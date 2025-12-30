@extends('layout.admin.layout')

@php
$routeBase = 'admin.penawaran';
$model = isset($penawaran) ? $penawaran : null;

$title = $model ? 'Change' : 'Create';
$subTitle = $title;

$defaultObyekItem = [
    'obyek_id' => null,
    'debitur' => null,
    'legalitas_items' => [''],
    'lokasi' => null,
    'kepemilikan_id' => null,
    'kab_kota_id' => null,
    'provinsi_id' => null,
    'kode_pos' => null,
    'luas_tanah' => null,
    'imb' => null,
    'luas_bangunan' => null,
    'tipe_properti_id' => null,
    'nama_pemilik' => null,
    'alamat_pemilik' => null,
    'no_telepon_pemilik' => null,
];

$rawObyekItems = old('obyek_penilaian_items', $model?->obyek_penilaian_items ?? []);

if (empty($rawObyekItems)) {
    $legacyLegalitas = old(
        'obyek_penilaian_legalitas_items',
        $model?->obyek_penilaian_legalitas_items ?? ($model?->obyek_penilaian_legalitas ? [$model->obyek_penilaian_legalitas] : [])
    );
    $legacyLegalitas = collect($legacyLegalitas)
        ->map(fn ($item) => is_string($item) ? trim($item) : $item)
        ->filter()
        ->values()
        ->all();
    if (empty($legacyLegalitas)) {
        $legacyLegalitas = [''];
    }

    $rawObyekItems = [[
        'obyek_id' => old('obyek_penilaian_obyek_id', $model->obyek_penilaian_obyek_id ?? null),
        'debitur' => old('obyek_penilaian_debitur', $model->obyek_penilaian_debitur ?? null),
        'legalitas_items' => $legacyLegalitas,
        'lokasi' => old('obyek_penilaian_lokasi', $model->obyek_penilaian_lokasi ?? null),
        'kepemilikan_id' => old('obyek_penilaian_kepemilikan_id', $model->obyek_penilaian_kepemilikan_id ?? null),
        'kab_kota_id' => old('obyek_penilaian_kab_kota_id', $model->obyek_penilaian_kab_kota_id ?? null),
        'provinsi_id' => old('obyek_penilaian_provinsi_id', $model->obyek_penilaian_provinsi_id ?? null),
        'kode_pos' => old('obyek_penilaian_kode_pos', $model->obyek_penilaian_kode_pos ?? null),
        'luas_tanah' => old('obyek_penilaian_luas_tanah', $model->obyek_penilaian_luas_tanah ?? null),
        'imb' => old('obyek_penilaian_imb', $model->obyek_penilaian_imb ?? null),
        'luas_bangunan' => old('obyek_penilaian_luas_bangunan', $model->obyek_penilaian_luas_bangunan ?? null),
        'tipe_properti_id' => old('obyek_penilaian_tipe_properti_id', $model->obyek_penilaian_tipe_properti_id ?? null),
        'nama_pemilik' => old('obyek_penilaian_nama_pemilik', $model->obyek_penilaian_nama_pemilik ?? null),
        'alamat_pemilik' => old('obyek_penilaian_alamat_pemilik', $model->obyek_penilaian_alamat_pemilik ?? null),
        'no_telepon_pemilik' => old('obyek_penilaian_no_telepon_pemilik', $model->obyek_penilaian_no_telepon_pemilik ?? null),
    ]];
}

$obyekRepeaterItems = collect($rawObyekItems)
    ->map(function ($item) use ($defaultObyekItem) {
        $legalitas = collect($item['legalitas_items'] ?? [])
            ->map(fn ($value) => is_string($value) ? trim($value) : $value)
            ->filter()
            ->values()
            ->all();

        if (empty($legalitas)) {
            $legalitas = [''];
        }

        $item['legalitas_items'] = $legalitas;

        return array_merge($defaultObyekItem, $item);
    })
    ->values()
    ->all();

if (empty($obyekRepeaterItems)) {
    $obyekRepeaterItems = [$defaultObyekItem];
}

$nextObyekIndex = count($obyekRepeaterItems);
@endphp

@section('title', $title)

@section('content')
<div class="row gy-4">
    <div class="col-lg-12">
        <div class="card mt-24">
            <div class="card-body p-24">
                <form id="form"
                    action="{{ $model ? route($routeBase  . '.update', $model->id) : route($routeBase . '.store') }}"
                    method="POST" class="d-flex flex-column gap-24">

                    @csrf
                    @if($model)
                        @method('PUT')
                    @endif

                    {{-- Penanggung Jawab --}}
                    <div class="section">
                        <h5 class="mb-3">Penanggung Jawab</h5>
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label">Nama Perusahaan</label>
                                <select name="penanggung_jawab_company_id" class="form-select">
                                    <option value="">Pilih perusahaan</option>
                                    @foreach ($companies as $item)
                                    <option value="{{ $item->id }}" @selected(old('penanggung_jawab_company_id', $model->penanggung_jawab_company_id ?? null)==$item->id)>{{ $item->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Penanggung Penilai</label>
                                <select name="penanggung_jawab_penanggung_penilai_id" class="form-select">
                                    <option value="">Pilih penanggung penilai</option>
                                    @foreach ($penanggungPenilais as $item)
                                    <option value="{{ $item->id }}" @selected(old('penanggung_jawab_penanggung_penilai_id', $model->penanggung_jawab_penanggung_penilai_id ?? null)==$item->id)>{{ $item->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Penilai</label>
                                <select name="penanggung_jawab_penilai_id" class="form-select">
                                    <option value="">Pilih penilai</option>
                                    @foreach ($penilais as $item)
                                    <option value="{{ $item->id }}" @selected(old('penanggung_jawab_penilai_id', $model->penanggung_jawab_penilai_id ?? null)==$item->id)>{{ $item->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Reviewer</label>
                                <select name="penanggung_jawab_reviewer_id" class="form-select">
                                    <option value="">Pilih reviewer</option>
                                    @foreach ($reviewers as $item)
                                    <option value="{{ $item->id }}" @selected(old('penanggung_jawab_reviewer_id', $model->penanggung_jawab_reviewer_id ?? null)==$item->id)>{{ $item->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Inspeksi</label>
                                <select name="penanggung_jawab_inspeksi_id" class="form-select">
                                    <option value="">Pilih inspeksi</option>
                                    @foreach ($inspeksis as $item)
                                    <option value="{{ $item->id }}" @selected(old('penanggung_jawab_inspeksi_id', $model->penanggung_jawab_inspeksi_id ?? null)==$item->id)>{{ $item->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    {{-- Pengguna Laporan --}}
                    <div class="section">
                        <h5 class="mb-3">Pengguna Laporan</h5>
                        <div class="row g-3">
                            <div class="col-md-3">
                                <label class="form-label">PT</label>
                                <select id="pengguna_laporan_pt_id" name="pengguna_laporan_pt_id" class="form-select">
                                    <option value="">Pilih PT</option>
                                    @foreach ($pts as $item)
                                    <option value="{{ $item->id }}"
                                        data-alamat="{{ $item->alamat }}"
                                        data-kab="{{ $item->kab_kota }}"
                                        data-provinsi="{{ $item->provinsi }}"
                                        data-kodepos="{{ $item->kode_pos }}"
                                        @selected(old('pengguna_laporan_pt_id', $model->pengguna_laporan_pt_id ?? null)==$item->id)>
                                        {{ $item->name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Nama</label>
                                <select name="pengguna_laporan_nama_id" class="form-select">
                                    <option value="">Pilih nama</option>
                                    @foreach ($penggunaNama as $item)
                                    <option value="{{ $item->id }}" @selected(old('pengguna_laporan_nama_id', $model->pengguna_laporan_nama_id ?? null)==$item->id)>{{ $item->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Jenis Pengguna</label>
                                <select name="pengguna_laporan_jenis_pengguna_id" class="form-select">
                                    <option value="">Pilih jenis pengguna</option>
                                    @foreach ($penggunaJenis as $item)
                                    <option value="{{ $item->id }}" @selected(old('pengguna_laporan_jenis_pengguna_id', $model->pengguna_laporan_jenis_pengguna_id ?? null)==$item->id)>{{ $item->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Jenis Industri</label>
                                <select name="pengguna_laporan_jenis_industri_id" class="form-select">
                                    <option value="">Pilih jenis industri</option>
                                    @foreach ($penggunaIndustri as $item)
                                    <option value="{{ $item->id }}" @selected(old('pengguna_laporan_jenis_industri_id', $model->pengguna_laporan_jenis_industri_id ?? null)==$item->id)>{{ $item->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Alamat Pengguna Laporan</label>
                                <textarea id="pengguna_laporan_alamat" name="pengguna_laporan_alamat" class="form-control" rows="2">{{ old('pengguna_laporan_alamat', $model->pengguna_laporan_alamat ?? '') }}</textarea>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Kab./Kota</label>
                                <input type="text" id="pengguna_laporan_kab_kota" name="pengguna_laporan_kab_kota" class="form-control"
                                    value="{{ old('pengguna_laporan_kab_kota', $model->pengguna_laporan_kab_kota ?? '') }}">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Provinsi</label>
                                <input type="text" id="pengguna_laporan_provinsi" name="pengguna_laporan_provinsi" class="form-control"
                                    value="{{ old('pengguna_laporan_provinsi', $model->pengguna_laporan_provinsi ?? '') }}">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Kode Pos</label>
                                <input type="text" id="pengguna_laporan_kode_pos" name="pengguna_laporan_kode_pos" class="form-control"
                                    value="{{ old('pengguna_laporan_kode_pos', $model->pengguna_laporan_kode_pos ?? '') }}">
                            </div>
                        </div>
                    </div>

                    {{-- Kepada (Penerbit SPK) --}}
                    <div class="section">
                        <h5 class="mb-3">Kepada (Penerbit SPK)</h5>
                        <div class="row g-3">
                            <div class="col-md-3">
                                <label class="form-label">No. SPK</label>
                                <input type="text" name="kepada_no_spk" class="form-control"
                                    value="{{ old('kepada_no_spk', $model->kepada_no_spk ?? '') }}">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">No. Lingkup</label>
                                <input type="text" name="kepada_no_lingkup" class="form-control"
                                    value="{{ old('kepada_no_lingkup', $model->kepada_no_lingkup ?? '') }}">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Tanggal Lingkup</label>
                                <input type="date" name="kepada_tgl_lingkup" class="form-control"
                                    value="{{ old('kepada_tgl_lingkup', $model->kepada_tgl_lingkup ?? '') }}">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Tanggal SPK</label>
                                <input type="date" name="kepada_tgl_spk" class="form-control"
                                    value="{{ old('kepada_tgl_spk', $model->kepada_tgl_spk ?? '') }}">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">PT</label>
                                <input type="text" name="kepada_pt" class="form-control"
                                    value="{{ old('kepada_pt', $model->kepada_pt ?? '') }}">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Nama</label>
                                <input type="text" id="kepada_nama" name="kepada_nama" class="form-control"
                                    value="{{ old('kepada_nama', $model->kepada_nama ?? '') }}">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Jabatan</label>
                                <input type="text" name="kepada_jabatan" class="form-control"
                                    value="{{ old('kepada_jabatan', $model->kepada_jabatan ?? '') }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Alamat Pemberi Tugas</label>
                                <textarea id="kepada_alamat_pemberi_tugas" name="kepada_alamat_pemberi_tugas" class="form-control" rows="2">{{ old('kepada_alamat_pemberi_tugas', $model->kepada_alamat_pemberi_tugas ?? '') }}</textarea>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Desa / Kelurahan & Kecamatan</label>
                                <input type="text" id="kepada_desa_dan_kecamatan" name="kepada_desa_dan_kecamatan" class="form-control"
                                    value="{{ old('kepada_desa_dan_kecamatan', $model->kepada_desa_dan_kecamatan ?? '') }}"
                                    placeholder="Contoh: Desa. Kertaharja, Kec. Cikembar">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Kabupaten / Kota</label>
                                <select id="kepada_kab_kota_id" name="kepada_kab_kota_id" class="form-select">
                                    <option value="">Pilih kabupaten/kota</option>
                                    @foreach ($kabKotas as $item)
                                    <option value="{{ $item->id }}" @selected(old('kepada_kab_kota_id', $model->kepada_kab_kota_id ?? null)==$item->id)>{{ $item->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Provinsi</label>
                                <select id="kepada_provinsi_id" name="kepada_provinsi_id" class="form-select">
                                    <option value="">Pilih provinsi</option>
                                    @foreach ($provinsis as $item)
                                    <option value="{{ $item->id }}" @selected(old('kepada_provinsi_id', $model->kepada_provinsi_id ?? null)==$item->id)>{{ $item->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Kode Pos</label>
                                <input type="text" id="kepada_kode_pos" name="kepada_kode_pos" class="form-control"
                                    value="{{ old('kepada_kode_pos', $model->kepada_kode_pos ?? '') }}">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Email</label>
                                <input type="email" name="kepada_email" class="form-control"
                                    value="{{ old('kepada_email', $model->kepada_email ?? '') }}">
                            </div>
                        </div>
                    </div>

                    {{-- Status Penawaran --}}
                    <div class="section">
                        <h5 class="mb-3">Status Penawaran</h5>
                        <div class="row g-3">
                            <div class="col-md-4">
                                @can('admin.penawaran.approval')
                                <label class="form-label">Status</label>
                                <select name="status" class="form-select">
                                    <option value="draft_1" @selected(old('status', $model->status ?? 'draft_1')==='draft_1')>Draft 1</option>
                                    <option value="acc_1" @selected(old('status', $model->status ?? 'draft_1')==='acc_1')>ACC 1</option>
                                </select>
                                @else
                                <label class="form-label d-block">Status</label>
                                @php
                                    $statusLabel = ($model->status ?? 'draft_1') === 'acc_1' ? 'ACC 1' : 'Draft 1';
                                    $statusClass = ($model->status ?? 'draft_1') === 'acc_1' ? 'bg-success-subtle text-success-600' : 'bg-warning-subtle text-warning-600';
                                @endphp
                                <span class="badge {{ $statusClass }} px-12 py-8">{{ $statusLabel }}</span>
                                <input type="hidden" name="status" value="{{ $model->status ?? 'draft_1' }}">
                                @endcan
                            </div>
                        </div>
                    </div>

                    {{-- Nasabah / Klien --}}
                    <div class="section">
                        <h5 class="mb-3">Nasabah / Klien / Pembayar Jasa / Pemberi Tugas</h5>
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label">Nama</label>
                                <input type="text" id="nasabah_nama" name="nasabah_nama" class="form-control"
                                    value="{{ old('nasabah_nama', $model->nasabah_nama ?? '') }}">
                            </div>
                            <div class="col-md-8">
                                <label class="form-label">Alamat</label>
                                <textarea id="nasabah_alamat" name="nasabah_alamat" class="form-control" rows="2">{{ old('nasabah_alamat', $model->nasabah_alamat ?? '') }}</textarea>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Kabupaten / Kota</label>
                                <select id="nasabah_kab_kota_id" name="nasabah_kab_kota_id" class="form-select">
                                    <option value="">Pilih kabupaten/kota</option>
                                    @foreach ($kabKotas as $item)
                                    <option value="{{ $item->id }}" @selected(old('nasabah_kab_kota_id', $model->nasabah_kab_kota_id ?? null)==$item->id)>{{ $item->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Provinsi</label>
                                <select id="nasabah_provinsi_id" name="nasabah_provinsi_id" class="form-select">
                                    <option value="">Pilih provinsi</option>
                                    @foreach ($provinsis as $item)
                                    <option value="{{ $item->id }}" @selected(old('nasabah_provinsi_id', $model->nasabah_provinsi_id ?? null)==$item->id)>{{ $item->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Kode Pos</label>
                                <input type="text" id="nasabah_kode_pos" name="nasabah_kode_pos" class="form-control"
                                    value="{{ old('nasabah_kode_pos', $model->nasabah_kode_pos ?? '') }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">NPWP Pemberi Tugas</label>
                                <input type="text" name="nasabah_npwp" class="form-control"
                                    value="{{ old('nasabah_npwp', $model->nasabah_npwp ?? '') }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Go Publik</label>
                                <select name="nasabah_go_publik" class="form-select">
                                    <option value="0" @selected(old('nasabah_go_publik', $model->nasabah_go_publik ?? false)==false)>Tidak</option>
                                    <option value="1" @selected(old('nasabah_go_publik', $model->nasabah_go_publik ?? false)==true)>Ya</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Status Kepemilikan</label>
                                <select name="status_kepemilikan_id" class="form-select">
                                    <option value="">Pilih status</option>
                                    @foreach ($statusKepemilikans as $item)
                                    <option value="{{ $item->id }}" @selected(old('status_kepemilikan_id', $model->status_kepemilikan_id ?? null)==$item->id)>{{ $item->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Bidang Usaha</label>
                                <select name="bidang_usaha_id" class="form-select">
                                    <option value="">Pilih bidang usaha</option>
                                    @foreach ($bidangUsahas as $item)
                                    <option value="{{ $item->id }}" @selected(old('bidang_usaha_id', $model->bidang_usaha_id ?? null)==$item->id)>{{ $item->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Telepon</label>
                                <input type="text" name="nasabah_telepon" class="form-control"
                                    value="{{ old('nasabah_telepon', $model->nasabah_telepon ?? '') }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Email</label>
                                <input type="email" name="nasabah_email" class="form-control"
                                    value="{{ old('nasabah_email', $model->nasabah_email ?? '') }}">
                            </div>
                        </div>
                    </div>

                    {{-- Penilaian --}}
                    <div class="section">
                        <h5 class="mb-3">Penilaian</h5>
                        <div class="row g-3">
                            <div class="col-md-3">
                                <label class="form-label">Tujuan</label>
                                <select name="penilaian_tujuan_id" class="form-select">
                                    <option value="">Pilih tujuan</option>
                                    @foreach ($tujuans as $item)
                                    <option value="{{ $item->id }}" @selected(old('penilaian_tujuan_id', $model->penilaian_tujuan_id ?? null)==$item->id)>{{ $item->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Jenis Laporan</label>
                                <select name="penilaian_jenis_laporan_id" class="form-select">
                                    <option value="">Pilih jenis laporan</option>
                                    @foreach ($jenisLaporans as $item)
                                    <option value="{{ $item->id }}" @selected(old('penilaian_jenis_laporan_id', $model->penilaian_jenis_laporan_id ?? null)==$item->id)>{{ $item->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Jangka Waktu Penilaian</label>
                                <input type="text" name="penilaian_jangka_waktu" class="form-control"
                                    value="{{ old('penilaian_jangka_waktu', $model->penilaian_jangka_waktu ?? '') }}">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Nilai</label>
                                <select name="penilaian_nilai_id" class="form-select">
                                    <option value="">Pilih nilai</option>
                                    @foreach ($nilais as $item)
                                    <option value="{{ $item->id }}" @selected(old('penilaian_nilai_id', $model->penilaian_nilai_id ?? null)==$item->id)>{{ $item->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Jumlah Buku</label>
                                <input type="number" min="0" name="penilaian_jumlah_buku" class="form-control"
                                    value="{{ old('penilaian_jumlah_buku', $model->penilaian_jumlah_buku ?? '') }}">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Jenis Jasa</label>
                                <select name="penilaian_jenis_jasa_id" class="form-select">
                                    <option value="">Pilih jenis jasa</option>
                                    @foreach ($jenisJasas as $item)
                                    <option value="{{ $item->id }}" @selected(old('penilaian_jenis_jasa_id', $model->penilaian_jenis_jasa_id ?? null)==$item->id)>{{ $item->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Tipe Properti</label>
                                <select name="penilaian_tipe_properti_id" class="form-select">
                                    <option value="">Pilih tipe properti</option>
                                    @foreach ($tipePropertis as $item)
                                    <option value="{{ $item->id }}" @selected(old('penilaian_tipe_properti_id', $model->penilaian_tipe_properti_id ?? null)==$item->id)>{{ $item->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Biaya Jasa Penilaian (Rp)</label>
                                <input type="text" name="penilaian_biaya_jasa" class="form-control"
                                    value="{{ old('penilaian_biaya_jasa', $model->penilaian_biaya_jasa ?? '') }}">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Transport & Akomodasi (Rp)</label>
                                <input type="text" name="penilaian_transport_akomodasi" class="form-control"
                                    value="{{ old('penilaian_transport_akomodasi', $model->penilaian_transport_akomodasi ?? '') }}">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">PPN</label>
                                <select name="penilaian_ppn_included" class="form-select">
                                    <option value="1" @selected(old('penilaian_ppn_included', $model->penilaian_ppn_included ?? false)==true)>Sudah termasuk</option>
                                    <option value="0" @selected(old('penilaian_ppn_included', $model->penilaian_ppn_included ?? false)==false)>Belum termasuk</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Rekening Pembayaran</label>
                                <input type="text" name="penilaian_rekening_pembayaran" class="form-control"
                                    value="{{ old('penilaian_rekening_pembayaran', $model->penilaian_rekening_pembayaran ?? '') }}">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Pembayaran</label>
                                <select name="penilaian_pembayaran_split" class="form-select">
                                    <option value="1" @selected(old('penilaian_pembayaran_split', $model->penilaian_pembayaran_split ?? true)==true)>50% di muka / 50% sisa</option>
                                    <option value="0" @selected(old('penilaian_pembayaran_split', $model->penilaian_pembayaran_split ?? true)==false)>100% di muka</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Pendekatan Penilaian</label>
                                <select name="penilaian_pendekatan_penilaian_id" class="form-select">
                                    <option value="">Pilih pendekatan</option>
                                    @foreach ($pendekatanPenilaians as $item)
                                    <option value="{{ $item->id }}" @selected(old('penilaian_pendekatan_penilaian_id', $model->penilaian_pendekatan_penilaian_id ?? null)==$item->id)>{{ $item->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Metode Penilaian</label>
                                <select name="penilaian_metode_penilaian_id" class="form-select">
                                    <option value="">Pilih metode</option>
                                    @foreach ($metodePenilaians as $item)
                                    <option value="{{ $item->id }}" @selected(old('penilaian_metode_penilaian_id', $model->penilaian_metode_penilaian_id ?? null)==$item->id)>{{ $item->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    {{-- Obyek Penilaian --}}
                    <div class="section">
                        <h5 class="mb-3 d-flex align-items-center justify-content-between">
                            <span>Obyek Penilaian</span>
                            <button type="button" class="btn btn-outline-primary btn-sm d-flex align-items-center gap-1" data-obyek-add>
                                <iconify-icon icon="mdi:plus"></iconify-icon>
                                Tambah Obyek
                            </button>
                        </h5>
                        <div class="d-flex flex-column gap-3" data-obyek-container data-obyek-min="1">
                            @foreach ($obyekRepeaterItems as $index => $item)
                                @php $isPrimary = $index === 0; @endphp
                                <div class="border rounded-12 p-16" data-obyek-item data-obyek-index="{{ $index }}">
                                    <div class="d-flex align-items-center justify-content-between mb-3">
                                        <div>
                                            <p class="text-uppercase text-xs text-muted mb-1">Obyek</p>
                                            <h6 class="mb-0" data-obyek-title>Obyek #{{ $loop->iteration }}</h6>
                                        </div>
                                        <button type="button" class="btn btn-outline-danger btn-sm" data-obyek-remove>
                                            <iconify-icon icon="mdi:trash-can-outline"></iconify-icon>
                                        </button>
                                    </div>
                                    <div class="row g-3">
                                        <div class="col-md-4">
                                            <label class="form-label">Jenis Obyek</label>
                                            <select
                                                name="obyek_penilaian_items[{{ $index }}][obyek_id]"
                                                class="form-select"
                                                data-name-template="obyek_penilaian_items[__INDEX__][obyek_id]"
                                                data-sync-id="obyek_penilaian_obyek_id"
                                                @if($isPrimary) id="obyek_penilaian_obyek_id" @endif
                                            >
                                                <option value="">Pilih obyek</option>
                                                @foreach ($obyeks as $obyek)
                                                    <option value="{{ $obyek->id }}" @selected($item['obyek_id'] == $obyek->id)>{{ $obyek->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">Debitur</label>
                                            <input
                                                type="text"
                                                class="form-control"
                                                name="obyek_penilaian_items[{{ $index }}][debitur]"
                                                data-name-template="obyek_penilaian_items[__INDEX__][debitur]"
                                                value="{{ $item['debitur'] }}"
                                                data-sync-id="obyek_penilaian_debitur"
                                                @if($isPrimary) id="obyek_penilaian_debitur" @endif
                                            >
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">Kepemilikan</label>
                                            <select
                                                class="form-select"
                                                name="obyek_penilaian_items[{{ $index }}][kepemilikan_id]"
                                                data-name-template="obyek_penilaian_items[__INDEX__][kepemilikan_id]"
                                            >
                                                <option value="">Pilih kepemilikan</option>
                                                @foreach ($kepemilikans as $kepemilikan)
                                                    <option value="{{ $kepemilikan->id }}" @selected($item['kepemilikan_id'] == $kepemilikan->id)>{{ $kepemilikan->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-12">
                                            <label class="form-label">Legalitas</label>
                                            <div class="d-flex flex-column gap-2" data-legalitas-container data-legalitas-min="1">
                                                @foreach ($item['legalitas_items'] as $legalitas)
                                                    <div class="d-flex align-items-center gap-2" data-legalitas-item>
                                                        <input
                                                            type="text"
                                                            class="form-control"
                                                            name="obyek_penilaian_items[{{ $index }}][legalitas_items][]"
                                                            data-name-template="obyek_penilaian_items[__INDEX__][legalitas_items][]"
                                                            value="{{ $legalitas }}"
                                                        >
                                                        <button type="button" class="btn btn-outline-danger btn-icon" data-legalitas-remove>
                                                            <iconify-icon icon="mdi:trash-can-outline"></iconify-icon>
                                                        </button>
                                                    </div>
                                                @endforeach
                                            </div>
                                            <button type="button" class="btn btn-outline-primary btn-sm mt-2" data-legalitas-add>
                                                <iconify-icon icon="mdi:plus"></iconify-icon>
                                            </button>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Lokasi</label>
                                            <textarea
                                                class="form-control"
                                                rows="2"
                                                name="obyek_penilaian_items[{{ $index }}][lokasi]"
                                                data-name-template="obyek_penilaian_items[__INDEX__][lokasi]"
                                                data-sync-id="obyek_penilaian_lokasi"
                                                @if($isPrimary) id="obyek_penilaian_lokasi" @endif
                                            >{{ $item['lokasi'] }}</textarea>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label">Kabupaten / Kota</label>
                                            <select
                                                class="form-select"
                                                name="obyek_penilaian_items[{{ $index }}][kab_kota_id]"
                                                data-name-template="obyek_penilaian_items[__INDEX__][kab_kota_id]"
                                                data-sync-id="obyek_penilaian_kab_kota_id"
                                                @if($isPrimary) id="obyek_penilaian_kab_kota_id" @endif
                                            >
                                                <option value="">Pilih kabupaten/kota</option>
                                                @foreach ($kabKotas as $kab)
                                                    <option value="{{ $kab->id }}" @selected($item['kab_kota_id'] == $kab->id)>{{ $kab->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label">Provinsi</label>
                                            <select
                                                class="form-select"
                                                name="obyek_penilaian_items[{{ $index }}][provinsi_id]"
                                                data-name-template="obyek_penilaian_items[__INDEX__][provinsi_id]"
                                                data-sync-id="obyek_penilaian_provinsi_id"
                                                @if($isPrimary) id="obyek_penilaian_provinsi_id" @endif
                                            >
                                                <option value="">Pilih provinsi</option>
                                                @foreach ($provinsis as $prov)
                                                    <option value="{{ $prov->id }}" @selected($item['provinsi_id'] == $prov->id)>{{ $prov->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label">Kode Pos</label>
                                            <input
                                                type="text"
                                                class="form-control"
                                                name="obyek_penilaian_items[{{ $index }}][kode_pos]"
                                                data-name-template="obyek_penilaian_items[__INDEX__][kode_pos]"
                                                value="{{ $item['kode_pos'] }}"
                                                data-sync-id="obyek_penilaian_kode_pos"
                                                @if($isPrimary) id="obyek_penilaian_kode_pos" @endif
                                            >
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label">Luas Tanah</label>
                                            <input
                                                type="text"
                                                class="form-control"
                                                name="obyek_penilaian_items[{{ $index }}][luas_tanah]"
                                                data-name-template="obyek_penilaian_items[__INDEX__][luas_tanah]"
                                                value="{{ $item['luas_tanah'] }}"
                                            >
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label">IMB</label>
                                            <input
                                                type="text"
                                                class="form-control"
                                                name="obyek_penilaian_items[{{ $index }}][imb]"
                                                data-name-template="obyek_penilaian_items[__INDEX__][imb]"
                                                value="{{ $item['imb'] }}"
                                            >
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label">Luas Bangunan</label>
                                            <input
                                                type="text"
                                                class="form-control"
                                                name="obyek_penilaian_items[{{ $index }}][luas_bangunan]"
                                                data-name-template="obyek_penilaian_items[__INDEX__][luas_bangunan]"
                                                value="{{ $item['luas_bangunan'] }}"
                                            >
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label">Tipe Properti</label>
                                            <select
                                                class="form-select"
                                                name="obyek_penilaian_items[{{ $index }}][tipe_properti_id]"
                                                data-name-template="obyek_penilaian_items[__INDEX__][tipe_properti_id]"
                                                data-sync-id="obyek_penilaian_tipe_properti_id"
                                                @if($isPrimary) id="obyek_penilaian_tipe_properti_id" @endif
                                            >
                                                <option value="">Pilih tipe properti</option>
                                                @foreach ($tipePropertis as $tipe)
                                                    <option value="{{ $tipe->id }}" @selected($item['tipe_properti_id'] == $tipe->id)>{{ $tipe->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="d-flex align-items-center justify-content-center gap-3">
                        <a href="{{ route($routeBase . '.index') }}"
                            class="border border-danger-600 bg-hover-danger-200 text-danger-600 text-md px-56 py-11 radius-8">Back</a>
                        <button id="submit" type="submit"
                            class="btn btn-primary border border-primary-600 text-md px-56 py-12 radius-8">
                            {{ $title }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <template id="obyekCardTemplate">
        <div class="border rounded-12 p-16" data-obyek-item data-obyek-index="__INDEX__">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <div>
                    <p class="text-uppercase text-xs text-muted mb-1">Obyek</p>
                    <h6 class="mb-0" data-obyek-title>Obyek #__NO__</h6>
                </div>
                <button type="button" class="btn btn-outline-danger btn-sm" data-obyek-remove>
                    <iconify-icon icon="mdi:trash-can-outline"></iconify-icon>
                </button>
            </div>
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Jenis Obyek</label>
                    <select name="obyek_penilaian_items[__INDEX__][obyek_id]" class="form-select" data-name-template="obyek_penilaian_items[__INDEX__][obyek_id]">
                        <option value="">Pilih obyek</option>
                        @foreach ($obyeks as $obyek)
                            <option value="{{ $obyek->id }}">{{ $obyek->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Debitur</label>
                    <input type="text" class="form-control" name="obyek_penilaian_items[__INDEX__][debitur]" data-name-template="obyek_penilaian_items[__INDEX__][debitur]">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Kepemilikan</label>
                    <select class="form-select" name="obyek_penilaian_items[__INDEX__][kepemilikan_id]" data-name-template="obyek_penilaian_items[__INDEX__][kepemilikan_id]">
                        <option value="">Pilih kepemilikan</option>
                        @foreach ($kepemilikans as $kepemilikan)
                            <option value="{{ $kepemilikan->id }}">{{ $kepemilikan->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12">
                    <label class="form-label">Legalitas</label>
                    <div class="d-flex flex-column gap-2" data-legalitas-container data-legalitas-min="1">
                        <div class="d-flex align-items-center gap-2" data-legalitas-item>
                            <input type="text" class="form-control" name="obyek_penilaian_items[__INDEX__][legalitas_items][]" data-name-template="obyek_penilaian_items[__INDEX__][legalitas_items][]">
                            <button type="button" class="btn btn-outline-danger btn-icon" data-legalitas-remove>
                                <iconify-icon icon="mdi:trash-can-outline"></iconify-icon>
                            </button>
                        </div>
                    </div>
                    <button type="button" class="btn btn-outline-primary btn-sm mt-2" data-legalitas-add>
                        <iconify-icon icon="mdi:plus"></iconify-icon>
                    </button>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Lokasi</label>
                    <textarea class="form-control" rows="2" name="obyek_penilaian_items[__INDEX__][lokasi]" data-name-template="obyek_penilaian_items[__INDEX__][lokasi]"></textarea>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Kabupaten / Kota</label>
                    <select class="form-select" name="obyek_penilaian_items[__INDEX__][kab_kota_id]" data-name-template="obyek_penilaian_items[__INDEX__][kab_kota_id]">
                        <option value="">Pilih kabupaten/kota</option>
                        @foreach ($kabKotas as $kab)
                            <option value="{{ $kab->id }}">{{ $kab->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Provinsi</label>
                    <select class="form-select" name="obyek_penilaian_items[__INDEX__][provinsi_id]" data-name-template="obyek_penilaian_items[__INDEX__][provinsi_id]">
                        <option value="">Pilih provinsi</option>
                        @foreach ($provinsis as $prov)
                            <option value="{{ $prov->id }}">{{ $prov->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Kode Pos</label>
                    <input type="text" class="form-control" name="obyek_penilaian_items[__INDEX__][kode_pos]" data-name-template="obyek_penilaian_items[__INDEX__][kode_pos]">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Luas Tanah</label>
                    <input type="text" class="form-control" name="obyek_penilaian_items[__INDEX__][luas_tanah]" data-name-template="obyek_penilaian_items[__INDEX__][luas_tanah]">
                </div>
                <div class="col-md-3">
                    <label class="form-label">IMB</label>
                    <input type="text" class="form-control" name="obyek_penilaian_items[__INDEX__][imb]" data-name-template="obyek_penilaian_items[__INDEX__][imb]">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Luas Bangunan</label>
                    <input type="text" class="form-control" name="obyek_penilaian_items[__INDEX__][luas_bangunan]" data-name-template="obyek_penilaian_items[__INDEX__][luas_bangunan]">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Tipe Properti</label>
                    <select class="form-select" name="obyek_penilaian_items[__INDEX__][tipe_properti_id]" data-name-template="obyek_penilaian_items[__INDEX__][tipe_properti_id]">
                        <option value="">Pilih tipe properti</option>
                        @foreach ($tipePropertis as $tipe)
                            <option value="{{ $tipe->id }}">{{ $tipe->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </template>

    <template id="legalitasRowTemplate">
        <div class="d-flex align-items-center gap-2" data-legalitas-item>
            <input type="text" class="form-control" name="obyek_penilaian_items[__INDEX__][legalitas_items][]" data-name-template="obyek_penilaian_items[__INDEX__][legalitas_items][]">
            <button type="button" class="btn btn-outline-danger btn-icon" data-legalitas-remove>
                <iconify-icon icon="mdi:trash-can-outline"></iconify-icon>
            </button>
        </div>
    </template>
@endsection

@push('script')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const obyekContainer = document.querySelector('[data-obyek-container]');
        const addObyekBtn = document.querySelector('[data-obyek-add]');
        const obyekTemplate = document.getElementById('obyekCardTemplate');
        const legalitasTemplate = document.getElementById('legalitasRowTemplate');
        const minObyek = parseInt(obyekContainer?.dataset.obyekMin || '1', 10);

        const updatePrimarySync = () => {
            if (!obyekContainer) return;
            const cards = obyekContainer.querySelectorAll('[data-obyek-item]');
            cards.forEach((card, idx) => {
                card.querySelectorAll('[data-sync-id]').forEach((field) => {
                    field.removeAttribute('id');
                    if (idx === 0) {
                        field.id = field.dataset.syncId;
                    }
                });
            });
        };

        const applyIndexToCard = (card, index) => {
            card.dataset.obyekIndex = index;
            const title = card.querySelector('[data-obyek-title]');
            if (title) {
                title.textContent = `Obyek #${index + 1}`;
            }
            card.querySelectorAll('[data-name-template]').forEach((field) => {
                const template = field.dataset.nameTemplate;
                if (template) {
                    field.name = template.replace(/__INDEX__/g, index);
                }
            });
        };

        const updateObyekRemoveState = () => {
            if (!obyekContainer) return;
            const total = obyekContainer.querySelectorAll('[data-obyek-item]').length;
            obyekContainer.querySelectorAll('[data-obyek-remove]').forEach((btn) => {
                btn.disabled = total <= minObyek;
            });
        };

        const reindexObyekCards = () => {
            if (!obyekContainer) return;
            const cards = obyekContainer.querySelectorAll('[data-obyek-item]');
            cards.forEach((card, idx) => applyIndexToCard(card, idx));
            updatePrimarySync();
            updateObyekRemoveState();
        };

        const bindLegalitasSection = (card) => {
            const container = card.querySelector('[data-legalitas-container]');
            const addBtn = card.querySelector('[data-legalitas-add]');
            if (!container || !addBtn || !legalitasTemplate) {
                return;
            }
            const minLegal = parseInt(container.dataset.legalitasMin || '1', 10);

            const updateLegalitasRemove = () => {
                const rows = container.querySelectorAll('[data-legalitas-item]');
                rows.forEach((row) => {
                    const removeBtn = row.querySelector('[data-legalitas-remove]');
                    if (removeBtn) {
                        removeBtn.disabled = rows.length <= minLegal;
                    }
                });
            };

            const bindLegalitasRow = (row) => {
                const removeBtn = row.querySelector('[data-legalitas-remove]');
                if (!removeBtn) return;
                removeBtn.addEventListener('click', () => {
                    const rows = container.querySelectorAll('[data-legalitas-item]');
                    if (rows.length <= minLegal) {
                        return;
                    }
                    row.remove();
                    reindexObyekCards();
                });
            };

            container.querySelectorAll('[data-legalitas-item]').forEach(bindLegalitasRow);
            updateLegalitasRemove();

            addBtn.addEventListener('click', (event) => {
                event.preventDefault();
                const fragment = legalitasTemplate.content.cloneNode(true);
                const row = fragment.querySelector('[data-legalitas-item]');
                if (!row) return;
                container.appendChild(row);
                bindLegalitasRow(row);
                reindexObyekCards();
            });
        };

        const bindObyekCard = (card) => {
            bindLegalitasSection(card);
            const removeBtn = card.querySelector('[data-obyek-remove]');
            if (removeBtn) {
                removeBtn.addEventListener('click', (event) => {
                    event.preventDefault();
                    const total = obyekContainer.querySelectorAll('[data-obyek-item]').length;
                    if (total <= minObyek) {
                        return;
                    }
                    card.remove();
                    reindexObyekCards();
                });
            }
        };

        const addNewObyekCard = () => {
            if (!obyekContainer || !obyekTemplate) return;
            const fragment = obyekTemplate.content.cloneNode(true);
            const newCard = fragment.querySelector('[data-obyek-item]');
            if (!newCard) return;
            obyekContainer.appendChild(newCard);
            bindObyekCard(newCard);
            reindexObyekCards();
        };

        if (obyekContainer) {
            obyekContainer.querySelectorAll('[data-obyek-item]').forEach(bindObyekCard);
            reindexObyekCards();
        }

        addObyekBtn?.addEventListener('click', (event) => {
            event.preventDefault();
            addNewObyekCard();
        });

        const mapping = [
            { from: 'kepada_nama', to: 'nasabah_nama' },
            { from: 'kepada_nama', to: 'obyek_penilaian_debitur' },
            { from: 'kepada_kode_pos', to: 'nasabah_kode_pos' },
            { from: 'kepada_kode_pos', to: 'obyek_penilaian_kode_pos' },
            { from: 'kepada_kab_kota_id', to: 'nasabah_kab_kota_id' },
            { from: 'kepada_provinsi_id', to: 'nasabah_provinsi_id' },
        ];

        const alamatFields = {
            alamat: document.getElementById('kepada_alamat_pemberi_tugas'),
            desa: document.getElementById('kepada_desa_dan_kecamatan'),
        };

        const nasabahAlamat = document.getElementById('nasabah_alamat');

        mapping.forEach(pair => {
            const source = document.getElementById(pair.from);
            const target = document.getElementById(pair.to);
            if (source && target) {
                const sync = () => {
                    if (!target.dataset.manual || target.dataset.manual === 'false') {
                        target.value = source.value;
                    }
                };
                source.addEventListener('input', sync);
                sync();
                target.addEventListener('input', () => target.dataset.manual = 'true');
            }
        });

        const obyekLokasi = document.getElementById('obyek_penilaian_lokasi');

        if (nasabahAlamat && alamatFields.alamat) {
            const buildAlamat = () => {
                if (nasabahAlamat.dataset.manual === 'true') {
                    return;
                }
                const parts = [alamatFields.alamat.value, alamatFields.desa?.value].filter(Boolean);
                nasabahAlamat.value = parts.join(', ');
                if (obyekLokasi && obyekLokasi.dataset.manual !== 'true') {
                    obyekLokasi.value = nasabahAlamat.value;
                }
            };

            [alamatFields.alamat, alamatFields.desa].forEach(field => {
                field?.addEventListener('input', buildAlamat);
            });

            buildAlamat();
            nasabahAlamat.addEventListener('input', () => nasabahAlamat.dataset.manual = 'true');
        }

        if (obyekLokasi) {
            const syncLokasi = () => {
                if (obyekLokasi.dataset.manual === 'true') return;
                obyekLokasi.value = nasabahAlamat?.value || obyekLokasi.value;
            };

            nasabahAlamat?.addEventListener('input', syncLokasi);
            syncLokasi();
            obyekLokasi.addEventListener('input', () => obyekLokasi.dataset.manual = 'true');
        }

        const ptSelect = document.getElementById('pengguna_laporan_pt_id');
        const penggunaAlamat = document.getElementById('pengguna_laporan_alamat');
        const penggunaKab = document.getElementById('pengguna_laporan_kab_kota');
        const penggunaProv = document.getElementById('pengguna_laporan_provinsi');
        const penggunaKodePos = document.getElementById('pengguna_laporan_kode_pos');

        if (ptSelect) {
            const fillFromPt = () => {
                const option = ptSelect.options[ptSelect.selectedIndex];
                if (!option) return;
                penggunaAlamat && (penggunaAlamat.value = option.dataset.alamat || '');
                penggunaKab && (penggunaKab.value = option.dataset.kab || '');
                penggunaProv && (penggunaProv.value = option.dataset.provinsi || '');
                penggunaKodePos && (penggunaKodePos.value = option.dataset.kodepos || '');
            };

            ptSelect.addEventListener('change', fillFromPt);
            fillFromPt();
        }
    });
</script>
@endpush
