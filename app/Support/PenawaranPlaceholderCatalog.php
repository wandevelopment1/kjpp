<?php

namespace App\Support;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class PenawaranPlaceholderCatalog
{
    public static function sections(): array
    {
        return [
            [
                'title' => 'Placeholder Otomatis & Format Khusus',
                'description' => 'Nilai turunan seperti nama prioritas, label status, tanggal lokal, serta format rupiah siap pakai.',
                'items' => self::computedPlaceholders(),
            ],
            [
                'title' => 'Kolom Form Penawaran',
                'description' => 'Semua input yang diisi pada form penawaran dapat dipanggil langsung menggunakan format {{nama_kolom}}.',
                'items' => self::attributePlaceholders(),
            ],
            [
                'title' => 'Relasi Master Data & Penanggung Jawab',
                'description' => 'Gunakan format snake_case + atribut. Contoh: {{pengguna_laporan_pt.name}} untuk nama PT pengguna laporan.',
                'items' => self::relationPlaceholders(),
            ],
        ];
    }

    public static function tokens(): array
    {
        return collect(self::sections())
            ->flatMap(fn ($section) => collect($section['items'])->pluck('token'))
            ->unique()
            ->values()
            ->all();
    }

    private static function computedPlaceholders(): array
    {
        return [
            ['token' => 'nama', 'label' => 'Nama penerima utama (nasabah > kepada > owner)'],
            ['token' => 'alamat', 'label' => 'Alamat utama (nasabah > kepada > pengguna laporan)'],
            ['token' => 'tanggal', 'label' => 'Tanggal generate (format lokal)'],
            ['token' => 'no_spk', 'label' => 'Nomor SPK / kontrak penawaran'],
            ['token' => 'no_lingkup', 'label' => 'Nomor lingkup penawaran'],
            ['token' => 'tgl_lingkup', 'label' => 'Tanggal lingkup penawaran (format lokal)'],
            ['token' => 'status_label', 'label' => 'Label status (Draft 1 / Project Berjalan / Final)'],
            ['token' => 'status', 'label' => 'Status internal mentah (draft_1 / acc_1 / acc_2)'],
            ['token' => 'biaya_jasa_rupiah', 'label' => 'Biaya jasa dengan format Rupiah'],
            ['token' => 'biaya_jasa_ppn_rupiah', 'label' => 'PPN dari biaya jasa (11%)'],
            ['token' => 'biaya_jasa_total_rupiah', 'label' => 'Total biaya jasa + PPN'],
            ['token' => 'transport_rupiah', 'label' => 'Biaya transport/akomodasi format Rupiah'],
            ['token' => 'penilaian_transport_akomodasi_rupiah', 'label' => 'Alias transport/akomodasi format Rupiah'],
            ['token' => 'ppn_status', 'label' => 'Status PPN (Sudah termasuk / Belum termasuk)'],
            ['token' => 'penilaian_ppn_label', 'label' => 'Alias label PPN'],
            ['token' => 'penilaian_pembayaran_split_label', 'label' => 'Label split pembayaran (Ya/Tidak)'],
            ['token' => 'owner_nama', 'label' => 'Nama user internal pembuat penawaran'],
            ['token' => 'owner_email', 'label' => 'Email user internal pembuat penawaran'],
            ['token' => 'obyek_penilaian_obyek_name', 'label' => 'Nama obyek penilaian (master data)'],
            ['token' => 'obyek_penilaian_debitur', 'label' => 'Nama debitur obyek penilaian'],
            ['token' => 'obyek_penilaian_legalitas', 'label' => 'Legalitas obyek penilaian'],
            ['token' => 'obyek_penilaian_lokasi', 'label' => 'Alamat/lokasi obyek penilaian'],
            ['token' => 'obyek_penilaian_kepemilikan_name', 'label' => 'Status kepemilikan obyek penilaian'],
            ['token' => 'obyek_penilaian_kab_kota_name', 'label' => 'Kabupaten/kota obyek penilaian'],
            ['token' => 'obyek_penilaian_provinsi_name', 'label' => 'Provinsi obyek penilaian'],
            ['token' => 'obyek_penilaian_kode_pos', 'label' => 'Kode pos obyek penilaian'],
            ['token' => 'obyek_penilaian_luas_tanah', 'label' => 'Luas tanah obyek penilaian'],
            ['token' => 'obyek_penilaian_imb', 'label' => 'Nomor IMB obyek penilaian'],
            ['token' => 'obyek_penilaian_luas_bangunan', 'label' => 'Luas bangunan obyek penilaian'],
            ['token' => 'obyek_penilaian_tipe_properti_name', 'label' => 'Tipe properti obyek penilaian'],
        ];
    }

    private static function attributePlaceholders(): array
    {
        $hiddenColumns = [
            'obyek_penilaian_debitur',
            'obyek_penilaian_legalitas',
            'obyek_penilaian_lokasi',
            'obyek_penilaian_kode_pos',
            'obyek_penilaian_luas_tanah',
            'obyek_penilaian_imb',
            'obyek_penilaian_luas_bangunan',
        ];

        return collect(Schema::getColumnListing('penawarans'))
            ->filter(fn ($column) => !Str::endsWith($column, '_id'))
            ->reject(fn ($column) => Str::startsWith($column, 'obyek_penilaian_') && in_array($column, $hiddenColumns, true))
            ->map(fn ($column) => [
                'token' => $column,
                'label' => Str::headline(str_replace('_id', ' id', $column)),
            ])
            ->values()
            ->all();
    }

    private static function relationPlaceholders(): array
    {
        $relations = [
            'owner.name' => 'Nama user pembuat penawaran',
            'owner.email' => 'Email user pembuat penawaran',
            'penanggung_jawab_company.name' => 'Nama perusahaan penanggung jawab',
            'penanggung_jawab_penanggung_penilai.name' => 'Nama penanggung penilai',
            'penanggung_jawab_penanggung_penilai.no_mappi' => 'No. MAPPI penanggung penilai',
            'penanggung_jawab_penanggung_penilai.no_izin_penilai' => 'No. Izin Penilai penanggung penilai',
            'penanggung_jawab_penanggung_penilai.no_rmk' => 'No. RMK penanggung penilai',
            'penanggung_jawab_penilai.name' => 'Nama penilai lapangan',
            'penanggung_jawab_penilai.no_mappi' => 'No. MAPPI penilai lapangan',
            'penanggung_jawab_penilai.no_izin_penilai' => 'No. Izin Penilai penilai lapangan',
            'penanggung_jawab_penilai.no_rmk' => 'No. RMK penilai lapangan',
            'penanggung_jawab_reviewer.name' => 'Nama reviewer laporan',
            'penanggung_jawab_reviewer.no_mappi' => 'No. MAPPI reviewer laporan',
            'penanggung_jawab_reviewer.no_izin_penilai' => 'No. Izin Penilai reviewer laporan',
            'penanggung_jawab_reviewer.no_rmk' => 'No. RMK reviewer laporan',
            'penanggung_jawab_inspeksi.name' => 'Nama penanggung jawab inspeksi',
            'penanggung_jawab_inspeksi.no_mappi' => 'No. MAPPI penanggung jawab inspeksi',
            'penanggung_jawab_inspeksi.no_izin_penilai' => 'No. Izin Penilai penanggung jawab inspeksi',
            'penanggung_jawab_inspeksi.no_rmk' => 'No. RMK penanggung jawab inspeksi',
            'pengguna_laporan_pt.name' => 'Nama PT pengguna laporan',
            'pengguna_laporan_nama.name' => 'Nama individu pengguna laporan',
            'pengguna_laporan_jenis_pengguna.name' => 'Jenis pengguna laporan',
            'pengguna_laporan_jenis_industri.name' => 'Jenis industri pengguna laporan',
            'kepada_kab_kota.name' => 'Nama kabupaten/kota pemberi tugas',
            'kepada_provinsi.name' => 'Nama provinsi pemberi tugas',
            'nasabah_kab_kota.name' => 'Nama kabupaten/kota nasabah',
            'nasabah_provinsi.name' => 'Nama provinsi nasabah',
            'status_kepemilikan.name' => 'Status kepemilikan nasabah',
            'bidang_usaha.name' => 'Bidang usaha nasabah',
            'penilaian_tujuan.name' => 'Tujuan penilaian',
            'penilaian_jenis_laporan.name' => 'Jenis laporan penilaian',
            'penilaian_nilai.name' => 'Rentang nilai aset',
            'penilaian_jenis_jasa.name' => 'Jenis jasa penilaian',
            'penilaian_tipe_properti.name' => 'Tipe properti',
            'penilaian_pendekatan_penilaian.name' => 'Pendekatan penilaian',
            'penilaian_metode_penilaian.name' => 'Metode penilaian',
        ];

        return collect($relations)
            ->map(fn ($label, $token) => [
                'token' => $token,
                'label' => $label,
            ])
            ->values()
            ->all();
    }
}
