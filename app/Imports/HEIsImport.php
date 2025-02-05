<?php

namespace App\Imports;

use App\Models\HEI;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class HEIsImport implements ToModel, WithHeadingRow
{
    /**
     * Define how the data from the Excel rows will be mapped to the database.
     */
    public function model(array $row)
    {
        return new HEI([
            'Region' => $row['region'], // Ensure 'region' matches the Excel column header
            'HEIs'   => $row['heis'],
            'UII'    => $row['uii'],
        ]);
    }
}
