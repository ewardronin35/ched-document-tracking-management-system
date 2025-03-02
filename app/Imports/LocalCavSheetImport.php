<?php

namespace App\Imports;

use App\Models\Cav;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Log;

class LocalCavSheetImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        Log::info('LocalCavSheetImport processing row:', ['row' => $row]);

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
            'date_started'                   => $this->parseDate($row['date_started'] ?? null),
            'date_ended'                     => $this->parseDate($row['date_ended'] ?? null),
            'graduation_date'                => $this->parseDate($row['graduation_date'] ?? null),
            'units_earned'                   => $row['units_earned'] ?? null,
            'special_order_no'               => $row['special_order_no'] ?? null,
            'series'                         => $row['series'] ?? null,
            'date_applied'                   => $this->parseDate($row['date_applied'] ?? null),
            'date_released'                  => $this->parseDate($row['date_released'] ?? null),
            'airway_bill_no'                 => $row['airway_bill_no'] ?? null,
            'serial_number_of_security_paper'=> $row['serial_number_of_security_paper'] ?? null,
            'target_country'                 => 'Philippines', // Force for local records
        ]);
    }
    
    /**
     * Attempts to parse a given value into a date in "Y-m-d" format.
     * If parsing fails (e.g., for values like "FIRST SEMESTER 2009-2010"), returns null.
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
            // If the value is numeric (as in Excel serial dates), convert it:
            if (is_numeric($value)) {
                // Excel dates start at 25569 in Unix time
                $unixDate = ($value - 25569) * 86400;
                return gmdate("Y-m-d", $unixDate);
            }
            
            $date = new \DateTime($value);
            return $date->format('Y-m-d');
        } catch (\Exception $e) {
            Log::warning("Could not parse date: " . $value);
            return null;
        }
    }
}
