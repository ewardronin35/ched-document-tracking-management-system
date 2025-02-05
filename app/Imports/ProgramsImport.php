<?php

namespace App\Imports;

use App\Models\Programs;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ProgramsImport implements ToModel, WithChunkReading, WithHeadingRow
{
    /**
     * Create a new Program model for each row.
     */
    public function model(array $row)
    {
        return new Programs([
            'name' => $row['name'],         // Exact column header from the Excel file
            'psced_code' => $row['psced_code'], // Exact column header from the Excel file
        ]);
    }

    /**
     * Define chunk size for processing.
     */
    public function chunkSize(): int
    {
        return 100; // Process 100 rows at a time
    }
}
