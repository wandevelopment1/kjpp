<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use ZipArchive;

class WrapFullCrud extends Command
{
    protected $signature = 'wrap:fullcrud {name}';
    protected $description = 'Wrap full CRUD files (model, migration, controller, view, route) into a zip archive';

    public function handle()
    {
        $name = Str::studly($this->argument('name'));
        $snake = Str::snake($name);
        $pluralSnake = Str::snake(Str::pluralStudly($name));
        $kebab = Str::kebab($name);

        $this->info("Membungkus Full CRUD untuk: {$name}");

        // Lokasi output zip
        $zipPath = base_path("wrap-{$kebab}.zip");

        $zip = new ZipArchive();
        if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
            $this->error("Tidak bisa membuat ZIP file di {$zipPath}");
            return;
        }

        // 1. Model
        $modelPath = app_path("Models/{$name}.php");
        $this->addToZip($zip, $modelPath, "app/Models/{$name}.php");

        // 2. Migration
        $migrations = File::files(database_path('migrations'));
        foreach ($migrations as $migration) {
            if (str_contains($migration->getFilename(), "create_{$pluralSnake}_table")) {
                $this->addToZip($zip, $migration->getPathname(), "database/migrations/" . $migration->getFilename());
            }
        }

        // 3. Controller
        $controllerPath = app_path("Http/Controllers/Admin/{$name}Controller.php");
        $this->addToZip($zip, $controllerPath, "app/Http/Controllers/Admin/{$name}Controller.php");

        // 4. Views
        $viewPath = resource_path("views/admin/{$snake}");
        if (File::exists($viewPath)) {
            $files = File::allFiles($viewPath);
            foreach ($files as $file) {
                $relativePath = "resources/views/admin/{$snake}/" . $file->getFilename();
                $this->addToZip($zip, $file->getPathname(), $relativePath);
            }
        }

        // 5. Routes
        $routePath = base_path("routes/admin/{$kebab}.php");
        $this->addToZip($zip, $routePath, "routes/admin/{$kebab}.php");

        $zip->close();

        $this->info("✅ Wrap selesai: {$zipPath}");
    }

    private function addToZip(ZipArchive $zip, $filePath, $zipPath)
    {
        if (File::exists($filePath)) {
            $zip->addFile($filePath, $zipPath);
            $this->info("✔ Ditambahkan: {$zipPath}");
        } else {
            $this->warn("✖ Tidak ditemukan: {$filePath}");
        }
    }
}
