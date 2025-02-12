<?php

namespace App\Imports;

use App\Models\Outgoing;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow; 
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;

class OutgoingImport implements ToModel, WithHeadingRow, WithMapping, WithChunkReading, WithMultipleSheets, ShouldQueue
{
    /**
     * The sheet index for the current instance.
     *
     * 0 => Main 2025 Outgoing
     * 1 => Travel Memo
     * 2 => O No. DATE OF RELEASED
     */
    protected $sheetIndex = 0;

    /**
     * Setter for the sheet index.
     */
    public function setSheetIndex($index)
    {
        $this->sheetIndex = $index;
    }

    /**
     * Specify the heading row for each sheet.
     */
    public function headingRow(): int
    {
        return 1;
    }

    /**
     * Normalize a header string.
     */
    protected function normalizeHeader(string $header): string
    {
        $header = strtolower(trim($header));
        $header = str_replace([' ', '/', '.'], '_', $header);
        $header = preg_replace('/_+/', '_', $header);
        return trim($header, '_');
    }

    /**
     * Normalize an entire row’s keys.
     */
    protected function normalizeRow(array $row): array
    {
        $normalized = [];
        foreach ($row as $key => $value) {
            if (is_numeric($key)) {
                $normalized[$key] = $value;
                continue;
            }
            $normKey = $this->normalizeHeader($key);
            if (!empty($normKey)) {
                $normalized[$normKey] = $value;
            }
        }
        return $normalized;
    }

    /**
     * Return the first non-empty value for a list of keys.
     */
    protected function getValue(array $row, array $keys, $default = null)
    {
        foreach ($keys as $key) {
            if (isset($row[$key]) && $row[$key] !== '') {
                return $row[$key];
            }
        }
        return $default;
    }

    /**
     * Convert an Excel date value to a Carbon instance.
     */
    protected function parseDate($value)
    {
        if (empty($value)) {
            return null;
        }
        if (is_numeric($value)) {
            try {
                return Carbon::instance(ExcelDate::excelToDateTimeObject($value));
            } catch (\Exception $e) {
                Log::error("Error converting Excel date: " . $e->getMessage());
                return null;
            }
        }
        return Carbon::parse($value);
    }

    /**
     * Convert an Excel time value (a fraction of a day) to a formatted time string.
     */
    protected function parseTime($value)
    {
        if (empty($value)) {
            return null;
        }
        if (is_numeric($value)) {
            try {
                $dateTime = ExcelDate::excelToDateTimeObject($value);
                return Carbon::instance($dateTime)->format('g:i A');
            } catch (\Exception $e) {
                Log::error("Error converting Excel time: " . $e->getMessage());
                return null;
            }
        }
        try {
            return Carbon::parse($value)->format('g:i A');
        } catch (\Exception $e) {
            Log::error("Error parsing time: " . $e->getMessage());
            return $value;
        }
    }

    /**
     * Map each row to an array matching the Outgoing model.
     * Mapping differs based on the sheet index.
     */
    public function map($row): array
    {
        // Normalize the row first.
        $row = $this->normalizeRow($row);
        Log::info("Mapping row for sheet index {$this->sheetIndex}: " . json_encode($row));

        // Parse the common "date_released" field.
        $dateReleased = $this->parseDate($this->getValue($row, ['date_of_released', 'date_released']));
        // Compute quarter from the month if dateReleased exists; default to 1 if missing.
        $quarter = $dateReleased ? intdiv($dateReleased->month - 1, 3) + 1 : 1;

        if ($this->sheetIndex == 0) {
            // Sheet 0: 2025 OUTGOING
            return [
                'no'                => $this->getValue($row, ['no']),
                'date_released'     => $dateReleased,
                'quarter'           => $quarter,
                // Use the "personal/private" column value as the category.
                'category'          => $this->getValue($row, ['personal/private'], 'OUTGOING'),
                'addressed_to'      => $this->getValue($row, ['addresed_to', 'addressed_to']),
                'email'             => $this->getValue($row, ['email']),
                'subject_of_letter' => substr($this->getValue($row, ['subject']), 0, 255),
                'remarks'           => $this->getValue($row, ['remarks']),
                'libcap_no'         => $this->getValue($row, ['libcap_#', 'libcap_no']),
                'status'            => $this->getValue($row, ['status']),
                'chedrix_2025'      => $this->getValue($row, ['chedrix_2025'], 'CHEDRIX-2025'),
                'o'                 => $this->getValue($row, ['o'], 'O'),
            ];
        } elseif ($this->sheetIndex == 1) {
            // Sheet 1: TRAVEL MEMO
            $travelDate = $this->parseDate($this->getValue($row, ['travel_date']));
            return [
                'No'                => $this->getValue($row, ['no']),
                'date_released'     => $dateReleased,
                'quarter'           => $quarter,
                'category'          => 'TRAVEL ORDER',
                'addressed_to'      => $this->getValue($row, ['addresed_to', 'addressed_to']),
                'email'             => $this->getValue($row, ['email']),
                'travel_date'       => $travelDate,
                'es_in_charge'      => $this->getValue($row, ['es_incharge', 'es_in_charge']),
                'chedrix_2025'      => $this->getValue($row, ['chedrix_2025'], 'CHEDRIX-2025'),
                'o'                 => $this->getValue($row, ['o'], 'O'),
            ];
        } elseif ($this->sheetIndex == 2) {
            // Sheet 2: O No. DATE OF RELEASED
            return [
                'No'                => $this->getValue($row, ['no']),
                'date_released'     => $dateReleased,
                'quarter'           => $quarter,
                'category'          => 'ONO',
                'addressed_to'      => $this->getValue($row, ['addresed_to', 'addressed_to']),
                'subject_of_letter' => substr($this->getValue($row, ['subject']), 0, 255),
                'remarks'           => $this->getValue($row, ['remarks']),
                'libcap_no'         => $this->getValue($row, ['libcap_#', 'libcap_no']),
                'status'            => $this->getValue($row, ['status']),
                'chedrix_2025'      => $this->getValue($row, ['chedrix_2025'], 'CHEDRIX-2025'),
                'o'                 => $this->getValue($row, ['o'], 'O'),
            ];
        } else {
            return [];
        }
    }

    /**
     * Create a new Outgoing model instance from the mapped row.
     */
    public function model(array $mappedRow)
    {
        Log::info('Creating Outgoing with mapped data: ' . json_encode($mappedRow));
        // Optionally, skip rows that appear empty.
        if (empty($mappedRow['No']) && empty($mappedRow['date_released'])) {
            Log::info('Skipping empty row.');
            return null;
        }
        return new Outgoing($mappedRow);
    }

    /**
     * Set the chunk size.
     */
    public function chunkSize(): int
    {
        return 1000;
    }

    /**
     * Return the sheets to be imported.
     * Each sheet gets its own instance with the proper sheet index.
     */
    public function sheets(): array
    {
        return [
            0 => (function () {
                $instance = new static;
                $instance->setSheetIndex(0);
                return $instance;
            })(),
            1 => (function () {
                $instance = new static;
                $instance->setSheetIndex(1);
                return $instance;
            })(),
            2 => (function () {
                $instance = new static;
                $instance->setSheetIndex(2);
                return $instance;
            })(),
        ];
    }
}
