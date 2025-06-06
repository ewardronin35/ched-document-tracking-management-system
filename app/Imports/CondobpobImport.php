<?php

namespace App\Imports;

use App\Models\Condobpob;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Log;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class CondobpobImport implements ToModel, WithHeadingRow
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
        if (!array_filter($row)) {
            return null;
        }
        Log::info('CondobpobImport processing row:', ['row' => $row]);
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
            'date_applied'      => $this->transformDate($row['date_applied'] ?? null),
            'date_released'     => $this->transformDate($row['date_released'] ?? null),
        ]);
    }
}
