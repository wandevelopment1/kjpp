<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('penanggung_jawab_companies', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        Schema::create('penanggung_jawab_penanggung_penilai', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        Schema::create('penanggung_jawab_penilai', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        Schema::create('penanggung_jawab_reviewers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        Schema::create('penanggung_jawab_inspeksi', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('penanggung_jawab_inspeksi');
        Schema::dropIfExists('penanggung_jawab_reviewers');
        Schema::dropIfExists('penanggung_jawab_penilai');
        Schema::dropIfExists('penanggung_jawab_penanggung_penilai');
        Schema::dropIfExists('penanggung_jawab_companies');
    }
};
