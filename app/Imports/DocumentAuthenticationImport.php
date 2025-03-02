<?php

namespace App\Imports;

use App\Models\DocumentAuthentication;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Log;
class DocumentAuthenticationImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        Log::info('DocumentAuthenticationImport processing row:', ['row' => $row]);
        return new DocumentAuthentication([
            'quarter'          => $row['quarter'] ?? null,
            'No'               => $row['no'] ?? null,
            'document_type'    => $row['document_type'] ?? null,
            'surname'          => $row['surname'] ?? null,
            'first_name'       => $row['first_name'] ?? null,
            'extension_name'   => $row['extension_name'] ?? null,
            'middle_name'      => $row['middle_name'] ?? null,
            'full_name_of_hei' => $row['full_name_of_hei'] ?? null,
            'program_name'     => $row['program_name'] ?? null,
            'major'            => $row['major'] ?? null,
            'date_started'     => $row['date_started'] ?? null,
            'date_ended'       => $row['date_ended'] ?? null,
            'year_graduated'   => $row['year_graduated'] ?? null,
            'units_earned'     => $row['units_earned'] ?? null,
            'purpose'          => $row['purpose'] ?? null,
            'no_of_pcs'        => $row['no_of_pcs'] ?? null,
            'special_order'    => $row['special_order'] ?? null,
            'date_applied'     => $row['date_applied'] ?? null,
            'date_released'    => $row['date_released'] ?? null,
        ]);
    }
}
