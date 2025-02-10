<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\IncomingImport;

class ImportIncomings extends Command
{
    /**
     * The name and signature of the console command.
     *
     * {file : The path to the CSV/Excel file to import}
     *
     * @var string
     */
    protected $signature = 'import:incomings {file : The path to the CSV or Excel file}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import incoming records from a CSV or Excel file into the database.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $filePath = $this->argument('file');

        if (!file_exists($filePath)) {
            $this->error("File not found: {$filePath}");
            return 1;
        }

        $this->info("Importing incoming records from: {$filePath}");

        try {
            Excel::import(new IncomingImport, $filePath);
            $this->info("Import completed successfully.");
        } catch (\Exception $e) {
            $this->error("Error during import: " . $e->getMessage());
            return 1;
        }

        return 0;
    }
}
