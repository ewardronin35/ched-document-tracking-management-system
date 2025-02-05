<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class PermissionSeed extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // 1. Clear any cached permissions (important step)
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // 2. Define your roles
        $roles = [
            'admin',
            'HR',
            'Records',
            'RegionalDirector',
            'Supervisor',
            'Technical',
            'Unifast',
            'Accounting',
            'user' // If you also want a generic "user" role
        ];

        // 3. Define all permissions for your application
        //    This can include both "SO Master List" related and general user management
        $permissions = [
            // SO MasterListController permissions
            'so_master_lists.view',
            'so_master_lists.create',
            'so_master_lists.edit',
            'so_master_lists.delete',

            // Additional general permissions
            'manage users',
            'import users',
            'generate passwords',
            // Add more if needed
            'view permissions',
            'update permissions',
        ];

        // 4. Create or update each permission for guard 'web' (or your chosen guard)
        foreach ($permissions as $permName) {
            Permission::firstOrCreate([
                'name' => $permName,
                'guard_name' => 'web',
            ]);
        }

        // 5. Create or update roles
        foreach ($roles as $roleName) {
            Role::firstOrCreate([
                'name' => $roleName,
                'guard_name' => 'web',
            ]);
        }

        // 6. Assign permissions to roles as needed

        // a) Admin role gets all permissions
        $adminRole = Role::where('name', 'admin')->first();
        if ($adminRole) {
            $adminRole->syncPermissions($permissions); // admin has all
        }

        // b) Records role has all "so_master_lists" permissions
        $recordsRole = Role::where('name', 'Records')->first();
        if ($recordsRole) {
            $recordsRole->syncPermissions([
                'so_master_lists.view',
                'so_master_lists.create',
                'so_master_lists.edit',
                'so_master_lists.delete'
            ]);
        }

        // c) If you want user role to have only basic permissions
        $userRole = Role::where('name', 'user')->first();
        if ($userRole) {
            // Assign only minimal user permissions here if applicable
            // e.g., $userRole->syncPermissions(['view permissions']);
            // Or leave empty if standard user has no special permissions
        }

        // d) Assign any other roles with their specific permissions...
        // Example: 'HR' role might have 'manage users'
        $hrRole = Role::where('name', 'HR')->first();
        if ($hrRole) {
            $hrRole->syncPermissions(['manage users']);
        }

        // e) Add more custom role-permission assignments as needed

        // 7. Clear cache again after making changes
        app()[PermissionRegistrar::class]->forgetCachedPermissions();
    }
}
