<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            'post.index',
            'post.create',
            'post.edit',
            'post.delete',
        ];
    
        foreach ($permissions as $perm) {
            Permission::firstOrCreate(['name' => $perm]);
        }
    
        $admin = Role::firstOrCreate(['name' => 'admin']);
        $admin->syncPermissions($permissions);
    
        // Assign role ke user pertama (optional)
        $user = \App\Models\User::find(1);
        $user->assignRole('admin');
    }
}
