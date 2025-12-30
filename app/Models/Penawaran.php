<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\PenanggungJawab\Company;
use App\Models\User;
use App\Models\PenanggungJawab\PenanggungPenilai;
use App\Models\PenanggungJawab\Penilai;
use App\Models\PenanggungJawab\Reviewer;
use App\Models\PenanggungJawab\Inspeksi;
use App\Models\PenggunaLaporan\Pt;
use App\Models\PenggunaLaporan\Nama;
use App\Models\PenggunaLaporan\JenisPengguna;
use App\Models\PenggunaLaporan\JenisIndustri;
use App\Models\PenawaranTemplateFile;

class Penawaran extends Model
{
    use HasFactory;

    protected $fillable = [
        'penanggung_jawab_company_id',
        'penanggung_jawab_penanggung_penilai_id',
        'penanggung_jawab_penilai_id',
        'penanggung_jawab_reviewer_id',
        'penanggung_jawab_inspeksi_id',
        'pengguna_laporan_pt_id',
        'pengguna_laporan_nama_id',
        'pengguna_laporan_jenis_pengguna_id',
        'pengguna_laporan_jenis_industri_id',
        'pengguna_laporan_alamat',
        'pengguna_laporan_kab_kota',
        'pengguna_laporan_provinsi',
        'pengguna_laporan_kode_pos',
        'kepada_no_spk',
        'kepada_no_lingkup',
        'kepada_tgl_lingkup',
        'kepada_tgl_spk',
        'kepada_pt',
        'kepada_nama',
        'kepada_jabatan',
        'kepada_alamat_pemberi_tugas',
        'kepada_desa_dan_kecamatan',
        'kepada_kab_kota_id',
        'kepada_provinsi_id',
        'kepada_kode_pos',
        'kepada_email',
        'laporan_nomor',
        'laporan_tanggal',
        'nasabah_nama',
        'nasabah_alamat',
        'nasabah_kab_kota_id',
        'nasabah_provinsi_id',
        'nasabah_kode_pos',
        'nasabah_npwp',
        'nasabah_go_publik',
        'status_kepemilikan_id',
        'bidang_usaha_id',
        'nasabah_telepon',
        'nasabah_email',
        'status',
        'user_id',
        'owner_role_id',
        'approved_at',
        'penilaian_tujuan_id',
        'penilaian_jenis_laporan_id',
        'penilaian_jangka_waktu',
        'penilaian_nilai_id',
        'penilaian_jumlah_buku',
        'penilaian_jenis_jasa_id',
        'penilaian_tipe_properti_id',
        'penilaian_biaya_jasa',
        'penilaian_transport_akomodasi',
        'penilaian_ppn_included',
        'penilaian_rekening_pembayaran',
        'penilaian_pembayaran_option',
        'penilaian_pendekatan_penilaian_id',
        'penilaian_metode_penilaian_id',
        'obyek_penilaian_obyek_id',
        'obyek_penilaian_debitur',
        'obyek_penilaian_legalitas',
        'obyek_penilaian_legalitas_items',
        'obyek_penilaian_lokasi',
        'obyek_penilaian_kepemilikan_id',
        'obyek_penilaian_kab_kota_id',
        'obyek_penilaian_provinsi_id',
        'obyek_penilaian_kode_pos',
        'obyek_penilaian_luas_tanah',
        'obyek_penilaian_imb',
        'obyek_penilaian_luas_bangunan',
        'obyek_penilaian_tipe_properti_id',
        'obyek_penilaian_obyek_ids',
        'obyek_penilaian_items',
    ];

    protected $casts = [
        'obyek_penilaian_obyek_ids' => 'array',
        'obyek_penilaian_legalitas_items' => 'array',
        'obyek_penilaian_items' => 'array',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class, 'penanggung_jawab_company_id');
    }

    public function penanggungJawabCompany()
    {
        return $this->company();
    }

    public function penanggungPenilai()
    {
        return $this->belongsTo(PenanggungPenilai::class, 'penanggung_jawab_penanggung_penilai_id');
    }

    public function penanggungJawabPenanggungPenilai()
    {
        return $this->penanggungPenilai();
    }

    public function penilai()
    {
        return $this->belongsTo(Penilai::class, 'penanggung_jawab_penilai_id');
    }

    public function penanggungJawabPenilai()
    {
        return $this->penilai();
    }

    public function reviewer()
    {
        return $this->belongsTo(Reviewer::class, 'penanggung_jawab_reviewer_id');
    }

    public function penanggungJawabReviewer()
    {
        return $this->reviewer();
    }

    public function inspeksi()
    {
        return $this->belongsTo(Inspeksi::class, 'penanggung_jawab_inspeksi_id');
    }

    public function penanggungJawabInspeksi()
    {
        return $this->inspeksi();
    }

    public function penggunaPt()
    {
        return $this->belongsTo(Pt::class, 'pengguna_laporan_pt_id');
    }

    public function penggunaLaporanPt()
    {
        return $this->penggunaPt();
    }

    public function penggunaNama()
    {
        return $this->belongsTo(Nama::class, 'pengguna_laporan_nama_id');
    }

    public function penggunaLaporanNama()
    {
        return $this->penggunaNama();
    }

    public function penggunaJenis()
    {
        return $this->belongsTo(JenisPengguna::class, 'pengguna_laporan_jenis_pengguna_id');
    }

    public function penggunaLaporanJenisPengguna()
    {
        return $this->penggunaJenis();
    }

    public function penggunaIndustri()
    {
        return $this->belongsTo(JenisIndustri::class, 'pengguna_laporan_jenis_industri_id');
    }

    public function penggunaLaporanJenisIndustri()
    {
        return $this->penggunaIndustri();
    }

    public function kepadaKabKota()
    {
        return $this->belongsTo(KepadaKabKota::class, 'kepada_kab_kota_id');
    }

    public function kepadaProvinsi()
    {
        return $this->belongsTo(KepadaProvinsi::class, 'kepada_provinsi_id');
    }

    public function nasabahKabKota()
    {
        return $this->belongsTo(KepadaKabKota::class, 'nasabah_kab_kota_id');
    }

    public function nasabahProvinsi()
    {
        return $this->belongsTo(KepadaProvinsi::class, 'nasabah_provinsi_id');
    }

    public function statusKepemilikan()
    {
        return $this->belongsTo(StatusKepemilikan::class);
    }

    public function bidangUsaha()
    {
        return $this->belongsTo(BidangUsaha::class);
    }

    public function penilaianTujuan()
    {
        return $this->belongsTo(Tujuan::class, 'penilaian_tujuan_id');
    }

    public function penilaianJenisLaporan()
    {
        return $this->belongsTo(JenisLaporan::class, 'penilaian_jenis_laporan_id');
    }

    public function penilaianNilai()
    {
        return $this->belongsTo(Nilai::class, 'penilaian_nilai_id');
    }

    public function templateFiles()
    {
        return $this->hasMany(PenawaranTemplateFile::class);
    }

    public function penilaianJenisJasa()
    {
        return $this->belongsTo(JenisJasa::class, 'penilaian_jenis_jasa_id');
    }

    public function penilaianTipeProperti()
    {
        return $this->belongsTo(TipeProperti::class, 'penilaian_tipe_properti_id');
    }

    public function penilaianPendekatan()
    {
        return $this->belongsTo(PendekatanPenilaian::class, 'penilaian_pendekatan_penilaian_id');
    }

    public function penilaianMetode()
    {
        return $this->belongsTo(MetodePenilaian::class, 'penilaian_metode_penilaian_id');
    }

    public function obyekPenilaianObyek()
    {
        return $this->belongsTo(Obyek::class, 'obyek_penilaian_obyek_id');
    }

    public function obyekPenilaianKepemilikan()
    {
        return $this->belongsTo(Kepemilikan::class, 'obyek_penilaian_kepemilikan_id');
    }

    public function obyekPenilaianKabKota()
    {
        return $this->belongsTo(KepadaKabKota::class, 'obyek_penilaian_kab_kota_id');
    }

    public function obyekPenilaianProvinsi()
    {
        return $this->belongsTo(KepadaProvinsi::class, 'obyek_penilaian_provinsi_id');
    }

    public function obyekPenilaianTipeProperti()
    {
        return $this->belongsTo(TipeProperti::class, 'obyek_penilaian_tipe_properti_id');
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
