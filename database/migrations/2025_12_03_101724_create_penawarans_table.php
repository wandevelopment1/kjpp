<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('penawarans', function (Blueprint $table) {
            $table->id();

            // Penanggung Jawab
            $table->foreignId('penanggung_jawab_company_id')->nullable()->constrained('penanggung_jawab_companies')->nullOnDelete();
            $table->foreignId('penanggung_jawab_penanggung_penilai_id')->nullable()->constrained('penanggung_jawab_penanggung_penilai')->nullOnDelete();
            $table->foreignId('penanggung_jawab_penilai_id')->nullable()->constrained('penanggung_jawab_penilai')->nullOnDelete();
            $table->foreignId('penanggung_jawab_reviewer_id')->nullable()->constrained('penanggung_jawab_reviewers')->nullOnDelete();
            $table->foreignId('penanggung_jawab_inspeksi_id')->nullable()->constrained('penanggung_jawab_inspeksi')->nullOnDelete();

            // Pengguna Laporan
            $table->foreignId('pengguna_laporan_pt_id')->nullable()->constrained('pengguna_laporan_pts')->nullOnDelete();
            $table->foreignId('pengguna_laporan_nama_id')->nullable()->constrained('pengguna_laporan_nama')->nullOnDelete();
            $table->foreignId('pengguna_laporan_jenis_pengguna_id')->nullable()->constrained('pengguna_laporan_jenis_pengguna')->nullOnDelete();
            $table->foreignId('pengguna_laporan_jenis_industri_id')->nullable()->constrained('pengguna_laporan_jenis_industri')->nullOnDelete();
            $table->text('pengguna_laporan_alamat')->nullable();
            $table->string('pengguna_laporan_kab_kota')->nullable();
            $table->string('pengguna_laporan_provinsi')->nullable();
            $table->string('pengguna_laporan_kode_pos', 20)->nullable();

            // Kepada (Penerbit SPK)
            $table->string('kepada_no_spk')->nullable();
            $table->date('kepada_tgl_spk')->nullable();
            $table->string('kepada_pt')->nullable();
            $table->string('kepada_nama')->nullable();
            $table->string('kepada_jabatan')->nullable();
            $table->text('kepada_alamat_pemberi_tugas')->nullable();
            $table->string('kepada_desa_dan_kecamatan')->nullable();
            $table->foreignId('kepada_kab_kota_id')->nullable()->constrained('kepada_kab_kotas')->nullOnDelete();
            $table->foreignId('kepada_provinsi_id')->nullable()->constrained('kepada_provinsis')->nullOnDelete();
            $table->string('kepada_kode_pos')->nullable();
            $table->string('kepada_email')->nullable();

            // Nasabah / Klien
            $table->string('nasabah_nama')->nullable();
            $table->text('nasabah_alamat')->nullable();
            $table->foreignId('nasabah_kab_kota_id')->nullable()->constrained('kepada_kab_kotas')->nullOnDelete();
            $table->foreignId('nasabah_provinsi_id')->nullable()->constrained('kepada_provinsis')->nullOnDelete();
            $table->string('nasabah_kode_pos')->nullable();
            $table->string('nasabah_npwp')->nullable();
            $table->boolean('nasabah_go_publik')->default(false);
            $table->foreignId('status_kepemilikan_id')->nullable()->constrained('status_kepemilikans')->nullOnDelete();
            $table->foreignId('bidang_usaha_id')->nullable()->constrained('bidang_usahas')->nullOnDelete();
            $table->string('nasabah_telepon')->nullable();
            $table->string('nasabah_email')->nullable();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->enum('status', ['draft_1', 'acc_1'])->default('draft_1');
            $table->timestamp('approved_at')->nullable();

            // Penilaian
            $table->foreignId('penilaian_tujuan_id')->nullable()->constrained('tujuans')->nullOnDelete();
            $table->foreignId('penilaian_jenis_laporan_id')->nullable()->constrained('jenis_laporans')->nullOnDelete();
            $table->string('penilaian_jangka_waktu')->nullable();
            $table->foreignId('penilaian_nilai_id')->nullable()->constrained('nilais')->nullOnDelete();
            $table->integer('penilaian_jumlah_buku')->nullable();
            $table->foreignId('penilaian_jenis_jasa_id')->nullable()->constrained('jenis_jasas')->nullOnDelete();
            $table->foreignId('penilaian_tipe_properti_id')->nullable()->constrained('tipe_propertis')->nullOnDelete();
            $table->decimal('penilaian_biaya_jasa', 15, 2)->nullable();
            $table->decimal('penilaian_transport_akomodasi', 15, 2)->nullable();
            $table->boolean('penilaian_ppn_included')->default(false);
            $table->string('penilaian_rekening_pembayaran')->nullable();
            $table->boolean('penilaian_pembayaran_split')->default(true);
            $table->foreignId('penilaian_pendekatan_penilaian_id')->nullable()->constrained('pendekatan_penilaians')->nullOnDelete();
            $table->foreignId('penilaian_metode_penilaian_id')->nullable()->constrained('metode_penilaians')->nullOnDelete();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('penawarans');
    }
};
