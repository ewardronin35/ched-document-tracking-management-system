<?php

namespace App\Imports;

use App\Models\Incoming;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow; 
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;

class IncomingImport implements ToModel, WithHeadingRow, WithMapping, WithChunkReading, ShouldQueue
{
    /**
     * Specify which row in your Excel file contains the headers.
     * (Change this if your header row is not in row 2.)
     */
    public function headingRow(): int
    {
        return 2;
    }

    /**
     * Normalize a CSV header.
     */
    protected function normalizeHeader(string $header): string
    {
        $header = strtolower(trim($header));
        // Replace spaces, slashes, and periods with underscores
        $header = str_replace([' ', '/', '.'], '_', $header);
        // Replace multiple underscores with a single underscore
        $header = preg_replace('/_+/', '_', $header);
        return trim($header, '_');
    }

    /**
     * Normalize the entire row keys.
     */
    protected function normalizeRow(array $row): array
    {
        $normalized = [];
        foreach ($row as $key => $value) {
            // If $key is numeric, simply keep the value
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
     * Helper to check a list of possible keys and return the first found value.
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
     * Helper method to parse a date from Excel.
     * If the value is numeric, it converts it from an Excel serial number.
     */
    protected function parseDate($value)
    {
        if (empty($value)) {
            return null;
        }
        if (is_numeric($value)) {
            try {
                // Convert the Excel serial to a DateTime object and wrap it in Carbon
                return Carbon::instance(ExcelDate::excelToDateTimeObject($value));
            } catch (\Exception $e) {
                Log::error("Error converting Excel date: " . $e->getMessage());
                return null;
            }
        }
        return Carbon::parse($value);
    }

    /**
     * Helper method to parse a time from Excel.
     * If the value is numeric, it converts the Excel time (a fraction of a day)
     * to a DateTime object and formats it in 24-hour format (e.g., "08:00:00").
     */
    protected function parseTime($value)
    {
        if (empty($value)) {
            return null;
        }
        if (is_numeric($value)) {
            try {
                // Convert the Excel time to a DateTime object.
                $dateTime = ExcelDate::excelToDateTimeObject($value);
                // Format the time as 24-hour time (e.g., "08:00:00")
                return Carbon::instance($dateTime)->format('H:i:s');
            } catch (\Exception $e) {
                Log::error("Error converting Excel time: " . $e->getMessage());
                return null;
            }
        }
        // For non-numeric values, try parsing and formatting them in 24-hour format.
        try {
            return Carbon::parse($value)->format('H:i:s');
        } catch (\Exception $e) {
            Log::error("Error parsing time: " . $e->getMessage());
            return $value;
        }
    }

    /**
     * Map each row from the file to an array with the keys your Incoming model expects.
     */
    public function map($row): array
    {
        // Normalize row keys (this will use the header row from the file)
        $row = $this->normalizeRow($row);

        // Determine quarter.
        $quarter = $this->getValue($row, ['quarter']);
        if (!$quarter && isset($row['q1-jan_feb_mar'])) {
            $quarter = 1;
        }
        if (!$quarter && !empty($row['date'])) {
            $date = $this->parseDate($row['date']);
            $quarter = intdiv($date->month - 1, 3) + 1;
        }

        // Parse date fields using the helper method.
        $dateReceived   = $this->parseDate($this->getValue($row, ['date']));
        $dateTimeRouted = $this->parseDate($this->getValue($row, ['date_time_routed']));
        $dateActedByEs  = $this->parseDate($this->getValue($row, ['date_acted_by_es']));
        $dateReleased   = $this->parseDate($this->getValue($row, ['date_released']));

        return [
            'reference_number'  => $this->getValue($row, ['reference_number', 'ref_no']),
            'date_received'     => $dateReceived,  // comes from the "DATE" column
            // Use the parseTime() helper for the "TIME EMAILED" column.
            'time_emailed'      => $this->parseTime($this->getValue($row, ['time_emailed'])),
            'sender_name'       => $this->getValue($row, ['sender_name', 'sender']),
            'sender_email'      => $this->getValue($row, ['sender_email', 'email_address_of_sender']),
            'subject'           => $this->getValue($row, ['subject']),
            'remarks'           => $this->getValue($row, ['remarks', 'remarks_rp']),
            'date_time_routed'  => $dateTimeRouted,
            'routed_to'         => $this->getValue($row, ['routed_to', 'route_to_attendee']),
            'date_acted_by_es'  => $dateActedByEs,
            'outgoing_details'  => $this->getValue($row, ['outgoing_details']),
            'year'              => (int)$this->getValue($row, ['year'], null),
            'outgoing_id'       => $this->getValue($row, ['outgoing_id']),
            'date_released'     => $dateReleased,
            'chedrix_2025'      => $this->getValue($row, ['chedrix_2025', 'chedrix-2025'], 'CHEDRIX-2025'),
            'location'          => $this->getValue($row, ['location']),
            'No'                => $this->getValue($row, ['no']),
            'quarter'           => $quarter,
        ];
    }

    /**
     * Create a new Incoming model using the mapped row.
     */
    public function model(array $mappedRow)
    {
        // Log the mapped data to debug what is being imported.
        Log::info('Mapped row data: ' . json_encode($mappedRow));

        // If any of these required fields are empty, skip the row.
        if (
            empty($mappedRow['date_received']) ||
            empty($mappedRow['time_emailed']) ||
            empty($mappedRow['sender_name']) ||
            empty($mappedRow['subject'])
        ) {
            Log::info('Row skipped due to empty required fields in mapped data.');
            return null;
        }

        Log::info('Creating Incoming with mapped data: ' . json_encode($mappedRow));
        return new Incoming($mappedRow);
    }

    /**
     * Define the chunk size (number of rows per chunk).
     */
    public function chunkSize(): int
    {
        return 1000;
    }
}
