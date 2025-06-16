<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create roles
        $roles = [
            'Administrator',
            'Kasir',
        ];

        foreach ($roles as $roleId => $roleName) {
            $role = Role::updateOrCreate(
                ['name' => $roleName],
                ['guard_name' => 'web']
            );

            // Assign all permissions to Administrator role
            if ($roleName === 'Administrator') {
                $permissions = Permission::all();
                $role->syncPermissions($permissions);
            }
        }
    }
}
