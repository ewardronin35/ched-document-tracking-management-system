<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use App\Models\Incoming;
use App\Models\Outgoing;
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class QuarterlyReportExport implements FromArray, WithHeadings, WithTitle, WithStyles, ShouldAutoSize, WithColumnFormatting, WithEvents
{
    protected $year;
    protected $quarter;
    protected $docType;

    public function __construct($data, $year = null, $quarter = null, $docType = 'all')
    {
        $this->data = is_array($data) ? $data : json_decode(json_encode($data), true);
        $this->year = $year ?? date('Y');
        $this->quarter = $quarter;
        $this->docType = $docType;
    }

    public function array(): array
    {
        $rows = [];
        
        // Determine which quarters to include
        $quarters = $this->quarter ? [$this->quarter] : [1, 2, 3, 4];
        
        // Get quarterly data
        foreach ($quarters as $q) {
            // Base queries
            $incomingQuery = Incoming::whereYear('date_received', $this->year)
                ->whereRaw('QUARTER(date_received) = ?', [$q]);
                
            $outgoingQuery = Outgoing::whereYear('date_released', $this->year)
                ->whereRaw('QUARTER(date_released) = ?', [$q]);
                
            // Filter by document type if specified
            if ($this->docType === 'incoming') {
                $outgoingQuery = clone $outgoingQuery;
                $outgoingQuery->whereRaw('1=0'); // Ensures no results
            } elseif ($this->docType === 'outgoing') {
                $incomingQuery = clone $incomingQuery;
                $incomingQuery->whereRaw('1=0'); // Ensures no results
            }
            
            // Count documents for this quarter
            $incomingCount = $incomingQuery->count();
            $outgoingCount = $outgoingQuery->count();
            $totalCount = $incomingCount + $outgoingCount;
            
            // Get the month names for this quarter
            $startMonth = ($q - 1) * 3 + 1;
            $monthNames = [];
            for ($i = 0; $i < 3; $i++) {
                $monthNames[] = Carbon::create($this->year, $startMonth + $i, 1)->format('F');
            }
            
            // Format month range (e.g., "January-February-March")
            $monthRange = implode('-', $monthNames);
            
            // Add to rows
            $rows[] = [
                "Q{$q}",
                $monthRange,
                $incomingCount,
                $outgoingCount,
                $totalCount,
                $totalCount > 0 ? round(($incomingCount / $totalCount) * 100, 1) . '%' : '0%',
                $totalCount > 0 ? round(($outgoingCount / $totalCount) * 100, 1) . '%' : '0%',
            ];
        }
        
        // Calculate total row
        $totalIncoming = array_sum(array_column($rows, 2));
        $totalOutgoing = array_sum(array_column($rows, 3));
        $grandTotal = $totalIncoming + $totalOutgoing;
        
        // Add total row
        $rows[] = [
            'Total',
            'All Year',
            $totalIncoming,
            $totalOutgoing,
            $grandTotal,
            $grandTotal > 0 ? round(($totalIncoming / $grandTotal) * 100, 1) . '%' : '0%',
            $grandTotal > 0 ? round(($totalOutgoing / $grandTotal) * 100, 1) . '%' : '0%',
        ];
        
        // Add category breakdown if we're exporting outgoings
        if ($this->docType !== 'incoming') {
            // Add a separator row
            $rows[] = [null, null, null, null, null, null, null];
            
            // Add category header
            $rows[] = ['Category Breakdown', null, null, null, null, null, null];
            
            // Get outgoing categories
            $outgoingCategories = Outgoing::whereYear('date_released', $this->year)
                ->when($this->quarter, function($query) {
                    return $query->whereRaw('QUARTER(date_released) = ?', [$this->quarter]);
                })
                ->select('category')
                ->selectRaw('COUNT(*) as count')
                ->groupBy('category')
                ->orderBy('count', 'desc')
                ->get();
                
            // Add category rows
            foreach ($outgoingCategories as $category) {
                $categoryName = $category->category ?: 'Uncategorized';
                $percentage = round(($category->count / ($totalOutgoing ?: 1)) * 100, 1);
                $rows[] = [
                    $categoryName,
                    null,
                    null,
                    $category->count,
                    null,
                    null,
                    $percentage . '%'
                ];
            }
        }
        
        // Add status breakdown if we're exporting outgoings
        if ($this->docType !== 'incoming') {
            // Add a separator row
            $rows[] = [null, null, null, null, null, null, null];
            
            // Add status header
            $rows[] = ['Status Breakdown', null, null, null, null, null, null];
            
            // Get outgoing statuses
            $outgoingStatuses = Outgoing::whereYear('date_released', $this->year)
                ->when($this->quarter, function($query) {
                    return $query->whereRaw('QUARTER(date_released) = ?', [$this->quarter]);
                })
                ->select('status')
                ->selectRaw('COUNT(*) as count')
                ->groupBy('status')
                ->orderBy('count', 'desc')
                ->get();
                
            // Add status rows
            foreach ($outgoingStatuses as $status) {
                $statusName = $status->status ?: 'Undefined';
                $percentage = round(($status->count / ($totalOutgoing ?: 1)) * 100, 1);
                $rows[] = [
                    $statusName,
                    null,
                    null,
                    $status->count,
                    null,
                    null,
                    $percentage . '%'
                ];
            }
        }
        
        return $rows;
    }

    public function headings(): array
    {
        return [
            'Quarter',
            'Months',
            'Incoming Documents',
            'Outgoing Documents',
            'Total Documents',
            'Incoming %',
            'Outgoing %'
        ];
    }

    public function title(): string
    {
        $title = "Quarterly Report {$this->year}";
        if ($this->quarter) {
            $title .= " - Q{$this->quarter}";
        }
        if ($this->docType !== 'all') {
            $title .= " - " . ucfirst($this->docType) . " Only";
        }
        return $title;
    }
    
    public function styles(Worksheet $sheet)
    {
        // Get the number of rows - subtract 1 because we're adding the total row later
        $totalRows = count($this->array());
        $mainDataRows = $totalRows;
        
        // Loop through the array to find where our data tables start and end
        $array = $this->array();
        $categoryStart = null;
        $statusStart = null;
        
        for ($i = 0; $i < $totalRows; $i++) {
            if (isset($array[$i][0]) && $array[$i][0] === 'Category Breakdown') {
                $categoryStart = $i + 1;
            }
            if (isset($array[$i][0]) && $array[$i][0] === 'Status Breakdown') {
                $statusStart = $i + 1;
            }
        }
        
        // Calculate where total row is (it's not always array[4] because of filtering)
        $totalRowIndex = null;
        for ($i = 0; $i < $totalRows; $i++) {
            if (isset($array[$i][0]) && $array[$i][0] === 'Total') {
                $totalRowIndex = $i + 1; // +1 for headings row
                $mainDataRows = $totalRowIndex; // Main data section ends at total row
                break;
            }
        }
        
        return [
            // Style the header row
            1 => [
                'font' => [
                    'bold' => true,
                    'color' => ['argb' => 'FFFFFFFF'],
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['argb' => 'FF0078D4'], // CHED Blue
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                ],
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => ['argb' => 'FFD0D0D0'],
                    ],
                ],
            ],
            
            // Style all data cells
            'A2:G' . ($mainDataRows) => [
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => ['argb' => 'FFD0D0D0'],
                    ],
                ],
            ],
            
            // Style the total row
            'A' . ($totalRowIndex) . ':G' . ($totalRowIndex) => [
                'font' => [
                    'bold' => true,
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['argb' => 'FFF2F2F2'],
                ],
                'borders' => [
                    'top' => [
                        'borderStyle' => Border::BORDER_MEDIUM,
                        'color' => ['argb' => 'FF000000'],
                    ],
                    'bottom' => [
                        'borderStyle' => Border::BORDER_MEDIUM,
                        'color' => ['argb' => 'FF000000'],
                    ],
                ],
            ],
            
            // Style the category header if it exists
            $categoryStart ? ('A' . ($categoryStart) . ':G' . ($categoryStart)) : null => [
                'font' => [
                    'bold' => true,
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['argb' => 'FF0078D4'],
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_LEFT,
                ],
                'font' => [
                    'color' => ['argb' => 'FFFFFFFF'],
                ]
            ],
            
            // Style the status header if it exists
            $statusStart ? ('A' . ($statusStart) . ':G' . ($statusStart)) : null => [
                'font' => [
                    'bold' => true,
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['argb' => 'FF0078D4'],
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_LEFT,
                ],
                'font' => [
                    'color' => ['argb' => 'FFFFFFFF'],
                ]
            ],
        ];
    }
    
    public function columnFormats(): array
    {
        return [
            'C' => NumberFormat::FORMAT_NUMBER,
            'D' => NumberFormat::FORMAT_NUMBER,
            'E' => NumberFormat::FORMAT_NUMBER,
            'F' => NumberFormat::FORMAT_PERCENTAGE_00,
            'G' => NumberFormat::FORMAT_PERCENTAGE_00,
        ];
    }
    
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                // Auto filter for the header
                $worksheet = $event->sheet->getDelegate();
                $worksheet->setAutoFilter('A1:G1');
                
                // Set column widths
                $worksheet->getColumnDimension('A')->setWidth(12);
                $worksheet->getColumnDimension('B')->setWidth(30);
                $worksheet->getColumnDimension('C')->setWidth(20);
                $worksheet->getColumnDimension('D')->setWidth(20);
                $worksheet->getColumnDimension('E')->setWidth(20);
                $worksheet->getColumnDimension('F')->setWidth(15);
                $worksheet->getColumnDimension('G')->setWidth(15);
                
                // Add a title
                $worksheet->insertNewRowBefore(1, 2);
                $worksheet->mergeCells('A1:G1');
                $title = $this->title();
                $worksheet->setCellValue('A1', $title);
                
                // Style the title
                $worksheet->getStyle('A1')->getFont()->setBold(true);
                $worksheet->getStyle('A1')->getFont()->setSize(16);
                $worksheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                
                // Add generation date
                $worksheet->mergeCells('A2:G2');
                $worksheet->setCellValue('A2', 'Generated on: ' . Carbon::now()->format('F d, Y'));
                $worksheet->getStyle('A2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                
                // Add a chart if applicable
                // Note: Not all export libraries support chart creation
                // You may need to create visualizations client-side
            },
        ];
    }
}