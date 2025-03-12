<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Carbon\Carbon;

class DocumentTypeExport implements FromArray, WithHeadings, WithTitle, WithStyles, ShouldAutoSize, WithEvents
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function array(): array
    {
        $rows = [];

        foreach ($this->data->categories as $category) {
            $rows[] = [
                $category->name,
                $category->count,
                $category->percentage . '%'
            ];
        }
        
        // Add a total row
        $totalCount = array_sum(array_column($rows, 1));
        $rows[] = [
            'Total',
            $totalCount,
            '100%'
        ];

        return $rows;
    }

    public function headings(): array
    {
        return [
            'Document Category',
            'Count',
            'Percentage'
        ];
    }

    public function title(): string
    {
        return "Document Type Report {$this->data->year}";
    }
    
    public function styles(Worksheet $sheet)
    {
        $lastRow = count($this->array()) + 1; // +1 for headers
        
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
            'A2:C' . $lastRow => [
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => ['argb' => 'FFD0D0D0'],
                    ],
                ],
            ],
            
            // Style the total row
            'A' . $lastRow . ':C' . $lastRow => [
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
        ];
    }
    
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                // Auto filter for the header
                $worksheet = $event->sheet->getDelegate();
                $worksheet->setAutoFilter('A1:C1');
                
                // Add a title
                $worksheet->insertNewRowBefore(1, 2);
                $worksheet->mergeCells('A1:C1');
                $title = $this->title();
                $worksheet->setCellValue('A1', $title);
                
                // Style the title
                $worksheet->getStyle('A1')->getFont()->setBold(true);
                $worksheet->getStyle('A1')->getFont()->setSize(16);
                $worksheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                
                // Add generation date
                $worksheet->mergeCells('A2:C2');
                $worksheet->setCellValue('A2', 'Generated on: ' . Carbon::now()->format('F d, Y'));
                $worksheet->getStyle('A2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                
                // Create a chart to visualize the data
                $categories = $this->data->categories;
                
                if (count($categories) > 0) {
                    $dataSeriesLabels = [];
                    $dataSeriesValues = [];
                    
                    $rowCount = count($categories);
                    
                    // Add chart after the table
                    $chartStartRow = 3 + $rowCount + 4; // Headers + data rows + some space
                    
                    // Add a section title for the chart
                    $worksheet->setCellValue('A' . ($chartStartRow - 2), 'Visual Representation');
                    $worksheet->getStyle('A' . ($chartStartRow - 2))->getFont()->setBold(true);
                    $worksheet->getStyle('A' . ($chartStartRow - 2))->getFont()->setSize(14);
                    
                    // Set up the chart
                    $chartType = \PhpOffice\PhpSpreadsheet\Chart\Chart::EXCEL_CHART_TYPE_PIE;
                    
                    // Add data series
                    $labelRange = "'". $worksheet->getTitle() . "'!" . 
                                 $worksheet->getCellByColumnAndRow(1, 4)->getCoordinate() . ':' . 
                                 $worksheet->getCellByColumnAndRow(1, 3 + $rowCount)->getCoordinate();
                    
                    $valueRange = "'". $worksheet->getTitle() . "'!" . 
                                 $worksheet->getCellByColumnAndRow(2, 4)->getCoordinate() . ':' . 
                                 $worksheet->getCellByColumnAndRow(2, 3 + $rowCount)->getCoordinate();
                    
                    $dataSeriesLabels[] = new \PhpOffice\PhpSpreadsheet\Chart\DataSeriesValues('String', $labelRange, null, $rowCount);
                    $dataSeriesValues[] = new \PhpOffice\PhpSpreadsheet\Chart\DataSeriesValues('Number', $valueRange, null, $rowCount);
                    
                    // Create the data series
                    $series = new \PhpOffice\PhpSpreadsheet\Chart\DataSeries(
                        $chartType,
                        null,
                        range(0, count($dataSeriesValues) - 1),
                        $dataSeriesLabels,
                        range(0, count($dataSeriesValues) - 1),
                        $dataSeriesValues
                    );
                    $series->setPlotDirection(\PhpOffice\PhpSpreadsheet\Chart\DataSeries::DIRECTION_COL);
                    
                    // Create a layout for the chart
                    $layout = new \PhpOffice\PhpSpreadsheet\Chart\Layout();
                    $layout->setShowVal(true);
                    $layout->setShowPercent(true);
                    
                    // Create the chart
                    $chart = new \PhpOffice\PhpSpreadsheet\Chart\Chart(
                        'chart1',
                        null,
                        null,
                        $series,
                        $layout
                    );
                    
                    // Set the position
                    $chart->setTopLeftPosition('A' . $chartStartRow);
                    $chart->setBottomRightPosition('C' . ($chartStartRow + 15));
                    
                    // Add the chart to the worksheet
                    $worksheet->addChart($chart);
                }
            },
        ];
    }
}