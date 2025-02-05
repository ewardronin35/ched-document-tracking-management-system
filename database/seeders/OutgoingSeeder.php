<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Outgoing;

class OutgoingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create 50 random outgoing records
        Outgoing::factory()->count(50)->create();
    }
}
