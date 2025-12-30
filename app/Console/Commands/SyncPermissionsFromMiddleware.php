<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class SyncPermissionsFromMiddleware extends Command
{
    protected $signature = 'permission:sync-from-middleware';
    protected $description = 'Sync permissions based on can:* middleware inside all controllers';

    public function handle()
    {
        $controllerPath = app_path('Http/Controllers');
        $controllerFiles = File::allFiles($controllerPath);

        $permissions = [];

        foreach ($controllerFiles as $file) {
            $fullPath = $file->getRealPath();
            $contents = file_get_contents($fullPath);

            // Lewati file yang bukan controller
            if (!Str::endsWith($file->getFilename(), 'Controller.php')) continue;

            // Dapatkan namespace + nama class
            $namespace = $this->getNamespaceFromFile($contents);
            $className = $namespace . '\\' . Str::before($file->getFilename(), '.php');

            $this->info("🔍 Scanning: $className");

            // Cari semua penggunaan middleware('can:...')
            preg_match_all("/->middleware\(\s*['\"]can:([^'\"]+)['\"]\s*(?:,\s*\[[^\)]*\])?\)/", $contents, $matches);
            foreach ($matches[1] as $perm) {
                $perm = trim($perm);
                $permissions[] = $perm;
                $this->line("✅ Found permission: $perm");
            }
        }

        $permissions = array_unique($permissions);

        // Delete permissions that are not found in controllers
        Permission::whereNotIn('name', $permissions)->delete();

        // Create new permissions if they don't exist
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Sync ke role admin
        $admin = Role::firstOrCreate([
            'name' => 'Admin',
            'description' => 'Administrator role with full access to all permissions and features'
        ]);
        $admin->syncPermissions($permissions);

        // Optional: assign role admin ke user id 1
        $user = \App\Models\User::find(1);
        if ($user) {
            $user->assignRole('Admin');
        }

        $this->info("🎉 Sync complete! Total permissions: " . count($permissions));
    }

    protected function getNamespaceFromFile($contents)
    {
        preg_match('/namespace\s+(.+);/', $contents, $matches);
        return $matches[1] ?? 'App\Http\Controllers';
    }
}
