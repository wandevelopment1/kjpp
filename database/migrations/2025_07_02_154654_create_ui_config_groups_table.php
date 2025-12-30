<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('ui_config_groups', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('slug')->unique();
            $table->integer('order')->default(0);
            $table->timestamps();
        });

        DB::table('ui_config_groups')->insert([
            ['id' => 1, 'title' => 'Web Setting', 'slug' => 'web-setting', 'created_at' => now(), 'updated_at' => now()],
            // ['id' => 2, 'title' => 'Social Link', 'slug' => 'social-link', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 3, 'title' => 'Contact Info', 'slug' => 'contact-info', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 4, 'title' => 'Auth Setting', 'slug' => 'auth-setting', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ui_config_groups');
    }
};
