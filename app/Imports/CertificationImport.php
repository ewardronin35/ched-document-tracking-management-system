<?php

namespace App\Imports;

use App\Models\Certification;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class CertificationImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        return new Certification([
            'quarter'            => $row['quarter'] ?? null,
            'o_prefix'           => $row['o_prefix'] ?? null,
            'cav_no'             => $row['cav_no'] ?? null,
            'certification_type' => $row['certification_type'] ?? null,
            'surname'            => $row['surname'] ?? null,
            'first_name'         => $row['first_name'] ?? null,
            'extension_name'     => $row['extension_name'] ?? null,
            'middle_name'        => $row['middle_name'] ?? null,
            'full_name_of_hei'    => $row['full_name_of_hei'] ?? null,
            'program_name'       => $row['program_name'] ?? null,
            'major'              => $row['major'] ?? null,
            'date_of_entry'      => $row['date_of_entry'] ?? null,
            'date_ended'         => $row['date_ended'] ?? null,
            'year_graduated'     => $row['year_graduated'] ?? null,
            'so_no'              => $row['so_no'] ?? null,
            'or_no'              => $row['or_no'] ?? null,
            'date_applied'       => $row['date_applied'] ?? null,
            'date_released'      => $row['date_released'] ?? null,
            'remarks'            => $row['remarks'] ?? null,
        ]);
    }
}
