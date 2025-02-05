<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class CavPermissionSeeder extends Seeder
{
    public function run()
    {
        // CAV Permissions
        Permission::firstOrCreate(['name' => 'cav.viewAny']);
        Permission::firstOrCreate(['name' => 'cav.view']);
        Permission::firstOrCreate(['name' => 'cav.create']);
        Permission::firstOrCreate(['name' => 'cav.edit']);
        Permission::firstOrCreate(['name' => 'cav.delete']);
        Permission::firstOrCreate(['name' => 'cav.import']);
        Permission::firstOrCreate(['name' => 'cav.export']);
    }
}
