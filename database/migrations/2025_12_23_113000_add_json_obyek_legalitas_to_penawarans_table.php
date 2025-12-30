<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('penawarans', function (Blueprint $table) {
            $table->json('obyek_penilaian_obyek_ids')->nullable()->after('obyek_penilaian_obyek_id');
            $table->json('obyek_penilaian_legalitas_items')->nullable()->after('obyek_penilaian_debitur');
        });
    }

    public function down(): void
    {
        Schema::table('penawarans', function (Blueprint $table) {
            $table->dropColumn('obyek_penilaian_obyek_ids');
            $table->dropColumn('obyek_penilaian_legalitas_items');
        });
    }
};
