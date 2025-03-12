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
use PhpOffice\PhpSpreadsheet\Style\Color;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Carbon\Carbon;

class StatusExport implements FromArray, WithHeadings, WithTitle, WithStyles, ShouldAutoSize, WithEvents
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function array(): array
    {
        $rows = [];

        foreach ($this->data->statuses as $status) {
            $rows[] = [
                $status->name,
                $status->count,
                $status->percentage . '%'
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
            'Status',
            'Count',
            'Percentage'
        ];
    }

    public function title(): string
    {
        return "Status Report {$this->data->year}";
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
                
                // Create a status visualization section
                $statuses = $this->data->statuses;
                
                if (count($statuses) > 0) {
                    $rowCount = count($statuses);
                    
                    // Add visualization after the table
                    $visualStartRow = 3 + $rowCount + 4; // Headers + data rows + some space
                    
                    // Add a section title
                    $worksheet->setCellValue('A' . ($visualStartRow - 2), 'Status Distribution Visualization');
                    $worksheet->getStyle('A' . ($visualStartRow - 2))->getFont()->setBold(true);
                    $worksheet->getStyle('A' . ($visualStartRow - 2))->getFont()->setSize(14);
                    
                    // Define status colors
                    $statusColors = [
                        'Released' => 'FF4CAF50',
                        'Pending' => 'FF2196F3',
                        'Processing' => 'FFFF9800',
                        'Canceled' => 'FFF44336',
                        'default' => 'FF9E9E9E'
                    ];
                    
                    // Add bars for each status
                    $currentRow = $visualStartRow;
                    foreach ($statuses as $index => $status) {
                        // Status label
                        $worksheet->setCellValue('A' . $currentRow, $status->name);
                        $worksheet->getStyle('A' . $currentRow)->getFont()->setBold(true);
                        
                        // Value and percentage
                        $worksheet->setCellValue('C' . $currentRow, 
                            $status->count . ' (' . $status->percentage . '%)');
                        $worksheet->getStyle('C' . $currentRow)->getAlignment()
                            ->setHorizontal(Alignment::HORIZONTAL_RIGHT);
                        
                        $currentRow++;
                        
                        // Progress bar cells (using merged cells)
                        $worksheet->mergeCells('A' . $currentRow . ':C' . $currentRow);
                        
                        // Color the progress bar based on status percentage
                        $percentage = $status->percentage;
                        $fillColor = isset($statusColors[$status->name]) ? 
                            $statusColors[$status->name] : $statusColors['default'];
                        
                        // Create a conditional formatting rule for the progress bar
                        $conditionalStyles = $worksheet->getStyle('A' . $currentRow . ':C' . $currentRow)
                            ->getConditionalStyles();
                        
                        // Define the progress bar cell style
                        $worksheet->getRowDimension($currentRow)->setRowHeight(20);
                        $worksheet->getStyle('A' . $currentRow)->getFill()
                            ->setFillType(Fill::FILL_SOLID)
                            ->setStartColor(new Color('FFEEEEEE')); // Light gray background
                        
                        // Create a percentage bar using conditional formatting
                        $columnWidthTotal = 
                            $worksheet->getColumnDimension('A')->getWidth() +
                            $worksheet->getColumnDimension('B')->getWidth() +
                            $worksheet->getColumnDimension('C')->getWidth();
                        
                        // Apply custom styling to simulate a progress bar
                        $cellCoordinate = 'A' . $currentRow;
                        $worksheet->getStyle($cellCoordinate)->getAlignment()
                            ->setHorizontal(Alignment::HORIZONTAL_LEFT);
                        
                        // Create a string of spaces based on percentage
                        $barLength = min(100, max(0, intval($percentage)));
                        $bar = str_repeat("█", intval($barLength / 2));
                        $worksheet->setCellValue($cellCoordinate, $bar);
                        
                        // Color the bar
                        $worksheet->getStyle($cellCoordinate)->getFont()
                            ->setColor(new Color($fillColor));
                        
                        $currentRow += 2; // Add space between bars
                    }
                    
                    // Add a legend
                    $legendRow = $currentRow + 2;
                    $worksheet->setCellValue('A' . $legendRow, 'Status Legend:');
                    $worksheet->getStyle('A' . $legendRow)->getFont()->setBold(true);
                    
                    $legendRow++;
                    $legendKeys = [
                        'Released' => 'Completed document processing',
                        'Pending' => 'Awaiting processing or approval',
                        'Processing' => 'Currently being processed',
                        'Canceled' => 'Processing canceled or rejected',
                        'Other' => 'Other statuses'
                    ];
                    
                    foreach ($legendKeys as $status => $description) {
                        $color = isset($statusColors[$status]) ? 
                            $statusColors[$status] : $statusColors['default'];
                        
                        $worksheet->setCellValue('A' . $legendRow, '■ ' . $status);
                        $worksheet->getStyle('A' . $legendRow)->getFont()
                            ->setColor(new Color($color));
                        
                        $worksheet->setCellValue('B' . $legendRow, $description);
                        $legendRow++;
                    }
                    
                    // Add chart
                    $dataSeriesLabels = [];
                    $dataSeriesValues = [];
                    
                    // Create chart after the legend
                    $chartStartRow = $legendRow + 2;
                    
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