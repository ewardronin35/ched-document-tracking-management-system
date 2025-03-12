<?php

namespace App\Imports;

use App\Models\Cav;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class AllCavSheetImport implements ToModel, WithHeadingRow
{
    /**
     * Transforms Excel date values (numeric or string) into a valid Y-m-d date string.
     */
    protected function transformDate($value)
    {
        // If it's an Excel numeric date, convert it.
        if (is_numeric($value)) {
            try {
                return Date::excelToDateTimeObject($value)->format('Y-m-d');
            } catch (\Exception $e) {
                Log::error("Error transforming numeric date: {$value} - " . $e->getMessage());
                return null;
            }
        }
        
        // If it's a string, fix common typos and try parsing it.
        if (is_string($value)) {
            // Fix a common typo: "OCOTBER" to "OCTOBER"
            $value = str_ireplace('ocotber', 'october', $value);
            
            try {
                return Carbon::parse($value)->format('Y-m-d');
            } catch (\Exception $e) {
                Log::error("Error parsing string date: {$value} - " . $e->getMessage());
                return null;
            }
        }
        
        return $value;
    }

    public function model(array $row)
    {
        // Skip the row if it's entirely empty.
        if (!array_filter($row)) {
            return null;
        }

        Log::info('All CAVS processing row:', ['row' => $row]);

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
            'graduation_date'                => $this->transformDate($row['graduation_date'] ?? null),
            'units_earned'                   => $row['units_earned'] ?? null,
            'special_order_no'               => $row['special_order_no'] ?? null,
            'series'                         => $row['series'] ?? null,
            'date_applied'                   => $this->transformDate($row['date_applied'] ?? null),
            'date_released'                  => $this->transformDate($row['date_released'] ?? null),
            'airway_bill_no'                 => $row['airway_bill_no'] ?? null,
            'serial_number_of_security_paper'=> $row['serial_number_of_security_paper'] ?? null,
            'purpose_of_cav'                 => $row['purpose_of_cav'] ?? null,
            'target_country'                 => $row['target_country'] ?? null,
        ]);
    }
}
