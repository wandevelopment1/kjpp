<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pengguna_laporan_pts', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('alamat')->nullable();
            $table->string('kab_kota')->nullable();
            $table->string('provinsi')->nullable();
            $table->string('kode_pos', 20)->nullable();
            $table->timestamps();
        });

        Schema::create('pengguna_laporan_nama', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        Schema::create('pengguna_laporan_jenis_pengguna', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        Schema::create('pengguna_laporan_jenis_industri', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengguna_laporan_jenis_industri');
        Schema::dropIfExists('pengguna_laporan_jenis_pengguna');
        Schema::dropIfExists('pengguna_laporan_nama');
        Schema::dropIfExists('pengguna_laporan_pts');
    }
};
