<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
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
        ];
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
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // create permissions
        Permission::create(['name' => 'manage users']);
        Permission::create(['name' => 'import users']);
        Permission::create(['name' => 'generate passwords']);
        // Add more permissions as needed

        // create roles and assign existing permissions
        $adminRole = Role::create(['name' => 'admin']);
        $adminRole->givePermissionTo(['manage users', 'import users', 'generate passwords']);

        $userRole = Role::create(['name' => 'user']);
        // Assign permissions to user role if necessary
    }
}
}