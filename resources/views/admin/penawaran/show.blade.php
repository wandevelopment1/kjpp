@extends('layout.admin.layout')

@php
    $title = $title ?? 'Detail Penawaran';
    $subTitle = $subTitle ?? ($penawaran->kepada_no_spk ?? 'Detail');
@endphp

@section('title', $title)

@section('content')
<div class="card radius-12 p-20 mb-20">
    <div class="d-flex justify-content-between flex-wrap gap-3 align-items-start mb-4">
        <div>
            <h4 class="mb-1">{{ $penawaran->kepada_no_spk ?? 'No. SPK tidak tersedia' }}</h4>
            <p class="mb-0 ">Dibuat oleh {{ $penawaran->owner->name ?? 'Tidak diketahui' }} &bull;
                {{ optional($penawaran->created_at)->diffForHumans() }}</p>
        </div>
        <div class="text-end">
            @php
                $statusLabel = match ($penawaran->status) {
                    'acc_1' => 'Project Berjalan',
                    'acc_2' => 'Final',
                    default => 'Draft 1',
                };
                $statusClass = match ($penawaran->status) {
                    'acc_2' => 'bg-primary-subtle text-primary-600',
                    'acc_1' => 'bg-success-subtle text-success-600',
                    default => 'bg-warning-subtle text-warning-600',
                };
            @endphp
            <span class="badge {{ $statusClass }} px-16 py-8">{{ $statusLabel }}</span>
            <div class=" text-sm mt-1">
                @if($penawaran->approved_at)
                Disetujui {{ optional($penawaran->approved_at)->format('d M Y H:i') }}
                @else
                Belum disetujui
                @endif
            </div>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-3 col-sm-6">
            <div class="border rounded-12 p-16 h-100">
                <p class="text-uppercase text-xs  mb-1">No. Lingkup</p>
                <h6 class="mb-0">{{ $penawaran->kepada_no_lingkup ?? '-' }}</h6>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="border rounded-12 p-16 h-100">
                <p class="text-uppercase text-xs  mb-1">Tanggal Lingkup</p>
                <h6 class="mb-0">{{ $penawaran->kepada_tgl_lingkup ? \Carbon\Carbon::parse($penawaran->kepada_tgl_lingkup)->format('d M Y') : '-' }}</h6>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="border rounded-12 p-16 h-100">
                <p class="text-uppercase text-xs  mb-1">Tanggal SPK</p>
                <h6 class="mb-0">{{ $penawaran->kepada_tgl_spk ? \Carbon\Carbon::parse($penawaran->kepada_tgl_spk)->format('d M Y') : '-' }}</h6>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="border rounded-12 p-16 h-100">
                <p class="text-uppercase text-xs  mb-1">Status Dokumen</p>
                <h6 class="mb-0">{{ $statusLabel }}</h6>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <h6 class="text-uppercase text-xs  mb-0">Kepada (Penerbit SPK)</h6>
                    <iconify-icon icon="mdi:briefcase-outline" class="text-body-tertiary"></iconify-icon>
                </div>
                <dl class="row mb-0">
                    <dt class="col-5">No. SPK</dt>
                    <dd class="col-7">{{ $penawaran->kepada_no_spk ?? '-' }}</dd>
                    <dt class="col-5">No. Lingkup</dt>
                    <dd class="col-7">{{ $penawaran->kepada_no_lingkup ?? '-' }}</dd>
                    <dt class="col-5">Tgl Lingkup</dt>
                    <dd class="col-7">{{ $penawaran->kepada_tgl_lingkup ? \Carbon\Carbon::parse($penawaran->kepada_tgl_lingkup)->format('d M Y') : '-' }}</dd>
                    <dt class="col-5">Tgl SPK</dt>
                    <dd class="col-7">{{ $penawaran->kepada_tgl_spk ? \Carbon\Carbon::parse($penawaran->kepada_tgl_spk)->format('d M Y') : '-' }}</dd>
                    <dt class="col-5">PT</dt>
                    <dd class="col-7">{{ $penawaran->kepada_pt ?? '-' }}</dd>
                    <dt class="col-5">Nama</dt>
                    <dd class="col-7">{{ $penawaran->kepada_nama ?? '-' }}</dd>
                    <dt class="col-5">Jabatan</dt>
                    <dd class="col-7">{{ $penawaran->kepada_jabatan ?? '-' }}</dd>
                    <dt class="col-5">Alamat</dt>
                    <dd class="col-7">{{ $penawaran->kepada_alamat_pemberi_tugas ?? '-' }}</dd>
                    <dt class="col-5">Kab./Kota</dt>
                    <dd class="col-7">{{ $penawaran->kepadaKabKota->name ?? '-' }}</dd>
                    <dt class="col-5">Provinsi</dt>
                    <dd class="col-7">{{ $penawaran->kepadaProvinsi->name ?? '-' }}</dd>
                    <dt class="col-5">Kode Pos</dt>
                    <dd class="col-7">{{ $penawaran->kepada_kode_pos ?? '-' }}</dd>
                    <dt class="col-5">Email</dt>
                    <dd class="col-7">{{ $penawaran->kepada_email ?? '-' }}</dd>
                </dl>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <h6 class="text-uppercase text-xs  mb-0">Nasabah / Klien</h6>
                    <iconify-icon icon="mdi:account-circle-outline" class="text-body-tertiary"></iconify-icon>
                </div>
                <dl class="row mb-0">
                    <dt class="col-5">Nama</dt>
                    <dd class="col-7">{{ $penawaran->nasabah_nama ?? '-' }}</dd>
                    <dt class="col-5">Alamat</dt>
                    <dd class="col-7">{{ $penawaran->nasabah_alamat ?? '-' }}</dd>
                    <dt class="col-5">Kab./Kota</dt>
                    <dd class="col-7">{{ $penawaran->nasabahKabKota->name ?? '-' }}</dd>
                    <dt class="col-5">Provinsi</dt>
                    <dd class="col-7">{{ $penawaran->nasabahProvinsi->name ?? '-' }}</dd>
                    <dt class="col-5">Kode Pos</dt>
                    <dd class="col-7">{{ $penawaran->nasabah_kode_pos ?? '-' }}</dd>
                    <dt class="col-5">NPWP</dt>
                    <dd class="col-7">{{ $penawaran->nasabah_npwp ?? '-' }}</dd>
                    <dt class="col-5">Go Publik</dt>
                    <dd class="col-7">{{ $penawaran->nasabah_go_publik ? 'Ya' : 'Tidak' }}</dd>
                    <dt class="col-5">Bidang Usaha</dt>
                    <dd class="col-7">{{ $penawaran->bidangUsaha->name ?? '-' }}</dd>
                </dl>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <h6 class="text-uppercase text-xs  mb-0">Penilaian</h6>
                    <iconify-icon icon="mdi:clipboard-list-outline" class="text-body-tertiary"></iconify-icon>
                </div>
                <dl class="row mb-0">
                    <dt class="col-5">Tujuan</dt>
                    <dd class="col-7">{{ $penawaran->penilaianTujuan->name ?? '-' }}</dd>
                    <dt class="col-5">Jenis Laporan</dt>
                    <dd class="col-7">{{ $penawaran->penilaianJenisLaporan->name ?? '-' }}</dd>
                    <dt class="col-5">Jenis Jasa</dt>
                    <dd class="col-7">{{ $penawaran->penilaianJenisJasa->name ?? '-' }}</dd>
                    <dt class="col-5">Tipe Properti</dt>
                    <dd class="col-7">{{ $penawaran->penilaianTipeProperti->name ?? '-' }}</dd>
                    <dt class="col-5">Biaya Jasa</dt>
                    <dd class="col-7">{{ $penawaran->penilaian_biaya_jasa ? 'Rp ' . number_format($penawaran->penilaian_biaya_jasa, 0, ',', '.') : '-' }}</dd>
                    <dt class="col-5">Transport/Akomodasi</dt>
                    <dd class="col-7">{{ $penawaran->penilaian_transport_akomodasi ? 'Rp ' . number_format($penawaran->penilaian_transport_akomodasi, 0, ',', '.') : '-' }}</dd>
                    <dt class="col-5">PPN</dt>
                    <dd class="col-7">{{ $penawaran->penilaian_ppn_included ? 'Sudah termasuk' : 'Belum termasuk' }}</dd>
                    <dt class="col-5">Split Pembayaran</dt>
                    <dd class="col-7">{{ $penawaran->penilaian_pembayaran_split ? 'Ya' : 'Tidak' }}</dd>
                </dl>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <h6 class="text-uppercase text-xs mb-0">Obyek Penilaian</h6>
                    <iconify-icon icon="mdi:home-city-outline" class="text-body-tertiary"></iconify-icon>
                </div>
                @if(!empty($obyekItems))
                    <div class="d-flex flex-column gap-3">
                        @foreach($obyekItems as $item)
                            <div class="border rounded-12 p-16">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <div>
                                        <p class="text-uppercase text-xs text-muted mb-1">Obyek #{{ $item['order'] ?? $loop->iteration }}</p>
                                        <h6 class="mb-0">{{ $item['obyek_name'] ?? '-' }}</h6>
                                    </div>
                                    @if(!empty($item['legalitas_items']))
                                        <span class="badge bg-secondary-subtle text-secondary-700">
                                            {{ count($item['legalitas_items']) }} legalitas
                                        </span>
                                    @endif
                                </div>
                                <dl class="row mb-0 small">
                                    <dt class="col-5">Debitur</dt>
                                    <dd class="col-7">{{ $item['debitur'] ?? '-' }}</dd>
                                    <dt class="col-5">Lokasi</dt>
                                    <dd class="col-7">{{ $item['lokasi'] ?? '-' }}</dd>
                                    <dt class="col-5">Kepemilikan</dt>
                                    <dd class="col-7">{{ $item['kepemilikan_name'] ?? '-' }}</dd>
                                    <dt class="col-5">Kab./Kota</dt>
                                    <dd class="col-7">{{ $item['kab_name'] ?? '-' }}</dd>
                                    <dt class="col-5">Provinsi</dt>
                                    <dd class="col-7">{{ $item['prov_name'] ?? '-' }}</dd>
                                    <dt class="col-5">Kode Pos</dt>
                                    <dd class="col-7">{{ $item['kode_pos'] ?? '-' }}</dd>
                                    <dt class="col-5">Luas Tanah</dt>
                                    <dd class="col-7">{{ $item['luas_tanah'] ?? '-' }}</dd>
                                    <dt class="col-5">IMB</dt>
                                    <dd class="col-7">{{ $item['imb'] ?? '-' }}</dd>
                                    <dt class="col-5">Luas Bangunan</dt>
                                    <dd class="col-7">{{ $item['luas_bangunan'] ?? '-' }}</dd>
                                    <dt class="col-5">Tipe Properti</dt>
                                    <dd class="col-7">{{ $item['tipe_properti_name'] ?? '-' }}</dd>
                                </dl>
                                <div class="mt-3">
                                    <p class="text-xs text-muted mb-1">Legalitas</p>
                                    @if(!empty($item['legalitas_items']))
                                        <ul class="mb-0 ps-3">
                                            @foreach($item['legalitas_items'] as $legalitas)
                                                <li>{{ $legalitas }}</li>
                                            @endforeach
                                        </ul>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-muted mb-0">Belum ada data obyek penilaian.</p>
                @endif
                </div>
            </div>
        </div>
    </div>
</div>
<div class="card radius-12 p-20">
    @php
        $pjRoles = [
            [
                'label' => 'Perusahaan',
                'name' => optional($penawaran->penanggungJawabCompany)->name,
                'numbers' => [],
            ],
            [
                'label' => 'Penanggung Penilai',
                'name' => optional($penawaran->penanggungJawabPenanggungPenilai)->name,
                'numbers' => [
                    'No. MAPPI' => optional($penawaran->penanggungJawabPenanggungPenilai)->no_mappi,
                    'No. Izin Penilai' => optional($penawaran->penanggungJawabPenanggungPenilai)->no_izin_penilai,
                    'No. RMK' => optional($penawaran->penanggungJawabPenanggungPenilai)->no_rmk,
                ],
            ],
            [
                'label' => 'Penilai',
                'name' => optional($penawaran->penanggungJawabPenilai)->name,
                'numbers' => [
                    'No. MAPPI' => optional($penawaran->penanggungJawabPenilai)->no_mappi,
                    'No. Izin Penilai' => optional($penawaran->penanggungJawabPenilai)->no_izin_penilai,
                    'No. RMK' => optional($penawaran->penanggungJawabPenilai)->no_rmk,
                ],
            ],
            [
                'label' => 'Reviewer',
                'name' => optional($penawaran->penanggungJawabReviewer)->name,
                'numbers' => [
                    'No. MAPPI' => optional($penawaran->penanggungJawabReviewer)->no_mappi,
                    'No. Izin Penilai' => optional($penawaran->penanggungJawabReviewer)->no_izin_penilai,
                    'No. RMK' => optional($penawaran->penanggungJawabReviewer)->no_rmk,
                ],
            ],
            [
                'label' => 'Inspeksi',
                'name' => optional($penawaran->penanggungJawabInspeksi)->name,
                'numbers' => [
                    'No. MAPPI' => optional($penawaran->penanggungJawabInspeksi)->no_mappi,
                    'No. Izin Penilai' => optional($penawaran->penanggungJawabInspeksi)->no_izin_penilai,
                    'No. RMK' => optional($penawaran->penanggungJawabInspeksi)->no_rmk,
                ],
            ],
        ];
    @endphp
    <div class="row g-4">
        <div class="col-lg-6">
            <h5 class="mb-12">Penanggung Jawab</h5>
            <div class="row g-3">
                @foreach($pjRoles as $role)
                <div class="col-sm-6">
                    <div class="border rounded-12 p-16 h-100">
                        <p class="text-uppercase text-xs  mb-1">{{ $role['label'] }}</p>
                        <h6 class="mb-2">{{ $role['name'] ?? '-' }}</h6>
                        @if(!empty(array_filter($role['numbers'])))
                            <dl class="row g-1 mb-0 small">
                                @foreach($role['numbers'] as $label => $value)
                                    <dt class="col-7 ">{{ $label }}</dt>
                                    <dd class="col-5">{{ $value ?? '-' }}</dd>
                                @endforeach
                            </dl>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <h5 class="mb-3">Pengguna Laporan</h5>
                    <dl class="row mb-0">
                        <dt class="col-5">PT</dt>
                        <dd class="col-7">{{ $penawaran->penggunaLaporanPt->name ?? '-' }}</dd>
                        <dt class="col-5">Nama</dt>
                        <dd class="col-7">{{ $penawaran->penggunaLaporanNama->name ?? '-' }}</dd>
                        <dt class="col-5">Jenis Pengguna</dt>
                        <dd class="col-7">{{ $penawaran->penggunaLaporanJenisPengguna->name ?? '-' }}</dd>
                        <dt class="col-5">Jenis Industri</dt>
                        <dd class="col-7">{{ $penawaran->penggunaLaporanJenisIndustri->name ?? '-' }}</dd>
                        <dt class="col-5">Alamat</dt>
                        <dd class="col-7">
                            <div class="fw-semibold">{{ $penawaran->pengguna_laporan_alamat ?? '-' }}</div>
                            <div class=" text-xs mt-1">
                                {{ $penawaran->pengguna_laporan_kab_kota ?? '-' }}, {{ $penawaran->pengguna_laporan_provinsi ?? '-' }} {{ $penawaran->pengguna_laporan_kode_pos ?? '' }}
                            </div>
                        </dd>
                    </dl>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card radius-12 p-20 mb-20">
    <div class="d-flex justify-content-between flex-wrap gap-3 align-items-center mb-16">
        <div>
            <h5 class="mb-4">Template PDF Manual</h5>
            <p class="text mb-0">{{ $manualTemplateDescription }}</p>
        </div>
        <div class="text small">
            Format wajib: <strong>PDF</strong>, maksimal 5 MB.
        </div>
    </div>

    <div class="row g-4">
        @foreach ($templateGroups as $slug => $label)
        @php
            $uploaded = $uploadedTemplates->get($slug);
            $isDisabled = in_array($slug, $disabledTemplateGroups ?? []);
        @endphp
        <div class="col-lg-4 col-md-6">
            <div class="border rounded-12 p-16 h-100">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <h6 class="mb-1">{{ $label }}</h6>
                    </div>
                    @if ($uploaded)
                        <span class="badge bg-success-subtle text-success-600">Sudah diunggah</span>
                    @else
                        <span class="badge bg-secondary-subtle text-secondary-600">Belum ada</span>
                    @endif
                </div>
                @if (!$isDisabled)
                <form action="{{ route('admin.penawaran.template.upload', [$penawaran->id, $slug]) }}" method="POST" enctype="multipart/form-data" class="mb-12">
                    @csrf
                    <input type="file" name="template_file" accept="application/pdf" class="form-control form-control-sm mb-2" required>
                    <button type="submit" class="btn btn-sm btn-primary w-100">Unggah {{ $label }}</button>
                </form>
                @endif
                <div class="d-flex flex-column gap-2">
                    @if ($isDisabled)
                        <button type="button" class="btn btn-outline-secondary btn-sm" disabled>
                            <iconify-icon icon="solar:download-linear" class="icon"></iconify-icon>
                            Generate dinonaktifkan
                        </button>
                    @else
                    <a href="{{ route('admin.penawaran.export-template', [$penawaran->id, $slug]) }}" class="btn btn-outline-primary btn-sm" target="_blank">
                        <iconify-icon icon="solar:download-linear" class="icon"></iconify-icon>
                        Generate PDF
                    </a>
                    @endif
                    @if ($uploaded)
                        <a href="{{ route('admin.penawaran.template.view', [$penawaran->id, $slug]) }}" class="btn btn-outline-success btn-sm" target="_blank">
                            <iconify-icon icon="solar:document-linear" class="icon"></iconify-icon>
                            Lihat PDF Terunggah
                        </a>
                        <div class="text text-xs">
                            {{ $uploaded->original_name ?? 'File PDF' }}<br>
                            Diunggah {{ optional($uploaded->updated_at)->diffForHumans() }} oleh {{ $uploaded->uploader->name ?? 'Sistem' }}
                        </div>
                    @else
                        <div class="text text-xs">Belum ada file terunggah untuk template ini.</div>
                    @endif
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>

@if (!empty($finalInvoiceHtml))
<div class="card radius-12 p-20 mb-20">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-16">
        <div>
            <h5 class="mb-1">Invoice Akhir</h5>
            <p class="text mb-0">Konten diambil dari konfigurasi UI group <strong>Invoice</strong>.</p>
        </div>
        <div class="d-flex align-items-center gap-2">
            <span class="badge bg-primary-subtle text-primary-600">Status Final</span>
            <button type="button" id="printInvoiceBtn" class="btn btn-sm btn-primary d-flex align-items-center gap-2">
                <iconify-icon icon="solar:printer-minimalistic-outline" class="icon"></iconify-icon>
                Cetak / Simpan PDF
            </button>
        </div>
    </div>
    <div class="ckeditor-content" id="invoicePreview">
        {!! $finalInvoiceHtml !!}
    </div>
</div>
@endif
    <div class="mt-24 d-flex flex-wrap gap-3 align-items-center">
        <a href="{{ route('admin.penawaran.index') }}" class="btn btn-light">Kembali</a>
        @can('admin.penawaran.edit')
        <a href="{{ route('admin.penawaran.edit', $penawaran->id) }}" class="btn btn-primary">Edit Penawaran</a>
        @endcan
        <div class="ms-auto text small">Gunakan tombol di atas untuk unggah & generate template PDF.</div>
    </div>

@push('script')
@if (!empty($finalInvoiceHtml))
<script>
    document.getElementById('printInvoiceBtn')?.addEventListener('click', function () {
        const invoiceHtml = document.getElementById('invoicePreview').innerHTML;
        const win = window.open('', '_blank', 'width=1024,height=768');
        const collectedStyles = Array
            .from(document.querySelectorAll('link[rel="stylesheet"], style'))
            .map(node => node.outerHTML)
            .join('');

        win.document.write('<!DOCTYPE html><html lang="en"><head><meta charset="UTF-8"><title></title>');
        win.document.write(collectedStyles);
        win.document.write('<style>@page{margin:1cm;} body{font-family:"Times New Roman",serif;color:#1f2937;} .ckeditor-content{line-height:1.6;font-size:14px;} table{border-collapse:collapse;} table,th,td{border:1px solid #d1d5db;} th,td{padding:8px;text-align:left;} img{max-width:100%;height:auto;}</style>');
        win.document.write('</head><body><div class="ckeditor-content" id="print-root"></div></body></html>');
        win.document.close();

        win.onload = function () {
            win.document.getElementById('print-root').innerHTML = invoiceHtml;
            win.focus();
            win.print();
            win.onafterprint = function () {
                win.close();
            };
        };
    });
</script>
@endif
@endpush

@endsection
