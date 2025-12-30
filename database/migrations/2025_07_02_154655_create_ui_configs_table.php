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
        Schema::create('ui_configs', function (Blueprint $table) {
            $table->id();
            $table->string('key');
            $table->text('value')->nullable();
            $table->string('label')->nullable();
            $table->enum('type', ['text_field','text_area','ckeditor','image','file'])->default('text_field');
            $table->foreignId('ui_config_group_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });

        DB::table('ui_configs')->insert([
            // Images
            ['key' => 'logo', 'value' => 'dummy-logo.png', 'type' => 'image', 'label' => 'Logo', 'ui_config_group_id' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'logo_white', 'value' => 'dummy-logo-white.png', 'type' => 'image', 'label' => 'Dark Mode Logo', 'ui_config_group_id' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'icon', 'value' => 'dummy-icon.png', 'type' => 'image', 'label' => 'Icon', 'ui_config_group_id' => 1, 'created_at' => now(), 'updated_at' => now()],

            // Text Content
            ['key' => 'title', 'value' => 'Dummy Site', 'type' => 'text_field', 'label' => 'Site Title', 'ui_config_group_id' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'copyright', 'value' => '© 2024 Dummy Forum. All rights reserved.', 'type' => 'text_field', 'label' => 'Copyright Text', 'ui_config_group_id' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'keywords', 'value' => 'dummy', 'type' => 'text_field', 'label' => 'Meta Keywords (Split by comma (,))', 'ui_config_group_id' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'description', 'value' => 'This is a dummy forum description for testing purposes. Experience engaging discussions and community interactions in our sample forum platform.', 'type' => 'text_field', 'label' => 'Meta Description', 'ui_config_group_id' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'landing_title', 'value' => 'Welcome to Dummy Site - Your Sample Website', 'type' => 'text_field', 'label' => 'Landing Page Title', 'ui_config_group_id' => 1, 'created_at' => now(), 'updated_at' => now()]
        ]);

        //   DB::table('ui_configs')->insert([
        //     ['key' => 'facebook', 'value' => '#', 'label' => 'Facebook', 'type' => 'text_field', 'ui_config_group_id' => 2, 'created_at' => now(), 'updated_at' => now()],
        //     ['key' => 'twitter', 'value' => null, 'label' => 'Twitter', 'type' => 'text_field', 'ui_config_group_id' => 2, 'created_at' => now(), 'updated_at' => now()],
        //     ['key' => 'instagram', 'value' => '#', 'label' => 'Instagram', 'type' => 'text_field', 'ui_config_group_id' => 2, 'created_at' => now(), 'updated_at' => now()],
        //     ['key' => 'linkedin', 'value' => null, 'label' => 'Linkedin', 'type' => 'text_field', 'ui_config_group_id' => 2, 'created_at' => now(), 'updated_at' => now()],
        //     ['key' => 'youtube', 'value' => '#', 'label' => 'Youtube', 'type' => 'text_field', 'ui_config_group_id' => 2, 'created_at' => now(), 'updated_at' => now()],
        //     ['key' => 'tiktok', 'value' => '#', 'label' => 'Tiktok', 'type' => 'text_field', 'ui_config_group_id' => 2, 'created_at' => now(), 'updated_at' => now()],
        //     ['key' => 'pinterest', 'value' => null, 'label' => 'Pinterest', 'type' => 'text_field', 'ui_config_group_id' => 2, 'created_at' => now(), 'updated_at' => now()],
        //     ['key' => 'telegram', 'value' => null, 'label' => 'Telegram', 'type' => 'text_field', 'ui_config_group_id' => 2, 'created_at' => now(), 'updated_at' => now()]
        // ]);

        DB::table('ui_configs')->insert([
            ['key' => 'address', 'value' => 'Jl. Kolonel Bustomi No.16, Cimande Hilir, Kec. Caringin, Kabupaten Bogor, Jawa Barat 16730', 'label' => 'Address', 'type' => 'text_field', 'ui_config_group_id' => 3, 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'email1', 'value' => null, 'label' => 'Email 1', 'type' => 'text_field', 'ui_config_group_id' => 3, 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'email2', 'value' => null, 'label' => 'Email 2', 'type' => 'text_field', 'ui_config_group_id' => 3, 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'phone1', 'value' => null, 'label' => 'Phone 1', 'type' => 'text_field', 'ui_config_group_id' => 3, 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'phone2', 'value' => null, 'label' => 'Phone 2', 'type' => 'text_field', 'ui_config_group_id' => 3, 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'whatsapp1', 'value' => '+62 0878‑5361‑1457', 'label' => 'Whatsapp 1', 'type' => 'text_field', 'ui_config_group_id' => 3, 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'whatsapp2', 'value' => null, 'label' => 'Whatsapp 2', 'type' => 'text_field', 'ui_config_group_id' => 3, 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'maps', 'value' => 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3966.318852558839!2d106.85260919999999!3d-6.2216198999999985!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e69f38f52f5b4e7%3A0x5b699deee5551c97!2sJl.%20Lap.%20Ros%20Barat%204%20No.15%2C%20RT.1%2FRW.5%2C%20Bukit%20Duri%2C%20Kec.%20Tebet%2C%20Kota%20Jakarta%20Selatan%2C%20Daerah%20Khusus%20Ibukota%20Jakarta%2012840!5e0!3m2!1sid!2sid!4v1755842856929!5m2!1sid!2sid', 'label' => 'Maps', 'type' => 'text_field', 'ui_config_group_id' => 3, 'created_at' => now(), 'updated_at' => now()]
        ]);

        DB::table('ui_configs')->insert([
            ['key' => 'image', 'value' => 'dummy-bg.jpg', 'type' => 'image', 'label' => 'Image', 'ui_config_group_id' => 4, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ui_configs');
    }
};
