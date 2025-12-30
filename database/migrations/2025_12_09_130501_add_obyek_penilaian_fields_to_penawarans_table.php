<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('penawarans', function (Blueprint $table) {
            $table->foreignId('obyek_penilaian_obyek_id')->nullable()->after('penilaian_metode_penilaian_id')->constrained('obyeks')->nullOnDelete();
            $table->string('obyek_penilaian_debitur')->nullable()->after('obyek_penilaian_obyek_id');
            $table->string('obyek_penilaian_legalitas')->nullable()->after('obyek_penilaian_debitur');
            $table->text('obyek_penilaian_lokasi')->nullable()->after('obyek_penilaian_legalitas');
            $table->foreignId('obyek_penilaian_kepemilikan_id')->nullable()->after('obyek_penilaian_lokasi')->constrained('kepemilikans')->nullOnDelete();
            $table->foreignId('obyek_penilaian_kab_kota_id')->nullable()->after('obyek_penilaian_kepemilikan_id')->constrained('kepada_kab_kotas')->nullOnDelete();
            $table->foreignId('obyek_penilaian_provinsi_id')->nullable()->after('obyek_penilaian_kab_kota_id')->constrained('kepada_provinsis')->nullOnDelete();
            $table->string('obyek_penilaian_kode_pos', 20)->nullable()->after('obyek_penilaian_provinsi_id');
            $table->string('obyek_penilaian_luas_tanah', 100)->nullable()->after('obyek_penilaian_kode_pos');
            $table->string('obyek_penilaian_imb')->nullable()->after('obyek_penilaian_luas_tanah');
            $table->string('obyek_penilaian_luas_bangunan', 100)->nullable()->after('obyek_penilaian_imb');
            $table->foreignId('obyek_penilaian_tipe_properti_id')->nullable()->after('obyek_penilaian_luas_bangunan')->constrained('tipe_propertis')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('penawarans', function (Blueprint $table) {
            $table->dropConstrainedForeignId('obyek_penilaian_tipe_properti_id');
            $table->dropColumn('obyek_penilaian_luas_bangunan');
            $table->dropColumn('obyek_penilaian_imb');
            $table->dropColumn('obyek_penilaian_luas_tanah');
            $table->dropColumn('obyek_penilaian_kode_pos');
            $table->dropConstrainedForeignId('obyek_penilaian_provinsi_id');
            $table->dropConstrainedForeignId('obyek_penilaian_kab_kota_id');
            $table->dropConstrainedForeignId('obyek_penilaian_kepemilikan_id');
            $table->dropColumn('obyek_penilaian_lokasi');
            $table->dropColumn('obyek_penilaian_legalitas');
            $table->dropColumn('obyek_penilaian_debitur');
            $table->dropConstrainedForeignId('obyek_penilaian_obyek_id');
        });
    }
};
