<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class CAVSeeder extends Seeder
{
    public function run()
    {
        // Create roles
        $admin = Role::firstOrCreate(['name' => 'admin']);
        $records = Role::firstOrCreate(['name' => 'Records']);
        // Assign CAV permissions to admin
        $admin->givePermissionTo([
            'cav.viewAny',
            'cav.view',
            'cav.create',
            'cav.edit',
            'cav.delete',
            'cav.import',
            'cav.export',
        ]);
        // Assign CAV permissions to Records
        $records->givePermissionTo([
            'cav.view',
            'cav.create',
            'cav.edit',
            'cav.delete',
            'cav.import',
            'cav.export',
        ]);
     

        // Assign minimal permissions to user (if applicable)
        // $user->givePermissionTo(['cav.view']);
    }
}
