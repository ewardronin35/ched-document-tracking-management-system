<?php

namespace App\Imports;

use App\Models\Incoming;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow; 
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Illuminate\Contracts\Queue\ShouldQueue;

class IncomingImport implements ToModel, WithHeadingRow, WithChunkReading, ShouldQueue
{
    /**
     * Normalize a CSV header.
     *
     * Converts the header to lowercase, trims spaces, replaces spaces,
     * slashes, and periods with underscores, and removes duplicate underscores.
     *
     * @param string $header
     * @return string
     */
    protected function normalizeHeader(string $header): string
    {
        $header = strtolower(trim($header));
        // Replace spaces, slashes and periods with underscores
        $header = str_replace([' ', '/', '.'], '_', $header);
        // Replace multiple underscores with a single underscore
        $header = preg_replace('/_+/', '_', $header);
        // Trim underscores from the beginning and end
        return trim($header, '_');
    }

    /**
     * Normalize the entire row keys.
     *
     * @param array $row
     * @return array
     */
    protected function normalizeRow(array $row): array
    {
        $normalized = [];
        foreach ($row as $key => $value) {
            $normKey = $this->normalizeHeader($key);
            // Skip keys that end up empty (if the header was blank)
            if (!empty($normKey)) {
                $normalized[$normKey] = $value;
            }
        }
        return $normalized;
    }

    /**
     * Process each row from the file and create an Incoming model.
     *
     * @param array $row
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        // Normalize row keys
        $row = $this->normalizeRow($row);

        // Debug: Uncomment the following line to inspect the normalized row (only for testing)
        // dd($row);

        // Skip the row if key columns appear empty.
        if (
            empty($row['date']) &&
            empty($row['time_emailed']) &&
            empty($row['sender']) &&
            empty($row['subject'])
        ) {
            return null;
        }

        // Convert dates if provided.
        $dateReceived = !empty($row['date']) ? Carbon::parse($row['date']) : null;
        $dateTimeRouted = !empty($row['date_time_routed']) ? Carbon::parse($row['date_time_routed']) : null;

        // Determine quarter.
        // If there is a column "q1-jan-feb-mar", assume quarter 1.
        $quarter = null;
        if (!empty($row['q1-jan-feb-mar'])) {
            $quarter = 1;
        } elseif ($dateReceived) {
            $month = $dateReceived->month;
            $quarter = intdiv($month - 1, 3) + 1;
        }

        return new Incoming([
            // Adjust the keys to match your CSV headers after normalization.
            'reference_number'  => $row['reference_number'] ?? null,
            'date_received'     => $dateReceived,
            'time_emailed'      => $row['time_emailed'] ?? null,
            'sender_name'       => $row['sender'] ?? null,
            'sender_email'      => $row['email_address_of_sender'] ?? null,
            'subject'           => $row['subject'] ?? null,
            'remarks'           => $row['remarks_rp'] ?? null,  // "REMARKS / RP" becomes remarks_rp
            'date_time_routed'  => $dateTimeRouted,
            'routed_to'         => $row['route_to_attendee'] ?? null, // from "ROUTE TO / ATTENDEE"
            'date_acted_by_es'  => !empty($row['date_acted_by_es']) ? Carbon::parse($row['date_acted_by_es']) : null,
            'outgoing_details'  => $row['outgoing_details'] ?? null,
            'year'              => isset($row['year']) ? (int)$row['year'] : null,
            'outgoing_id'       => $row['outgoing_id'] ?? null,
            'date_released'     => !empty($row['date_released']) ? Carbon::parse($row['date_released']) : null,
            'chedrix_2025'      => $row['chedrix-2025'] ?? 'CHEDRIX-2025',
            'location'          => $row['location'] ?? null,
            'No'                => $row['no'] ?? null,
            'quarter'           => $row['quarter'] ?? $quarter,
        ]);
    }

    /**
     * Define the chunk size (number of rows per chunk).
     *
     * @return int
     */
    public function chunkSize(): int
    {
        return 1000;
    }
}
