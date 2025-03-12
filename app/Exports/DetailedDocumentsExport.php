<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Carbon\Carbon;

class DetailedDocumentsExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    protected $documents;
    protected $statistics;

    public function __construct($documents, $statistics)
    {
        $this->documents = $documents;
        $this->statistics = $statistics;
    }

    public function collection()
    {
        return $this->documents;
    }

    public function headings(): array
    {
        return [
            'Report Statistics',
            'Total Documents',
            $this->statistics['total_documents'],
            '',
            'Accepted Documents',
            $this->statistics['accepted_documents'],
            '',
            'Rejected Documents',
            $this->statistics['rejected_documents']
        ];
    }

    public function map($document): array
    {
        return [
            $document->tracking_number,
            $document->full_name,
            $document->email,
            $document->phone_number,
            $document->document_type,
            $document->status,
            $document->approval_status,
            Carbon::parse($document->created_at)->format('Y-m-d H:i:s')
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Style the first row (headers)
        $sheet->getStyle('1')->getFont()->setBold(true);
        
        // Color the header row with a light gray background
        $sheet->getStyle('1')->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setARGB('FFC0C0C0');

        // Add extra sheet with statistics
        $sheet->setCellValue('A10', 'Document Type Distribution');
        $row = 11;
        foreach ($this->statistics['document_types'] as $type => $count) {
            $sheet->setCellValue("A{$row}", $type);
            $sheet->setCellValue("B{$row}", $count);
            $row++;
        }
    }
}