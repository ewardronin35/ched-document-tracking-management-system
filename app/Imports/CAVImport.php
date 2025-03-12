<?php

namespace App\Imports;

use App\Models\Cav;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class CavImport implements WithMultipleSheets
{
    /**
     * Define the sheets that will be imported.
     * Sheet index 0 will be for local CAV records and sheet index 1 for abroad.
     */


    public function sheets(): array
    {
        return [
            
            'CAV LOCAL' => new LocalCavSheetImport(),
            'CAV ABROAD'=> new AbroadCavSheetImport(),
        ];
    }
}
class AllCavSheetImport  implements ToModel, WithHeadingRow
{
    /**
     * Map each row of the first sheet (local records) to a new CAV model.
     * Force the target_country to 'Philippines'.
     */
    protected function transformDate($value)
{
    if (is_numeric($value)) {
        try {
            return Date::excelToDateTimeObject($value)->format('Y-m-d');
        } catch (\Exception $e) {
            // Optionally, log or handle the exception if needed.
            return null;
        }
    }
    return $value;
}
    public function model(array $row)
{
    if (!array_filter($row)) {
        return null;
    }
    return new Cav([
        'cav_no'                        => $row['cav_no'] ?? null,
        'region'                        => $row['region'] ?? null,
        'surname'                       => $row['surname'] ?? null,
        'first_name'                    => $row['first_name'] ?? null,
        'extension_name'                => $row['extension_name'] ?? null,
        'middle_name'                   => $row['middle_name'] ?? null,
        'sex'                           => $row['sex'] ?? null,
        'institution_code'              => $row['institution_code'] ?? null,
        'full_name_of_hei'              => $row['full_name_of_hei'] ?? null,
        'address_of_hei'                => $row['address_of_hei'] ?? null,
        'official_receipt_number'       => $row['official_receipt_number'] ?? null,
        'type_of_heis'                  => $row['type_of_heis'] ?? null,
        'discipline_code'               => $row['discipline_code'] ?? null,
        'program_name'                  => $row['program_name'] ?? null,
        'major'                         => $row['major'] ?? null,
        'program_level'                 => $row['program_level'] ?? null,
        'status_of_the_program'         => $row['status_of_the_program'] ?? null,
        'date_started'                  => $row['date_started'] ?? null,
        'date_ended'                    => $row['date_ended'] ?? null,
        'graduation_date'               => $row['graduation_date'] ?? null,
        'units_earned'                  => $row['units_earned'] ?? null,
        'special_order_no'              => $row['special_order_no'] ?? null,
        'series'                        => $row['series'] ?? null,
        'date_applied'                  => $this->transformDate($row['date_applied'] ?? null),
        'date_released'                 => $this->transformDate($row['date_released'] ?? null),
        'airway_bill_no'                => $row['airway_bill_no'] ?? null,
        'serial_number_of_security_paper'=> $row['serial_number_of_security_paper'] ?? null,
        // For abroad records, use the provided target_country or default to 'Abroad'
        'target_country'                => $row['target_country'] ?? null,
    ]);
}
}
class LocalCavSheetImport implements ToModel, WithHeadingRow
{
    /**
     * Map each row of the first sheet (local records) to a new CAV model.
     * Force the target_country to 'Philippines'.
     */
    protected function transformDate($value)
{
    if (is_numeric($value)) {
        try {
            return Date::excelToDateTimeObject($value)->format('Y-m-d');
        } catch (\Exception $e) {
            // Optionally, log or handle the exception if needed.
            return null;
        }
    }
    return $value;
}
    public function model(array $row)
    {
        if (!array_filter($row)) {
            return null;
        }
        return new Cav([
            'cav_no'                        => $row['cav_no'] ?? null,
            'region'                        => $row['region'] ?? null,
            'surname'                       => $row['surname'] ?? null,
            'first_name'                    => $row['first_name'] ?? null,
            'extension_name'                => $row['extension_name'] ?? null,
            'middle_name'                   => $row['middle_name'] ?? null,
            'sex'                           => $row['sex'] ?? null,
            'institution_code'              => $row['institution_code'] ?? null,
            'full_name_of_hei'              => $row['full_name_of_hei'] ?? null,
            'address_of_hei'                => $row['address_of_hei'] ?? null,
            'official_receipt_number'       => $row['official_receipt_number'] ?? null,
            'type_of_heis'                  => $row['type_of_heis'] ?? null,
            'discipline_code'               => $row['discipline_code'] ?? null,
            'program_name'                  => $row['program_name'] ?? null,
            'major'                         => $row['major'] ?? null,
            'program_level'                 => $row['program_level'] ?? null,
            'status_of_the_program'         => $row['status_of_the_program'] ?? null,
            'date_started'                  => $row['date_started'] ?? null,
            'date_ended'                    => $row['date_ended'] ?? null,
            'graduation_date'               => $row['graduation_date'] ?? null,
            'units_earned'                  => $row['units_earned'] ?? null,
            'special_order_no'              => $row['special_order_no'] ?? null,
            'series'                        => $row['series'] ?? null,
            'date_applied'                  => $this->transformDate($row['date_applied'] ?? null),
            'date_released'                 => $this->transformDate($row['date_released'] ?? null),
            'airway_bill_no'                => $row['airway_bill_no'] ?? null,
            'serial_number_of_security_paper'=> $row['serial_number_of_security_paper'] ?? null,
            // For local records, force target_country to 'Philippines'
            'target_country'                => $row['target_country'] ?? null,
        ]);
    }
}

class AbroadCavSheetImport implements ToModel, WithHeadingRow
{
    /**
     * Map each row of the second sheet (abroad records) to a new CAV model.
     * Here you may rely on the Excel data for target_country or set a default.
     */
    protected function transformDate($value)
{
    if (is_numeric($value)) {
        try {
            return Date::excelToDateTimeObject($value)->format('Y-m-d');
        } catch (\Exception $e) {
            // Optionally, log or handle the exception if needed.
            return null;
        }
    }
    return $value;
}
    public function model(array $row)
    {
        if (!array_filter($row)) {
            return null;
        }
        return new Cav([
            'cav_no'                        => $row['cav_no'] ?? null,
            'region'                        => $row['region'] ?? null,
            'surname'                       => $row['surname'] ?? null,
            'first_name'                    => $row['first_name'] ?? null,
            'extension_name'                => $row['extension_name'] ?? null,
            'middle_name'                   => $row['middle_name'] ?? null,
            'sex'                           => $row['sex'] ?? null,
            'institution_code'              => $row['institution_code'] ?? null,
            'full_name_of_hei'              => $row['full_name_of_hei'] ?? null,
            'address_of_hei'                => $row['address_of_hei'] ?? null,
            'official_receipt_number'       => $row['official_receipt_number'] ?? null,
            'type_of_heis'                  => $row['type_of_heis'] ?? null,
            'discipline_code'               => $row['discipline_code'] ?? null,
            'program_name'                  => $row['program_name'] ?? null,
            'major'                         => $row['major'] ?? null,
            'program_level'                 => $row['program_level'] ?? null,
            'status_of_the_program'         => $row['status_of_the_program'] ?? null,
            'date_started'                  => $row['date_started'] ?? null,
            'date_ended'                    => $row['date_ended'] ?? null,
            'graduation_date'               => $row['graduation_date'] ?? null,
            'units_earned'                  => $row['units_earned'] ?? null,
            'special_order_no'              => $row['special_order_no'] ?? null,
            'series'                        => $row['series'] ?? null,
            'date_applied'                  => $this->transformDate($row['date_applied'] ?? null),
            'date_released'                 => $this->transformDate($row['date_released'] ?? null),
            'airway_bill_no'                => $row['airway_bill_no'] ?? null,
            'serial_number_of_security_paper'=> $row['serial_number_of_security_paper'] ?? null,
            // For abroad records, use the provided target_country or default to 'Abroad'
            'target_country'                => $row['target_country'] ?? null,
        ]);
    }
}
