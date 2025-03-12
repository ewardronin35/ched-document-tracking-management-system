<?php

namespace App\Imports;

use App\Models\CavAbroad;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class AbroadCavSheetImport implements ToModel, WithHeadingRow
{
    /**
     * Map each row of the abroad records sheet to a new Cav model.
     * If a date field is invalid, it returns null.
     */
    
    public function model(array $row)
    {
        if (!array_filter($row)) {
            return null;
        }
        

        return new CavAbroad([
            'quarter'                         => $row['quarter'] ?? null,
            'cav_no'                          => $row['cav_no'] ?? null,
            'region'                          => $row['region'] ?? null,
            'surname'                         => $row['surname'] ?? null,
            'first_name'                      => $row['first_name'] ?? null,
            'extension_name'                  => $row['extension_name'] ?? null,
            'middle_name'                     => $row['middle_name'] ?? null,
            'sex'                             => $row['sex'] ?? null,
            'institution_code'                => $row['institution_code'] ?? null,
            'full_name_of_hei'                => $row['full_name_of_hei'] ?? null,
            'address_of_hei'                  => $row['address_of_hei'] ?? null,
            'official_receipt_number'         => $row['official_receipt_number'] ?? null,
            'type_of_heis'                    => $row['type_of_heis'] ?? null,
            'discipline_code'                 => $row['discipline_code'] ?? null,
            'program_name'                    => $row['program_name'] ?? null,
            'major'                           => $row['major'] ?? null,
            'program_level'                   => $row['program_level'] ?? null,
            'status_of_the_program'           => $row['status_of_the_program'] ?? null,
            'date_started'                    => $row['date_started'] ?? null,
            'date_ended'                      => $row['date_ended'] ?? null,
            'graduation_date'                 => $this->parseDate($row['graduation_date'] ?? null),
            'units_earned'                    => $row['units_earned'] ?? null,
            'special_order_no'                => $row['special_order_no'] ?? null,
            'series'                          => $row['series'] ?? null,
            'date_applied'                    => $this->parseDate($row['date_applied'] ?? null),
            'date_released'                   => $this->parseDate($row['date_released'] ?? null),
            'airway_bill_no'                  => $row['airway_bill_no'] ?? null,
            'serial_number_of_security_paper' => $row['serial_number_of_security_paper'] ?? null,
            'purpose_of_cav'                  => $row['purpose_of_cav'] ?? null,
            'target_country'                  => $row['target_country'] ?? 'Abroad',
        ]);
    }
    
    /**
     * Attempts to parse a given value into a date (Y-m-d).
     * If parsing fails (e.g., for non-date strings), returns null.
     *
     * @param mixed $value
     * @return string|null
     */
    protected function parseDate($value)
    {
        if (empty($value)) {
            return null;
        }
        
        try {
            $date = new \DateTime($value);
            return $date->format('Y-m-d');
        } catch (\Exception $e) {
            // Log the failure if needed:
            // \Illuminate\Support\Facades\Log::warning("Could not parse date: " . $value);
            return null;
        }
    }
}
