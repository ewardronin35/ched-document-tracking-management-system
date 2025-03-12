<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Carbon\Carbon;

class DocumentsExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    protected $documents;

    public function __construct($documents)
    {
        $this->documents = $documents;
    }

    public function collection()
    {
        return $this->documents;
    }

    public function headings(): array
    {
        return [
            'Tracking Number',
            'Full Name',
            'Email',
            'Phone Number',
            'Document Type',
            'Status',
            'Approval Status',
            'Created At',
            'Updated At',
            'Details'
        ];
    }

    public function map($document): array
    {
        // Parse status details
        $statusDetails = json_decode($document->status_details, true) ?? [];
        $detailsMessage = $statusDetails['message'] ?? 'No details';

        return [
            $document->tracking_number,
            $document->full_name,
            $document->email,
            $document->phone_number,
            $document->document_type,
            $document->status,
            $document->approval_status,
            Carbon::parse($document->created_at)->format('Y-m-d H:i:s'),
            Carbon::parse($document->updated_at)->format('Y-m-d H:i:s'),
            $detailsMessage
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Style the first row (headers)
        $sheet->getStyle('1')->getFont()->setBold(true);
        $sheet->getStyle('1')->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setARGB('FFC0C0C0'); // Light gray background
    }
}