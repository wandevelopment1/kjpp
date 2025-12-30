<?php

namespace App\Console\Commands;

use ReflectionClass;
use Illuminate\Support\Str;
use Illuminate\Console\Command;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\File;
use Spatie\Permission\Models\Permission;

class SyncPermissionsFromControllers extends Command
{
    protected $signature = 'permission:sync-from-controllers';
    protected $description = 'Generate permissions only from selected controllers';

    protected $methodPermissionMap = [
        'index'   => 'index',
        'create'  => 'create',
        'store'   => 'create',
        'edit'    => 'edit',
        'update'  => 'edit',
        'destroy' => 'delete',
        'show'    => 'show',
        'sort'    => 'sort',
    ];

    // Daftar controller yang ingin dipilih untuk generate permission
    protected $selectedControllers = [
            \App\Http\Controllers\UserController::class,
            \App\Http\Controllers\RoleController::class,
            \App\Http\Controllers\UiConfigGroupController::class,
            \App\Http\Controllers\UiConfigController::class,   
    ];

    public function handle()
    {
        $controllerPath = app_path('Http/Controllers');
        $controllerFiles = File::allFiles($controllerPath);

        $permissions = [];

        foreach ($controllerFiles as $file) {
            $className = $this->getFullClassNameFromFile($file);
            if (!class_exists($className)) continue;

            // Cek apakah controller ada di dalam daftar selectedControllers
            if (!in_array($className, $this->selectedControllers)) {
                $this->warn("⏭️ Skipping $className, not in selected controllers");
                continue;
            }

            $shortName = class_basename($className);
            if (Str::endsWith($shortName, 'Controller')) {
                $resource = Str::kebab(str_replace('Controller', '', $shortName));

                $methods = get_class_methods($className);
                foreach ($methods as $method) {
                    if (array_key_exists($method, $this->methodPermissionMap)) {
                        $perm = "{$resource}.{$this->methodPermissionMap[$method]}";
                        $permissions[] = $perm;
                    }
                }
            }
        }

        $permissions = array_unique($permissions);

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
            $this->line("✅ Created or found: $permission");
        }

    
        $admin = Role::firstOrCreate(['name' => 'admin']);
        $admin->syncPermissions($permissions);
    
        // Assign role ke user pertama (optional)
        $user = \App\Models\User::find(1);
        $user->assignRole('admin');


        $this->info("🎉 Permission sync complete! Total: " . count($permissions));
    }

    protected function getFullClassNameFromFile($file)
    {
        $path = $file->getRealPath();
        $contents = file_get_contents($path);

        preg_match('/namespace\s+(.+);/', $contents, $matches);
        $namespace = $matches[1] ?? null;

        $class = Str::before($file->getFilename(), '.php');

        return $namespace ? $namespace . '\\' . $class : null;
    }
}
