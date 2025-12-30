<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class DeleteFullCrud extends Command
{
    protected $signature = 'delete:fullcrud {name}';
    protected $description = 'Delete full CRUD files (model, migration, controller, view, route) for a given name';

    public function handle()
    {
        $name = Str::studly($this->argument('name'));
        $snake = Str::snake($name);
        $plural = Str::pluralStudly($name);
        $kebab = Str::kebab($name);

        $this->info("Menghapus Full CRUD untuk: {$name}");

        // 1. Hapus Model
        $modelPath = app_path("Models/{$name}.php");
        $this->deleteFile($modelPath, "Model");

        // 2. Hapus Migration
        $migrations = File::files(database_path('migrations'));
        foreach ($migrations as $migration) {
            if (str_contains($migration->getFilename(), "create_" . Str::snake(Str::plural($name)) . "_table")) {
                $this->deleteFile($migration->getPathname(), "Migration");
            }
        }

        // 3. Hapus Controller
        $controllerPath = app_path("Http/Controllers/Admin/{$name}Controller.php");
        $this->deleteFile($controllerPath, "Controller");

        // 4. Hapus View
        $viewPath = resource_path("views/admin/" . $snake);
        if (File::exists($viewPath)) {
            File::deleteDirectory($viewPath);
            $this->info("✔ View folder dihapus: {$viewPath}");
        }

        // 5. Hapus Route file di routes/admin
        $routePath = base_path("routes/admin/{$kebab}.php");
        $this->deleteFile($routePath, "Route");

        // 6. Jalankan artisan permission sync
        $this->call('permission:sync-from-middleware');


        $this->info("✅ Semua file utama untuk {$name} berhasil dihapus.");


    }

    private function deleteFile($path, $type)
    {
        if (File::exists($path)) {
            File::delete($path);
            $this->info("✔ {$type} dihapus: {$path}");
        } else {
            $this->warn("✖ {$type} tidak ditemukan: {$path}");
        }
    }
}
