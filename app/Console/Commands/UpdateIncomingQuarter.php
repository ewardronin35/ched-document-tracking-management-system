<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Incoming;
use Carbon\Carbon;

class UpdateIncomingQuarter extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:incoming-quarter';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update the quarter field for Incoming records based on the date_released';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Get all Incoming records that have a date_released and a null quarter.
        $incomings = Incoming::whereNotNull('date_released')
            ->whereNull('quarter')
            ->get();

        $this->info("Found {$incomings->count()} Incoming record(s) to update.");

        foreach ($incomings as $incoming) {
            // Parse the date_released field
            $date = Carbon::parse($incoming->date_released);
            $month = $date->month;
            // Calculate the quarter (January - March: Q1, etc.)
            $quarter = intdiv($month - 1, 3) + 1;

            $incoming->quarter = $quarter;
            $incoming->save();

            $this->info("Updated record ID {$incoming->id} with quarter {$quarter}.");
        }

        $this->info('Update complete.');
    }
}
