<?php

namespace App\Exports;

use App\Models\Outgoing;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class OutgoingsExport implements FromCollection, WithHeadings
{
    protected $documentType;

    public function __construct($documentType)
    {
        $this->documentType = $documentType;
    }

    public function collection()
    {
        return Outgoing::where('category', $this->documentType)
            ->select('id', 'no', 'date_released', 'category', 'addressed_to', 'email', 'subject_of_letter', 'remarks', 'status')
            ->get();
    }

    public function headings(): array
    {
        return ['ID', 'No.', 'Date Released', 'Category', 'Addressed To', 'Email', 'Subject', 'Remarks', 'Status'];
    }
}
