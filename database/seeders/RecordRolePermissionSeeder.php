<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RecordRolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Define Record permissions
        $permissions = [
            'record.viewAny',
            'record.view',
            'record.create',
            'record.edit',
            'record.delete',
        ];

        // Ensure the permissions exist
        foreach ($permissions as $permissionName) {
            Permission::firstOrCreate([
                'name' => $permissionName,
                'guard_name' => 'web',  // Explicitly set the guard
            ]);
        }

        // Define roles
        $roles = [
            'admin',
            'records',
            // Add other roles as needed
        ];

        // Create roles and assign permissions
        foreach ($roles as $roleName) {
            $role = Role::firstOrCreate(['name' => $roleName, 'guard_name' => 'web']);

            // Assign permissions based on role
            switch ($roleName) {
                case 'admin':
                    // Admin gets all Record permissions
                    $role->givePermissionTo($permissions);
                    break;

                case 'records':
                    // 'records' role gets a subset of permissions
                    $role->givePermissionTo([
                        'record.viewAny',
                        'record.view',
                        'record.create',
                        'record.edit',
                        // 'record.delete', // Uncomment if needed
                    ]);
                    break;

                default:
                    // Optionally assign default permissions to other roles
                    break;
            }
        }
    }
}
