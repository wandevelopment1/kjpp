<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $tables = [
            'penanggung_jawab_penilai',
            'penanggung_jawab_reviewers',
            'penanggung_jawab_inspeksi',
            'penanggung_jawab_penanggung_penilai',
        ];

        foreach ($tables as $tableName) {
            Schema::table($tableName, function (Blueprint $table) {
                $table->string('no_mappi')->nullable()->after('name');
                $table->string('no_izin_penilai')->nullable()->after('no_mappi');
                $table->string('no_rmk')->nullable()->after('no_izin_penilai');
            });
        }
    }

    public function down(): void
    {
        $tables = [
            'penanggung_jawab_penilai',
            'penanggung_jawab_reviewers',
            'penanggung_jawab_inspeksi',
            'penanggung_jawab_penanggung_penilai',
        ];

        foreach ($tables as $tableName) {
            Schema::table($tableName, function (Blueprint $table) {
                $table->dropColumn(['no_mappi', 'no_izin_penilai', 'no_rmk']);
            });
        }
    }
};
