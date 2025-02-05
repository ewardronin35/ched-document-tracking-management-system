<?php

namespace App\Imports;

use App\Models\Majors;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
class MajorsImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Majors([
            'name' => $row['majors'],          // Column name in the Excel file
            'program_id' => $row['program_id'], // Ensure valid program IDs in Excel
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
