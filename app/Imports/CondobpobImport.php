<?php

namespace App\Imports;

use App\Models\Condobpob;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class CondobpobImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        return new Condobpob([
            'quarter'           => $row['quarter'] ?? null,
            'No'                => $row['no'] ?? null,  // make sure the heading is lowercased if needed
            'surname'           => $row['surname'] ?? null,
            'first_name'        => $row['first_name'] ?? null,
            'extension_name'    => $row['extension_name'] ?? null,
            'middle_name'       => $row['middle_name'] ?? null,
            'sex'               => $row['sex'] ?? null,
            'or_number'         => $row['or_number'] ?? null,
            'name_of_hei'       => $row['name_of_hei'] ?? null,
            'special_order_no'  => $row['special_order_no'] ?? null,
            'type_of_correction'=> $row['type_of_correction'] ?? null,
            'from_date'         => $row['from_date'] ?? null,
            'to_date'           => $row['to_date'] ?? null,
            'date_applied'      => $row['date_applied'] ?? null,
            'date_released'     => $row['date_released'] ?? null,
        ]);
    }
}
