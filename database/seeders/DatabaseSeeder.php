<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->withPersonalTeam()->create();
        $this->call(RolesAndPermissionsSeeder::class);
        $this->call(RolesAndPermissionsSeeders::class);
        $this->call(OutgoingSeeder::class);
        $this->call(PermissionSeed::class);
        $this->call(CavPermissionSeeder::class);
        $this->call(RecordRolePermissionSeeder::class);


        User::factory()->withPersonalTeam()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);
    }
}
