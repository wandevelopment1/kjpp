<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BidangUsaha;
use App\Models\KepadaKabKota;
use App\Models\KepadaProvinsi;
use App\Models\Kepemilikan;
use App\Models\Obyek;
use App\Models\Penawaran;
use App\Models\User;
use App\Models\PenanggungJawab\Company;
use App\Models\PenanggungJawab\Inspeksi;
use App\Models\PenanggungJawab\PenanggungPenilai;
use App\Models\PenanggungJawab\Penilai;
use App\Models\PenanggungJawab\Reviewer;
use App\Models\PendekatanPenilaian;
use App\Models\PenggunaLaporan\JenisIndustri;
use App\Models\PenggunaLaporan\JenisPengguna;
use App\Models\PenggunaLaporan\Nama;
use App\Models\PenggunaLaporan\Pt;
use App\Models\StatusKepemilikan;
use App\Models\JenisJasa;
use App\Models\JenisLaporan;
use App\Models\MetodePenilaian;
use App\Models\Nilai;
use App\Models\TipeProperti;
use App\Models\Tujuan;
use App\Models\UiConfigGroup;
use App\Support\PenawaranApprovalMatrix;
use App\Support\PenawaranPlaceholderResolver;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Notifications\PenawaranApproved;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf;

class PenawaranController extends Controller
{
    protected $penawaran;

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('can:admin.penawaran.index')->only('index', 'show', 'viewTemplateFile', 'downloadFinalInvoice');
        $this->middleware('can:admin.penawaran.create')->only('create', 'store');
        $this->middleware('can:admin.penawaran.edit')->only('edit', 'update', 'sort', 'uploadTemplateFile', 'updateLaporan');
        $this->middleware('can:admin.penawaran.delete')->only('destroy');
        $this->middleware('can:admin.penawaran.approval')->only('approve');
    }

    public function index(Request $request)
    {
        $items = $this->buildPenawaranList($request);

        return view('admin.penawaran.index', [
            'items' => $items,
            'title' => 'Penawaran',
            'subTitle' => 'Penawaran',
            'routeBase' => 'admin.penawaran',
            'listRoute' => 'admin.penawaran.index',
            'isAccWorkflowList' => false,
            'statusScope' => null,
            'showCreateButton' => true,
        ]);
    }


    public function create()
    {
        return view('admin.penawaran.form', $this->formPayload());
    }

    public function store(Request $request)
    {
        $user = $request->user();
        $data = $this->validatedData($request);
        $data['user_id'] = $user->id;
        $data['owner_role_id'] = PenawaranApprovalMatrix::resolveOwnerRoleId($user);

        Penawaran::create($data);

        return redirect()->route('admin.penawaran.index')
            ->with('success', 'Penawaran created successfully.');
    }

    public function edit(Penawaran $penawaran)
    {
        return view('admin.penawaran.form', $this->formPayload($penawaran));
    }

    public function show(Penawaran $penawaran)
    {
        $penawaran->load(
            'owner',
            'penanggungJawabCompany',
            'penanggungJawabPenanggungPenilai',
            'penanggungJawabPenilai',
            'penanggungJawabReviewer',
            'penanggungJawabInspeksi',
            'penggunaLaporanPt',
            'penggunaLaporanNama',
            'penggunaLaporanJenisPengguna',
            'penggunaLaporanJenisIndustri',
            'kepadaKabKota',
            'kepadaProvinsi',
            'nasabahKabKota',
            'nasabahProvinsi',
            'statusKepemilikan',
            'bidangUsaha',
            'penilaianTujuan',
            'penilaianJenisLaporan',
            'penilaianNilai',
            'penilaianJenisJasa',
            'penilaianTipeProperti',
            'penilaianPendekatan',
            'penilaianMetode',
            'templateFiles'
        );

        $obyekItems = $this->buildObyekPenilaianItems($penawaran);

        $templateGroups = $this->templateGroups($penawaran);

        $disabledTemplateGroups = match ($penawaran->status) {
            'draft_1' => [],
            'acc_1' => array_keys($this->draftTemplateGroups()),
            'acc_2' => array_merge(
                array_keys($this->draftTemplateGroups()),
                array_keys($this->accTemplateGroups())
            ),
            default => array_keys($this->draftTemplateGroups()),
        };

        $renderedTemplates = [];

        foreach ($templateGroups as $slug => $label) {
            $renderedTemplates[$slug] = $this->renderTemplatesForGroup($slug, $penawaran);
        }

        $placeholders = $this->availablePlaceholders($penawaran);
        $exampleTemplate = $this->exampleTemplate();
        $uploadedTemplates = $penawaran->templateFiles->keyBy('template_group');

        $manualTemplateDescription = $penawaran->status === 'draft_1'
            ? 'Unggah file PDF final per template (Draft 1, Kendali Klien, Rencana Penugasan). Jika sudah diunggah, klik "Lihat PDF" untuk membuka.'
            : 'Unggah file PDF final per template (draft2, lampirantahapakhir, rcekliskelengkapanakhir). Jika sudah diunggah, klik "Lihat PDF" untuk membuka.';

        $finalInvoiceHtml = null;
        if ($penawaran->status === 'acc_2') {
            $invoiceTemplate = ui_value('invoice', 'invoice');
            if ($invoiceTemplate) {
                $finalInvoiceHtml = $this->applyPlaceholders($invoiceTemplate, $penawaran);
            }
        }

        return view('admin.penawaran.show', [
            'title' => 'Detail Penawaran',
            'subTitle' => $penawaran->kepada_no_spk ?? 'Detail',
            'penawaran' => $penawaran,
            'templateGroups' => $templateGroups,
            'renderedTemplates' => $renderedTemplates,
            'placeholders' => $placeholders,
            'exampleTemplate' => $exampleTemplate,
            'uploadedTemplates' => $uploadedTemplates,
            'manualTemplateDescription' => $manualTemplateDescription,
            'disabledTemplateGroups' => $disabledTemplateGroups,
            'finalInvoiceHtml' => $finalInvoiceHtml,
            'obyekItems' => $obyekItems,
        ]);
    }

    public function exportTemplate(Penawaran $penawaran, string $group)
    {
        $templateGroups = $this->templateGroups($penawaran);

        abort_unless(array_key_exists($group, $templateGroups), 404);

        $templates = $this->renderTemplatesForGroup($group, $penawaran);

        abort_if(empty($templates), 404, 'Template belum dikonfigurasi.');

        $filename = Str::slug(($penawaran->kepada_no_spk ?? 'penawaran') . '-' . $templateGroups[$group]) . '.pdf';

        $pdf = Pdf::loadView('pdf.penawaran-template', [
            'penawaran' => $penawaran,
            'groupLabel' => $templateGroups[$group],
            'templates' => $templates,
        ])->setPaper('a4');

        return $pdf->download($filename);
    }

    public function downloadFinalInvoice(Penawaran $penawaran)
    {
        abort_unless($penawaran->status === 'acc_2', 403, 'Invoice akhir hanya tersedia saat penawaran final.');

        $invoiceTemplate = ui_value('invoice', 'invoice');
        abort_unless($invoiceTemplate, 404, 'Template invoice belum dikonfigurasi.');

        $content = $this->applyPlaceholders($invoiceTemplate, $penawaran);
        abort_if(blank($content), 404, 'Konten invoice kosong.');

        $filename = Str::slug(($penawaran->kepada_no_spk ?? 'penawaran') . '-invoice-akhir') . '.pdf';

        $pdf = Pdf::loadView('pdf.penawaran-invoice', [
            'penawaran' => $penawaran,
            'content' => $content,
        ])->setPaper('a4');

        return $pdf->download($filename);
    }

    public function uploadTemplateFile(Request $request, Penawaran $penawaran, string $group)
    {
        $templateGroups = $this->templateGroups($penawaran);

        abort_unless(array_key_exists($group, $templateGroups), 404);

        $validated = $request->validate([
            'template_file' => ['required', 'file', 'mimes:pdf', 'max:5120'],
        ]);

        $file = $validated['template_file'];
        $disk = 'public';

        $existing = $penawaran->templateFiles()
            ->where('template_group', $group)
            ->first();

        if ($existing && $existing->file_path) {
            Storage::disk($disk)->delete($existing->file_path);
        }

        $path = $file->store('penawaran/templates', $disk);

        $penawaran->templateFiles()->updateOrCreate(
            ['template_group' => $group],
            [
                'file_path' => $path,
                'original_name' => $file->getClientOriginalName(),
                'uploaded_by' => $request->user()->id,
            ]
        );

        return back()->with('success', "File {$templateGroups[$group]} berhasil diunggah.");
    }

    public function viewTemplateFile(Penawaran $penawaran, string $group)
    {
        $templateGroups = $this->templateGroups($penawaran);

        abort_unless(array_key_exists($group, $templateGroups), 404);

        $file = $penawaran->templateFiles()
            ->where('template_group', $group)
            ->firstOrFail();

        $disk = 'public';

        abort_unless(Storage::disk($disk)->exists($file->file_path), 404);

        $fullPath = Storage::disk($disk)->path($file->file_path);

        return response()->file($fullPath, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . ($file->original_name ?? basename($file->file_path)) . '"',
        ]);
    }

    private function templateGroups(?Penawaran $penawaran = null): array
    {
        $groups = $this->draftTemplateGroups();

        if ($penawaran && $penawaran->status !== 'draft_1') {
            $groups = array_merge($groups, $this->accTemplateGroups());
        }

        return $groups;
    }

    private function draftTemplateGroups(): array
    {
        return [
            'draft-1' => 'Draft 1',
            'kendali-klien' => 'Rencana Penugasan',
            'rencana-penugasan' => 'Kendali Klien   ',
        ];
    }

    private function accTemplateGroups(): array
    {
        return [
            'draft-2' => 'Draft 2',
            'lampiran-tahap-akhir' => 'Lampiran Tahap Akhir',
            'ceklis-kelengkapan-akhir' => 'Ceklis Kelengkapan Akhir',
        ];
    }

    private function buildPenawaranList(Request $request, ?string $statusScope = null)
    {
        $query = Penawaran::query()->with('owner');

        if ($statusScope === 'acc1') {
            $query->where('status', 'acc_1');
        } elseif ($statusScope === 'acc2') {
            $query->where('status', 'acc_2');
        }

        $query = $this->applyPenawaranVisibilityScope($query, $request->user());

        if ($request->filled('status') && in_array($request->status, ['draft_1', 'acc_1', 'acc_2'], true)) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('kepada_no_spk', 'like', "%{$search}%")
                    ->orWhere('kepada_no_lingkup', 'like', "%{$search}%")
                    ->orWhere('kepada_nama', 'like', "%{$search}%")
                    ->orWhere('nasabah_nama', 'like', "%{$search}%")
                    ->orWhere('kepada_tgl_spk', 'like', "%{$search}%");
            });
        }

        $perPage = $request->input('per_page', 10);

        return $query->latest()->paginate($perPage)->appends($request->all());
    }

    private function renderTemplatesForGroup(string $groupSlug, Penawaran $penawaran): array
    {
        $group = UiConfigGroup::where('slug', $groupSlug)
            ->with(['configs' => function ($query) {
                $query->orderBy('id');
            }])->first();

        if (!$group) {
            return [];
        }

        return $group->configs
            ->map(function ($config) use ($penawaran) {
                return [
                    'id' => $config->id,
                    'key' => $config->key,
                    'label' => $config->label,
                    'content' => $this->applyPlaceholders($config->value ?? '', $penawaran),
                ];
            })
            ->values()
            ->all();
    }

    private function applyPlaceholders(?string $content, Penawaran $penawaran): string
    {
        if (!$content) {
            return '';
        }

        return PenawaranPlaceholderResolver::replace($content, $penawaran);
    }

    private function availablePlaceholders(Penawaran $penawaran): array
    {
        $map = PenawaranPlaceholderResolver::map($penawaran);

        return collect($map)
            ->map(fn ($value, $key) => [
                'token' => '{{' . $key . '}}',
                'sample' => $value,
            ])
            ->values()
            ->all();
    }

    private function exampleTemplate(): string
    {
        return <<<HTML
<p>Kepada {{nama}},</p>
<p>Alamat: {{alamat}}</p>
<p>Nomor SPK: <strong>{{no_spk}}</strong></p>
<p>Pada tanggal {{tanggal}}, kami mengajukan biaya jasa sebesar {{biaya_jasa_rupiah}} dengan ketentuan pembayaran split sesuai form: {{penilaian_pembayaran_split}}.</p>
HTML;
    }

    public function update(Request $request, Penawaran $penawaran)
    {
        $updatedData = $this->validatedData($request, $penawaran);
        $updatedData['owner_role_id'] = $penawaran->owner_role_id ?? PenawaranApprovalMatrix::resolveOwnerRoleId($request->user());

        $penawaran->update($updatedData);

        return redirect()->route('admin.penawaran.index')
            ->with('success', 'Penawaran updated successfully.');
    }
    public function destroy(Penawaran $penawaran)
    {
        $penawaran->delete();

        return redirect()->route('admin.penawaran.index')
            ->with('success', 'Penawaran deleted successfully.');
    }

    public function approve(Request $request, Penawaran $penawaran)
    {
        if (!$this->userCanApprovePenawaran($request->user(), $penawaran)) {
            abort(403, 'Anda tidak berhak menyetujui penawaran ini.');
        }

        $allowedStatuses = ['draft_1', 'acc_1', 'acc_2'];
        $requestedStatus = $request->input('status', 'acc_1');

        if (!in_array($requestedStatus, $allowedStatuses, true)) {
            $requestedStatus = 'acc_1';
        }

        if ($requestedStatus === $penawaran->status) {
            $label = match ($requestedStatus) {
                'acc_1' => 'ACC 1',
                'acc_2' => 'ACC 2',
                default => 'Draft 1',
            };
            return back()->with('info', "Penawaran sudah berstatus {$label}.");
        }

        $approvedAt = match ($requestedStatus) {
            'draft_1' => null,
            default => $penawaran->approved_at ?? now(),
        };

        $penawaran->update([
            'status' => $requestedStatus,
            'approved_at' => $approvedAt,
        ]);

        if ($requestedStatus === 'acc_1') {
            $penawaran->loadMissing('owner');

            if ($penawaran->owner && $penawaran->owner->isNot($request->user())) {
                $penawaran->owner->notify(new PenawaranApproved($penawaran));
            }

            return back()->with('success', 'Penawaran disetujui menjadi ACC 1.');
        }

        if ($requestedStatus === 'acc_2') {
            return back()->with('success', 'Penawaran disetujui menjadi ACC 2.');
        }

        return back()->with('info', 'Status penawaran dikembalikan ke Draft 1.');
    }

    public function updateLaporan(Request $request, Penawaran $penawaran)
    {
        if ($penawaran->status !== 'acc_2') {
            return back()->with('error', 'No. Laporan hanya dapat diisi ketika penawaran sudah Final.');
        }

        $data = $request->validate([
            'laporan_nomor' => ['nullable', 'string', 'max:100'],
            'laporan_tanggal' => ['nullable', 'date'],
        ]);

        $penawaran->update([
            'laporan_nomor' => $data['laporan_nomor'] ?? $penawaran->laporan_nomor,
            'laporan_tanggal' => $data['laporan_tanggal'] ?? $penawaran->laporan_tanggal,
        ]);

        return back()->with('success', 'No. Laporan berhasil diperbarui.');
    }

    private function validatedData(Request $request, ?Penawaran $penawaran = null): array
    {
        $data = $request->validate($this->rules());
        $data = $this->normalizeMoneyFields($data);
        $data = $this->normalizeBooleanFields($data);
        $data = $this->fillNasabahFromKepada($data);
        $data = $this->fillObyekPenilaianDefaults($data);
        $data = $this->normalizeObyekPenilaianCollections($data);
        $data = $this->applyStatus($request, $penawaran, $data);
        $data = $this->syncLaporanFields($data, $penawaran);

        return $data;
    }

    private function fillObyekPenilaianDefaults(array $data): array
    {
        if (empty($data['obyek_penilaian_debitur']) && !empty($data['kepada_nama'])) {
            $data['obyek_penilaian_debitur'] = $data['kepada_nama'];
        }

        if (empty($data['obyek_penilaian_lokasi']) && !empty($data['nasabah_alamat'])) {
            $data['obyek_penilaian_lokasi'] = $data['nasabah_alamat'];
        }

        if (empty($data['obyek_penilaian_kode_pos']) && !empty($data['kepada_kode_pos'])) {
            $data['obyek_penilaian_kode_pos'] = $data['kepada_kode_pos'];
        }

        return $data;
    }

    private function rules(): array
    {
        return [
            'penanggung_jawab_company_id' => ['nullable', 'exists:penanggung_jawab_companies,id'],
            'penanggung_jawab_penanggung_penilai_id' => ['nullable', 'exists:penanggung_jawab_penanggung_penilai,id'],
            'penanggung_jawab_penilai_id' => ['nullable', 'exists:penanggung_jawab_penilai,id'],
            'penanggung_jawab_reviewer_id' => ['nullable', 'exists:penanggung_jawab_reviewers,id'],
            'penanggung_jawab_inspeksi_id' => ['nullable', 'exists:penanggung_jawab_inspeksi,id'],
            'pengguna_laporan_pt_id' => ['nullable', 'exists:pengguna_laporan_pts,id'],
            'pengguna_laporan_nama_id' => ['nullable', 'exists:pengguna_laporan_nama,id'],
            'pengguna_laporan_jenis_pengguna_id' => ['nullable', 'exists:pengguna_laporan_jenis_pengguna,id'],
            'pengguna_laporan_jenis_industri_id' => ['nullable', 'exists:pengguna_laporan_jenis_industri,id'],
            'pengguna_laporan_alamat' => ['nullable', 'string'],
            'pengguna_laporan_kab_kota' => ['nullable', 'string', 'max:255'],
            'pengguna_laporan_provinsi' => ['nullable', 'string', 'max:255'],
            'pengguna_laporan_kode_pos' => ['nullable', 'string', 'max:20'],
            'kepada_no_spk' => ['nullable', 'string', 'max:100'],
            'kepada_no_lingkup' => ['nullable', 'string', 'max:100'],
            'kepada_tgl_lingkup' => ['nullable', 'date'],
            'kepada_tgl_spk' => ['nullable', 'date'],
            'kepada_pt' => ['nullable', 'string', 'max:255'],
            'kepada_nama' => ['nullable', 'string', 'max:255'],
            'kepada_jabatan' => ['nullable', 'string', 'max:255'],
            'kepada_alamat_pemberi_tugas' => ['nullable', 'string'],
            'kepada_desa_dan_kecamatan' => ['nullable', 'string', 'max:255'],
            'kepada_kab_kota_id' => ['nullable', 'exists:kepada_kab_kotas,id'],
            'kepada_provinsi_id' => ['nullable', 'exists:kepada_provinsis,id'],
            'kepada_kode_pos' => ['nullable', 'string', 'max:20'],
            'kepada_email' => ['nullable', 'email', 'max:255'],
            'laporan_nomor' => ['nullable', 'string', 'max:100'],
            'laporan_tanggal' => ['nullable', 'date'],
            'nasabah_nama' => ['nullable', 'string', 'max:255'],
            'nasabah_alamat' => ['nullable', 'string'],
            'nasabah_kab_kota_id' => ['nullable', 'exists:kepada_kab_kotas,id'],
            'nasabah_provinsi_id' => ['nullable', 'exists:kepada_provinsis,id'],
            'nasabah_kode_pos' => ['nullable', 'string', 'max:20'],
            'nasabah_npwp' => ['nullable', 'string', 'max:50'],
            'nasabah_go_publik' => ['nullable', 'boolean'],
            'status_kepemilikan_id' => ['nullable', 'exists:status_kepemilikans,id'],
            'bidang_usaha_id' => ['nullable', 'exists:bidang_usahas,id'],
            'nasabah_telepon' => ['nullable', 'string', 'max:50'],
            'nasabah_email' => ['nullable', 'email', 'max:255'],
            'penilaian_tujuan_id' => ['nullable', 'exists:tujuans,id'],
            'penilaian_jenis_laporan_id' => ['nullable', 'exists:jenis_laporans,id'],
            'penilaian_jangka_waktu' => ['nullable', 'string', 'max:255'],
            'penilaian_nilai_id' => ['nullable', 'exists:nilais,id'],
            'penilaian_jumlah_buku' => ['nullable', 'integer', 'min:0'],
            'penilaian_jenis_jasa_id' => ['nullable', 'exists:jenis_jasas,id'],
            'penilaian_tipe_properti_id' => ['nullable', 'exists:tipe_propertis,id'],
            'penilaian_biaya_jasa' => ['nullable', 'numeric', 'min:0'],
            'penilaian_transport_akomodasi' => ['nullable', 'numeric', 'min:0'],
            'penilaian_ppn_included' => ['nullable', 'boolean'],
            'penilaian_rekening_pembayaran' => ['nullable', 'string', 'max:255'],
            'penilaian_pembayaran_split' => ['nullable', 'boolean'],
            'penilaian_pendekatan_penilaian_id' => ['nullable', 'exists:pendekatan_penilaians,id'],
            'penilaian_metode_penilaian_id' => ['nullable', 'exists:metode_penilaians,id'],
            'status' => ['nullable', Rule::in(['draft_1', 'acc_1', 'acc_2'])],
            'obyek_penilaian_obyek_id' => ['nullable', 'exists:obyeks,id'],
            'obyek_penilaian_obyek_ids' => ['nullable', 'array'],
            'obyek_penilaian_obyek_ids.*' => ['nullable', 'integer', 'exists:obyeks,id'],
            'obyek_penilaian_debitur' => ['nullable', 'string', 'max:255'],
            'obyek_penilaian_legalitas' => ['nullable', 'string'],
            'obyek_penilaian_legalitas_items' => ['nullable', 'array'],
            'obyek_penilaian_legalitas_items.*' => ['nullable', 'string', 'max:255'],
            'obyek_penilaian_items' => ['nullable', 'array'],
            'obyek_penilaian_items.*.obyek_id' => ['nullable', 'integer', 'exists:obyeks,id'],
            'obyek_penilaian_items.*.debitur' => ['nullable', 'string', 'max:255'],
            'obyek_penilaian_items.*.legalitas_items' => ['nullable', 'array'],
            'obyek_penilaian_items.*.legalitas_items.*' => ['nullable', 'string', 'max:255'],
            'obyek_penilaian_items.*.lokasi' => ['nullable', 'string'],
            'obyek_penilaian_items.*.kepemilikan_id' => ['nullable', 'exists:kepemilikans,id'],
            'obyek_penilaian_items.*.kab_kota_id' => ['nullable', 'exists:kepada_kab_kotas,id'],
            'obyek_penilaian_items.*.provinsi_id' => ['nullable', 'exists:kepada_provinsis,id'],
            'obyek_penilaian_items.*.kode_pos' => ['nullable', 'string', 'max:20'],
            'obyek_penilaian_items.*.luas_tanah' => ['nullable', 'string', 'max:100'],
            'obyek_penilaian_items.*.imb' => ['nullable', 'string', 'max:255'],
            'obyek_penilaian_items.*.luas_bangunan' => ['nullable', 'string', 'max:100'],
            'obyek_penilaian_items.*.tipe_properti_id' => ['nullable', 'exists:tipe_propertis,id'],
            'obyek_penilaian_lokasi' => ['nullable', 'string'],
            'obyek_penilaian_kepemilikan_id' => ['nullable', 'exists:kepemilikans,id'],
            'obyek_penilaian_kab_kota_id' => ['nullable', 'exists:kepada_kab_kotas,id'],
            'obyek_penilaian_provinsi_id' => ['nullable', 'exists:kepada_provinsis,id'],
            'obyek_penilaian_kode_pos' => ['nullable', 'string', 'max:20'],
            'obyek_penilaian_luas_tanah' => ['nullable', 'string', 'max:100'],
            'obyek_penilaian_imb' => ['nullable', 'string', 'max:255'],
            'obyek_penilaian_luas_bangunan' => ['nullable', 'string', 'max:100'],
            'obyek_penilaian_tipe_properti_id' => ['nullable', 'exists:tipe_propertis,id'],
        ];
    }

    private function normalizeMoneyFields(array $data): array
    {
        foreach (['penilaian_biaya_jasa', 'penilaian_transport_akomodasi'] as $field) {
            if (array_key_exists($field, $data) && $data[$field] !== null && $data[$field] !== '') {
                $normalized = str_replace([',', ' '], '', $data[$field]);
                $data[$field] = (float) $normalized;
            } else {
                $data[$field] = null;
            }
        }

        return $data;
    }

    private function normalizeBooleanFields(array $data): array
    {
        $data['nasabah_go_publik'] = $this->toBoolean($data['nasabah_go_publik'] ?? false);
        $data['penilaian_ppn_included'] = $this->toBoolean($data['penilaian_ppn_included'] ?? false);
        $data['penilaian_pembayaran_split'] = $this->toBoolean($data['penilaian_pembayaran_split'] ?? true);

        return $data;
    }

    private function syncLaporanFields(array $data, ?Penawaran $penawaran): array
    {
        $currentStatus = $data['status'] ?? ($penawaran->status ?? 'draft_1');
        $existingNomor = $penawaran?->laporan_nomor;
        $existingTanggal = $penawaran?->laporan_tanggal;

        if ($currentStatus !== 'acc_2') {
            $data['laporan_nomor'] = $existingNomor;
            $data['laporan_tanggal'] = $existingTanggal;
        } else {
            $data['laporan_nomor'] = $data['laporan_nomor'] ?? $existingNomor;
            $data['laporan_tanggal'] = $data['laporan_tanggal'] ?? $existingTanggal;
        }

        return $data;
    }

    private function toBoolean($value): bool
    {
        $bool = filter_var($value, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);

        return $bool ?? false;
    }

    private function applyStatus(Request $request, ?Penawaran $penawaran, array $data): array
    {
        $canApprove = $request->user()->can('admin.penawaran.approval');

        if ($canApprove && isset($data['status'])) {
            $allowed = ['draft_1', 'acc_1', 'acc_2'];
            $data['status'] = in_array($data['status'], $allowed, true) ? $data['status'] : 'draft_1';
        } else {
            $data['status'] = $penawaran->status ?? 'draft_1';
        }

        return $data;
    }

    private function fillNasabahFromKepada(array $data): array
    {
        $data['nasabah_nama'] = $data['nasabah_nama'] ?: ($data['kepada_nama'] ?? null);

        if (empty($data['nasabah_alamat'])) {
            $parts = array_filter([
                $data['kepada_alamat_pemberi_tugas'] ?? null,
                $data['kepada_desa_dan_kecamatan'] ?? null,
            ]);
            $data['nasabah_alamat'] = $parts ? implode(', ', $parts) : null;
        }

        $mapping = [
            'nasabah_kab_kota_id' => 'kepada_kab_kota_id',
            'nasabah_provinsi_id' => 'kepada_provinsi_id',
            'nasabah_kode_pos' => 'kepada_kode_pos',
            'nasabah_email' => 'kepada_email',
        ];

        foreach ($mapping as $target => $source) {
            if (empty($data[$target]) && !empty($data[$source])) {
                $data[$target] = $data[$source];
            }
        }

        return $data;
    }

    private function formPayload(?Penawaran $penawaran = null): array
    {
        return array_merge([
            'penawaran' => $penawaran,
        ], $this->formOptions());
    }

    private function formOptions(): array
    {
        return [
            'companies' => Company::orderBy('name')->get(),
            'penanggungPenilais' => PenanggungPenilai::orderBy('name')->get(),
            'penilais' => Penilai::orderBy('name')->get(),
            'reviewers' => Reviewer::orderBy('name')->get(),
            'inspeksis' => Inspeksi::orderBy('name')->get(),
            'pts' => Pt::orderBy('name')->get(),
            'penggunaNama' => Nama::orderBy('name')->get(),
            'penggunaJenis' => JenisPengguna::orderBy('name')->get(),
            'penggunaIndustri' => JenisIndustri::orderBy('name')->get(),
            'kabKotas' => KepadaKabKota::orderBy('name')->get(),
            'provinsis' => KepadaProvinsi::orderBy('name')->get(),
            'statusKepemilikans' => StatusKepemilikan::orderBy('name')->get(),
            'bidangUsahas' => BidangUsaha::orderBy('name')->get(),
            'tujuans' => Tujuan::orderBy('name')->get(),
            'jenisLaporans' => JenisLaporan::orderBy('name')->get(),
            'nilais' => Nilai::orderBy('name')->get(),
            'jenisJasas' => JenisJasa::orderBy('name')->get(),
            'tipePropertis' => TipeProperti::orderBy('name')->get(),
            'pendekatanPenilaians' => PendekatanPenilaian::orderBy('name')->get(),
            'metodePenilaians' => MetodePenilaian::orderBy('name')->get(),
            'obyeks' => Obyek::orderBy('name')->get(),
            'kepemilikans' => Kepemilikan::orderBy('name')->get(),
        ];
    }

    private function applyPenawaranVisibilityScope(Builder $query, User $user): Builder
    {
        return PenawaranApprovalMatrix::applyVisibilityScope($query, $user);
    }

    private function userCanApprovePenawaran(User $user, Penawaran $penawaran): bool
    {
        return PenawaranApprovalMatrix::userCanApprovePenawaran($user, $penawaran);
    }

    private function buildObyekPenilaianItems(Penawaran $penawaran): array
    {
        $items = collect($penawaran->obyek_penilaian_items ?? []);

        if ($items->isEmpty()) {
            $legacyLegalitas = collect($penawaran->obyek_penilaian_legalitas_items ?? [])
                ->map(fn ($value) => is_string($value) ? trim($value) : $value)
                ->filter(fn ($value) => filled($value))
                ->values()
                ->all();

            if (empty($legacyLegalitas) && filled($penawaran->obyek_penilaian_legalitas)) {
                $legacyLegalitas = [$penawaran->obyek_penilaian_legalitas];
            }

            $items = collect([
                [
                    'obyek_id' => $penawaran->obyek_penilaian_obyek_id,
                    'debitur' => $penawaran->obyek_penilaian_debitur,
                    'legalitas_items' => $legacyLegalitas,
                    'lokasi' => $penawaran->obyek_penilaian_lokasi,
                    'kepemilikan_id' => $penawaran->obyek_penilaian_kepemilikan_id,
                    'kab_kota_id' => $penawaran->obyek_penilaian_kab_kota_id,
                    'provinsi_id' => $penawaran->obyek_penilaian_provinsi_id,
                    'kode_pos' => $penawaran->obyek_penilaian_kode_pos,
                    'luas_tanah' => $penawaran->obyek_penilaian_luas_tanah,
                    'imb' => $penawaran->obyek_penilaian_imb,
                    'luas_bangunan' => $penawaran->obyek_penilaian_luas_bangunan,
                    'tipe_properti_id' => $penawaran->obyek_penilaian_tipe_properti_id,
                ],
            ])->filter(function ($item) {
                return filled($item['obyek_id'])
                    || filled($item['debitur'])
                    || filled($item['lokasi'])
                    || filled($item['kepemilikan_id'])
                    || filled($item['kab_kota_id'])
                    || filled($item['provinsi_id'])
                    || filled($item['kode_pos'])
                    || filled($item['luas_tanah'])
                    || filled($item['imb'])
                    || filled($item['luas_bangunan'])
                    || filled($item['tipe_properti_id'])
                    || !empty($item['legalitas_items']);
            })->values();
        }

        if ($items->isEmpty()) {
            return [];
        }

        $obyekIds = $items->pluck('obyek_id')->filter()->unique();
        $kepemilikanIds = $items->pluck('kepemilikan_id')->filter()->unique();
        $kabIds = $items->pluck('kab_kota_id')->filter()->unique();
        $provIds = $items->pluck('provinsi_id')->filter()->unique();
        $tipeIds = $items->pluck('tipe_properti_id')->filter()->unique();

        $obyekLookup = $obyekIds->isNotEmpty()
            ? Obyek::whereIn('id', $obyekIds)->pluck('name', 'id')
            : collect();
        $kepemilikanLookup = $kepemilikanIds->isNotEmpty()
            ? Kepemilikan::whereIn('id', $kepemilikanIds)->pluck('name', 'id')
            : collect();
        $kabLookup = $kabIds->isNotEmpty()
            ? KepadaKabKota::whereIn('id', $kabIds)->pluck('name', 'id')
            : collect();
        $provLookup = $provIds->isNotEmpty()
            ? KepadaProvinsi::whereIn('id', $provIds)->pluck('name', 'id')
            : collect();
        $tipeLookup = $tipeIds->isNotEmpty()
            ? TipeProperti::whereIn('id', $tipeIds)->pluck('name', 'id')
            : collect();

        return $items->map(function ($item, $index) use ($obyekLookup, $kepemilikanLookup, $kabLookup, $provLookup, $tipeLookup) {
            $legalitas = collect($item['legalitas_items'] ?? [])
                ->map(fn ($value) => is_string($value) ? trim($value) : $value)
                ->filter()
                ->values()
                ->all();

            return [
                'order' => $index + 1,
                'obyek_name' => $item['obyek_id'] ? ($obyekLookup[$item['obyek_id']] ?? '-') : '-',
                'debitur' => $item['debitur'] ?? '-',
                'lokasi' => $item['lokasi'] ?? '-',
                'kepemilikan_name' => $item['kepemilikan_id'] ? ($kepemilikanLookup[$item['kepemilikan_id']] ?? '-') : '-',
                'kab_name' => $item['kab_kota_id'] ? ($kabLookup[$item['kab_kota_id']] ?? '-') : '-',
                'prov_name' => $item['provinsi_id'] ? ($provLookup[$item['provinsi_id']] ?? '-') : '-',
                'kode_pos' => $item['kode_pos'] ?? '-',
                'luas_tanah' => $item['luas_tanah'] ?? '-',
                'imb' => $item['imb'] ?? '-',
                'luas_bangunan' => $item['luas_bangunan'] ?? '-',
                'tipe_properti_name' => $item['tipe_properti_id'] ? ($tipeLookup[$item['tipe_properti_id']] ?? '-') : '-',
                'legalitas_items' => $legalitas,
            ];
        })->all();
    }

    private function normalizeObyekPenilaianCollections(array $data): array
    {
        $items = collect($data['obyek_penilaian_items'] ?? [])
            ->map(function ($item) {
                $legalitas = collect($item['legalitas_items'] ?? [])
                    ->map(fn ($value) => is_string($value) ? trim($value) : $value)
                    ->filter(fn ($value) => filled($value))
                    ->values()
                    ->all();

                return [
                    'obyek_id' => filled($item['obyek_id'] ?? null) ? (int) $item['obyek_id'] : null,
                    'debitur' => filled($item['debitur'] ?? null) ? $item['debitur'] : null,
                    'legalitas_items' => $legalitas,
                    'lokasi' => filled($item['lokasi'] ?? null) ? $item['lokasi'] : null,
                    'kepemilikan_id' => filled($item['kepemilikan_id'] ?? null) ? (int) $item['kepemilikan_id'] : null,
                    'kab_kota_id' => filled($item['kab_kota_id'] ?? null) ? (int) $item['kab_kota_id'] : null,
                    'provinsi_id' => filled($item['provinsi_id'] ?? null) ? (int) $item['provinsi_id'] : null,
                    'kode_pos' => filled($item['kode_pos'] ?? null) ? $item['kode_pos'] : null,
                    'luas_tanah' => filled($item['luas_tanah'] ?? null) ? $item['luas_tanah'] : null,
                    'imb' => filled($item['imb'] ?? null) ? $item['imb'] : null,
                    'luas_bangunan' => filled($item['luas_bangunan'] ?? null) ? $item['luas_bangunan'] : null,
                    'tipe_properti_id' => filled($item['tipe_properti_id'] ?? null) ? (int) $item['tipe_properti_id'] : null,
                ];
            })
            ->filter(function ($item) {
                return filled($item['obyek_id'])
                    || filled($item['debitur'])
                    || filled($item['lokasi'])
                    || filled($item['kepemilikan_id'])
                    || filled($item['kab_kota_id'])
                    || filled($item['provinsi_id'])
                    || filled($item['kode_pos'])
                    || filled($item['luas_tanah'])
                    || filled($item['imb'])
                    || filled($item['luas_bangunan'])
                    || filled($item['tipe_properti_id'])
                    || !empty($item['legalitas_items']);
            })
            ->values();

        if ($items->isEmpty()) {
            $legacyLegalitas = collect($data['obyek_penilaian_legalitas_items'] ?? [])
                ->map(fn ($value) => is_string($value) ? trim($value) : $value)
                ->filter(fn ($value) => filled($value))
                ->values()
                ->all();

            if (filled($data['obyek_penilaian_legalitas'] ?? null)) {
                $legacyLegalitas = array_merge([$data['obyek_penilaian_legalitas']], $legacyLegalitas);
            }

            $items = collect([
                [
                    'obyek_id' => filled($data['obyek_penilaian_obyek_id'] ?? null) ? (int) $data['obyek_penilaian_obyek_id'] : null,
                    'debitur' => $data['obyek_penilaian_debitur'] ?? null,
                    'legalitas_items' => $legacyLegalitas,
                    'lokasi' => $data['obyek_penilaian_lokasi'] ?? null,
                    'kepemilikan_id' => $data['obyek_penilaian_kepemilikan_id'] ?? null,
                    'kab_kota_id' => $data['obyek_penilaian_kab_kota_id'] ?? null,
                    'provinsi_id' => $data['obyek_penilaian_provinsi_id'] ?? null,
                    'kode_pos' => $data['obyek_penilaian_kode_pos'] ?? null,
                    'luas_tanah' => $data['obyek_penilaian_luas_tanah'] ?? null,
                    'imb' => $data['obyek_penilaian_imb'] ?? null,
                    'luas_bangunan' => $data['obyek_penilaian_luas_bangunan'] ?? null,
                    'tipe_properti_id' => $data['obyek_penilaian_tipe_properti_id'] ?? null,
                ],
            ])->filter(function ($item) {
                return filled($item['obyek_id'])
                    || filled($item['debitur'])
                    || filled($item['lokasi'])
                    || filled($item['kepemilikan_id'])
                    || filled($item['kab_kota_id'])
                    || filled($item['provinsi_id'])
                    || filled($item['kode_pos'])
                    || filled($item['luas_tanah'])
                    || filled($item['imb'])
                    || filled($item['luas_bangunan'])
                    || filled($item['tipe_properti_id'])
                    || !empty($item['legalitas_items']);
            })->values();
        }

        $obyekIds = $items->pluck('obyek_id')->filter()->values();
        $primary = $items->first();
        $primaryLegalitas = collect($primary['legalitas_items'] ?? [])->values()->all();

        $data['obyek_penilaian_items'] = $items->isNotEmpty() ? $items->map(function ($item) {
            $item['legalitas_items'] = $item['legalitas_items'] ?? [];

            return $item;
        })->all() : null;

        $data['obyek_penilaian_obyek_ids'] = $obyekIds->isNotEmpty() ? $obyekIds->all() : null;
        $data['obyek_penilaian_obyek_id'] = $obyekIds->first() ?? null;
        $data['obyek_penilaian_debitur'] = $primary['debitur'] ?? null;
        $data['obyek_penilaian_lokasi'] = $primary['lokasi'] ?? null;
        $data['obyek_penilaian_kepemilikan_id'] = $primary['kepemilikan_id'] ?? null;
        $data['obyek_penilaian_kab_kota_id'] = $primary['kab_kota_id'] ?? null;
        $data['obyek_penilaian_provinsi_id'] = $primary['provinsi_id'] ?? null;
        $data['obyek_penilaian_kode_pos'] = $primary['kode_pos'] ?? null;
        $data['obyek_penilaian_luas_tanah'] = $primary['luas_tanah'] ?? null;
        $data['obyek_penilaian_imb'] = $primary['imb'] ?? null;
        $data['obyek_penilaian_luas_bangunan'] = $primary['luas_bangunan'] ?? null;
        $data['obyek_penilaian_tipe_properti_id'] = $primary['tipe_properti_id'] ?? null;
        $data['obyek_penilaian_legalitas_items'] = !empty($primaryLegalitas) ? $primaryLegalitas : null;
        $data['obyek_penilaian_legalitas'] = $primaryLegalitas[0] ?? null;

        return $data;
    }
}
