<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;

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
            0 => new LocalCavSheetImport(),              // Sheet 1: Local CAV records
            1 => new AbroadCavSheetImport(),             // Sheet 2: Abroad CAV records
            2 => new CondobpobImport(),                  // Sheet 3: CondoBPOB
            3 => new CavOsdImport(),                     // Sheet 4: CAV‑OSDS
            4 => new DocumentAuthenticationImport(),     // Sheet 5: Authentication of Documents
            5 => new CertificationImport(),              // Sheet 6: Certification
            // Add additional sheets here if needed...
        ];
    }
}
