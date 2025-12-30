<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('penawarans', function (Blueprint $table) {
            $table->string('laporan_nomor')->nullable()->after('kepada_no_lingkup');
            $table->date('laporan_tanggal')->nullable()->after('laporan_nomor');
        });
    }

    public function down(): void
    {
        Schema::table('penawarans', function (Blueprint $table) {
            $table->dropColumn(['laporan_nomor', 'laporan_tanggal']);
        });
    }
};
