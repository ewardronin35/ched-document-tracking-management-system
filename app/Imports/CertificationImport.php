<?php

namespace App\Imports;

use App\Models\Certification;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Log;
use PhpOffice\PhpSpreadsheet\Shared\Date;


class CertificationImport implements ToModel, WithHeadingRow
{
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
        if(!array_filter($row)) {
            return null;
        }

        Log::info('CertificationImport processing row:', ['row' => $row]);
        return new Certification([
            
            'quarter'            => $row['Quarter'] ?? null,
            'o_prefix'           => $row['o_prefix'] ?? null,
            'cav_no'             => $row['cav_no'] ?? null,
            'certification_type' => $row['certification'] ?? null,
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
            'date_applied'       => $this->transformDate($row['date_applied'] ?? null),
            'date_released'      => $this->transformDate($row['date_released'] ?? null),
            'remarks'            => $row['remarks'] ?? null,
        ]);
    }
}
