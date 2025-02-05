<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeders extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Define roles
        $roles = [
            'admin',
            'HR',
            'Records',
            'RegionalDirector',
            'Supervisor',
            'Technical',
            'Unifast',
            'Accounting',
        ];

        // Define permissions for SoMasterListController
        $permissions = [
            'so_master_lists.view',
            'so_master_lists.create',
            'so_master_lists.edit',
            'so_master_lists.delete',
            'so_master_lists.import',
            'so_master_lists.export',
            'so_master_lists.store',
            'so_master_lists.update',
        ];

        // Create permissions
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Create roles and assign existing permissions
        foreach ($roles as $roleName) {
            $role = Role::firstOrCreate(['name' => $roleName]);

            // Assign permissions based on role
            // Assuming 'admin' has all permissions
            if ($roleName === 'admin') {
                $role->syncPermissions($permissions);
            }

            // 'Records' role has specific permissions
            if ($roleName === 'Records') {
                $role->syncPermissions(['so_master_lists.view', 'so_master_lists.create', 'so_master_lists.edit', 'so_master_lists.delete']);
            }

            // Add other roles and their permissions as needed
            // Example:
            // if ($roleName === 'HR') {
            //     $role->syncPermissions(['some_other_permission']);
            // }
        }
    }
}
