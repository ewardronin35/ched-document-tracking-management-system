<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\MultiSheetCavImport;

class ImportCavExcel extends Command
{
    /**
     * The name and signature of the console command.
     *
     * Here we require one argument "file" (the path to the Excel file).
     */
    protected $signature = 'import:cav {file : The path to the Excel file}';

    /**
     * The console command description.
     */
    protected $description = 'Import a multi-sheet Excel file for 2025 CAV, CAV LOCAL, CAV ABROAD, CONDOBPOB, CAV‑OSDS, AUTHENTICATION OF DOCUMENTS and CERTIFICATION';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $file = $this->argument('file');

        $this->info("Starting import from file: $file");

        try {
            Excel::import(new MultiSheetCavImport, $file);
            $this->info("Import successful.");
        } catch (\Exception $e) {
            $this->error("Error during import: " . $e->getMessage());
        }
    }
}
