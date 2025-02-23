<?php

namespace App\Imports;

use App\Models\CavsOsd;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class CavOsdImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        return new CavsOsd([
            'quarter'           => $row['quarter'] ?? null,
            'o'                 => $row['o'] ?? null,
            'seq'               => $row['seq'] ?? null,
            'cav_osds'          => $row['cav_osds'] ?? null,
            'certification'     => $row['certification'] ?? null,
            'surname'           => $row['surname'] ?? null,
            'first_name'        => $row['first_name'] ?? null,
            'extension_name'    => $row['extension_name'] ?? null,
            'middle_name'       => $row['middle_name'] ?? null,
            'sex'               => $row['sex'] ?? null,
            'institution_code'  => $row['institution_code'] ?? null,
            'full_name_of_hei'  => $row['full_name_of_hei'] ?? null,
            'address_of_hei'    => $row['address_of_hei'] ?? null,
            'type_of_heis'      => $row['type_of_heis'] ?? null,
            'discipline_code'   => $row['discipline_code'] ?? null,
            'program_name'      => $row['program_name'] ?? null,
            'major'             => $row['major'] ?? null,
            'program_level'     => $row['program_level'] ?? null,
            'status_of_the_program' => $row['status_of_the_program'] ?? null,
            'date_started'      => $row['date_started'] ?? null,
            'date_ended'        => $row['date_ended'] ?? null,
            'graduation_date'   => $row['graduation_date'] ?? null,
            'units_earned'      => $row['units_earned'] ?? null,
            'special_order_no'  => $row['special_order_no'] ?? null,
            'date_applied'      => $row['date_applied'] ?? null,
            'date_released'     => $row['date_released'] ?? null,
            'purpose_of_cav'    => $row['purpose_of_cav'] ?? null,
            'target_country'    => $row['target_country'] ?? null,
            'semester'          => $row['semester'] ?? null,
            'academic_year'     => $row['academic_year'] ?? null,
        ]);
    }
}
