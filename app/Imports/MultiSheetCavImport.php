<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Illuminate\Support\Facades\Log;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class MultiSheetCavImport implements WithMultipleSheets
{
    /**
     * Return an array mapping sheet indexes to import classes.
     *
     * Adjust the sheet indexes and classes according to your Excel file.
     */
    public function sheets(): array
    {
        return [
            '2025 CAV'                 => new AllCavSheetImport(),
            'CAV LOCAL'                => new LocalCavSheetImport(),
            'CAV ABROAD'               => new AbroadCavSheetImport(),
            'CONDOBPOB'                => new CondobpobImport(),
            'CAV-OSDS'                 => new CavOsdImport(),
            'AUTHENTICATION OF DOCUMENTS' => new DocumentAuthenticationImport(),
            'CERTIFICATION'            => new CertificationImport(),
            // Additional sheets can be added here...
        ];
    } 
    
}
