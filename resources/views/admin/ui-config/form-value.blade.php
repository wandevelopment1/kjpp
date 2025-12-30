@extends('layout.admin.layout')

@php
use App\Support\PenawaranPlaceholderCatalog;
use App\Support\PenawaranPlaceholderResolver;
use App\Models\Penawaran;

$resource = 'uiConfig';
$routeBase = 'admin.ui-config'; // hasil: config-group
$model = isset($$resource) ? $$resource : null;
$title = $model ? 'Ubah' : 'Tambah';
$subTitle = $title;

$script = '<script>
    const fileInput = document.getElementById("upload-file");
        const imagePreview = document.getElementById("uploaded-img__preview");
        const uploadedImgContainer = document.querySelector(".uploaded-img");
        
        fileInput.addEventListener("change", (e) => {
            if (e.target.files.length) {
                const src = URL.createObjectURL(e.target.files[0]);
                imagePreview.src = src;
                uploadedImgContainer.classList.remove("d-none");
            }
        });
        
</script>';


$samplePenawaran = Penawaran::with([
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
])->find(1);

$sampleValues = $samplePenawaran
    ? PenawaranPlaceholderResolver::map($samplePenawaran)
    : [];

$placeholderSections = collect(PenawaranPlaceholderCatalog::sections() ?? [])
    ->map(function ($section) use ($sampleValues) {
        $rawItems = $section['placeholders'] ?? $section['items'] ?? [];

        $placeholders = collect($rawItems)
            ->reject(function ($item) {
                $rawToken = $item['token'] ?? $item['key'] ?? '';
                $rawToken = trim($rawToken);
                $rawToken = trim($rawToken, '{} ');

                return Str::endsWith($rawToken, '_id');
            })
            ->map(function ($item) use ($sampleValues) {
            $rawToken = $item['token'] ?? $item['key'] ?? '';
            $rawToken = trim($rawToken);

            if ($rawToken !== '') {
                $rawToken = trim($rawToken, '{} ');
            }

            $displayToken = $rawToken !== '' ? '{{' . $rawToken . '}}' : '{{token}}';
            $sample = $rawToken !== '' ? ($sampleValues[$rawToken] ?? '') : '';
            $description = $item['label'] ?? $item['description'] ?? null;

            return [
                'token' => $displayToken,
                'description' => $description,
                'sample' => $sample,
            ];
        })->all();

        return [
            'title' => $section['title'] ?? 'Placeholder',
            'description' => $section['description'] ?? null,
            'placeholders' => $placeholders,
        ];
    })
    ->filter(fn ($section) => !empty($section['placeholders']))
    ->values();
@endphp


@section('content')

<div class="row gy-4">
    <div class="col-lg-12">
        <div class="card mt-24">
            <div class="card-body p-24">
                <form id="form"
                    action="{{ $model ? route($routeBase  . '.update', $model->id) : route($routeBase . '.store') }}"
                    method="POST" enctype="multipart/form-data" class="d-flex flex-column gap-20">
                    
                    @csrf
                    @if($model)
                    @method('PUT')
                    @endif


                    <!-- Type -->
                    <div class="mb-3">
                        @if($model)
                        <input type="hidden" name="type" id="type" value="{{ old('type', $model->type) }}">
                        @endif
                    </div>


                    <!-- Placeholder Info -->
                    @if($model && $model->type === 'ckeditor')
                    <div class="mb-3">
                        <div class="card border-primary">
                            <div class="card-header bg-primary-50 cursor-pointer" data-bs-toggle="collapse" data-bs-target="#placeholderInfo">
                                <h6 class="mb-0 d-flex align-items-center justify-content-between">
                                    <span><iconify-icon icon="solar:info-circle-bold" class="icon text-lg me-2"></iconify-icon>Placeholder yang Tersedia</span>
                                    <iconify-icon icon="iconamoon:arrow-down-2" class="icon"></iconify-icon>
                                </h6>
                            </div>
                            <div class="collapse" id="placeholderInfo">
                                <div class="card-body">
                                    @if($placeholderSections->isNotEmpty())
                                        <div class="d-flex flex-column gap-24">
                                            @foreach($placeholderSections as $section)
                                                <div>
                                                    <div class="d-flex justify-content-between align-items-start flex-wrap gap-2 mb-2">
                                                        <div>
                                                            <h6 class="mb-1">{{ $section['title'] }}</h6>
                                                            @if(!empty($section['description']))
                                                                <p class="text-muted small mb-0">{{ $section['description'] }}</p>
                                                            @endif
                                                        </div>
                                                        <span class="badge bg-primary-subtle text-primary-600">{{ count($section['placeholders']) }} token</span>
                                                    </div>
                                                    <div class="table-responsive">
                                                        <table class="table table-sm table-bordered mb-0">
                                                            <thead class="table-light">
                                                                <tr>
                                                                    <th width="30%">Placeholder</th>
                                                                    <th width="45%">Deskripsi</th>
                                                                    <th width="25%">Contoh Nilai</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @foreach($section['placeholders'] as $placeholder)
                                                                    <tr>
                                                                        <td class="align-middle"><code>{{ $placeholder['token'] }}</code></td>
                                                                        <td class="align-middle">{{ $placeholder['description'] ?? '-' }}</td>
                                                                        <td class="align-middle text-nowrap">{{ $placeholder['sample'] ?: '-' }}</td>
                                                                    </tr>
                                                                @endforeach
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @else
                                        <div class="alert alert-warning mb-0">Belum ada placeholder terdaftar.</div>
                                    @endif
                                    <div class="mt-3 p-3 bg-light rounded">
                                        <h6 class="fw-bold mb-2"><iconify-icon icon="solar:document-text-bold" class="icon me-1"></iconify-icon>Contoh Template CKEditor</h6>
                                        <pre class="mb-0 text-sm"><code>Kepada @{{ nama }},
Alamat: @{{ alamat }}
Nomor SPK: @{{ no_spk }}

Pada tanggal @{{ tanggal }}, kami mengajukan biaya jasa sebesar @{{ biaya_jasa_rupiah }} dengan ketentuan pembayaran split sesuai form: @{{ penilaian_pembayaran_split }}.</code></pre>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif


                    <!-- Value -->
                    <div class="mb-3" id="value-wrapper">
                        <label class="form-label fw-bold text-neutral-900">Value</label>
                        {{-- isi diganti via JS --}}
                    </div>

                    @if (!empty($model->type) && in_array($model->type, ['image','file']) && !empty($model->value))
                    <div class="mt-2">
                        <a href="{{ asset('storage/' . $model->value) }}" target="_blank">
                            @if($model->type === 'image')
                            <img src="{{ asset('storage/' . $model->value) }}" alt="Current Image" style="height: 100px;">
                            @else
                            Download File Saat Ini
                            @endif
                        </a>
                    </div>
                    @endif


                    <!-- Tombol -->
                    <div class="d-flex align-items-center justify-content-center gap-3">
                        <a href="{{ route($routeBase . '.show', $model->group->slug) }}"
                            class="border border-danger-600 bg-hover-danger-200 text-danger-600 text-md px-56 py-11 radius-8">Back</a>
                        <button id="submit" type="submit"
                            class="btn btn-primary border border-primary-600 text-md px-56 py-12 radius-8">
                            {{ $model ? 'Update' : 'Save' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function updateValueField() {
        const type = document.getElementById('type').value;
        const wrapper = document.getElementById('value-wrapper');

        // Hapus field lama (dan destroy CKEditor kalau ada)
        if (CKEDITOR.instances['value']) {
            CKEDITOR.instances['value'].destroy(true);
        }
        wrapper.querySelectorAll('input, textarea').forEach(el => el.remove());

        if (type === 'ckeditor') {
            const textarea = document.createElement('textarea');
            textarea.name = 'value';
            textarea.id = 'value';
            textarea.className = 'form-control';
            textarea.rows = 6;
            textarea.value = @json(old('value', $model->value ?? ''));
            wrapper.appendChild(textarea);

            // Aktifkan CKEditor 4 dengan upload gambar
            setTimeout(() => {
                CKEDITOR.replace('value', {
                    filebrowserUploadUrl: "{{ route('admin.ckeditor.upload', ['_token' => csrf_token()]) }}",
                    filebrowserUploadMethod: 'form'
                });
            }, 50);
        } else if (type === 'text_field') {
            const input = document.createElement('input');
            input.type = 'text';
            input.name = 'value';
            input.id = 'value';
            input.className = 'form-control';
            input.value = @json(old('value', $model->value ?? ''));
            wrapper.appendChild(input);
        } else if (type === 'text_area') {
            const textarea = document.createElement('textarea');
            textarea.name = 'value';
            textarea.id = 'value';
            textarea.className = 'form-control';
            textarea.rows = 4;
            textarea.value = @json(old('value', $model->value ?? ''));
            wrapper.appendChild(textarea);
        } else if (type === 'image' || type === 'file') {
            const input = document.createElement('input');
            input.type = 'file';
            input.name = 'value';
            input.id = 'value';
            input.className = 'form-control';
            if (type === 'image') input.accept = 'image/*';
            wrapper.appendChild(input);
        }
    }

    document.addEventListener('DOMContentLoaded', () => {
        document.getElementById('type').addEventListener('change', updateValueField);
        updateValueField(); // load pertama
    });
</script>


@endsection