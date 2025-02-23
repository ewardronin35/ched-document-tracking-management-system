<?php

namespace App\Imports;

use App\Models\Cav;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class LocalCavSheetImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        return new Cav([
            'quarter'                        => $row['quarter'] ?? null,
            'cav_no'                         => $row['cav_no'] ?? null,
            'region'                         => $row['region'] ?? null,
            'surname'                        => $row['surname'] ?? null,
            'first_name'                     => $row['first_name'] ?? null,
            'extension_name'                 => $row['extension_name'] ?? null,
            'middle_name'                    => $row['middle_name'] ?? null,
            'sex'                            => $row['sex'] ?? null,
            'institution_code'               => $row['institution_code'] ?? null,
            'full_name_of_hei'               => $row['full_name_of_hei'] ?? null,
            'address_of_hei'                 => $row['address_of_hei'] ?? null,
            'official_receipt_number'        => $row['official_receipt_number'] ?? null,
            'type_of_heis'                   => $row['type_of_heis'] ?? null,
            'discipline_code'                => $row['discipline_code'] ?? null,
            'program_name'                   => $row['program_name'] ?? null,
            'major'                          => $row['major'] ?? null,
            'program_level'                  => $row['program_level'] ?? null,
            'status_of_the_program'          => $row['status_of_the_program'] ?? null,
            'date_started'                   => $row['date_started'] ?? null,
            'date_ended'                     => $row['date_ended'] ?? null,
            'graduation_date'                => $row['graduation_date'] ?? null,
            'units_earned'                   => $row['units_earned'] ?? null,
            'special_order_no'               => $row['special_order_no'] ?? null,
            'series'                         => $row['series'] ?? null,
            'date_applied'                   => $row['date_applied'] ?? null,
            'date_released'                  => $row['date_released'] ?? null,
            'airway_bill_no'                 => $row['airway_bill_no'] ?? null,
            'serial_number_of_security_paper'=> $row['serial_number_of_security_paper'] ?? null,
            'target_country'                 => 'Philippines', // Force for local records
        ]);
    }
}
