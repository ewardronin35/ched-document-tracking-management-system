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
