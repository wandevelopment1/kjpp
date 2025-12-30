<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        DB::statement("ALTER TABLE penawarans MODIFY COLUMN status ENUM('draft_1','acc_1','acc_2') NOT NULL DEFAULT 'draft_1'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE penawarans MODIFY COLUMN status ENUM('draft_1','acc_1') NOT NULL DEFAULT 'draft_1'");
    }
};
