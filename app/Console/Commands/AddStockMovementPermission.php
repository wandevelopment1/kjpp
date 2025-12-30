<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class AddStockMovementPermission extends Command
{
    protected $signature = 'permission:add-stock-movement';
    protected $description = 'Add stock movement permissions to the system';

    public function handle()
    {
        $permissions = [
            'admin.stock-movement.index',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
            $this->info("Created permission: {$permission}");
        }

        // Assign to admin role
        $admin = Role::where('name', 'Admin')->first();
        if ($admin) {
            $admin->givePermissionTo($permissions);
            $this->info("Assigned permissions to Admin role");
        }

        $this->info("Stock movement permissions added successfully!");
        return Command::SUCCESS;
    }
}