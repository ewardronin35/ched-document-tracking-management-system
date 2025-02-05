<?php

namespace App\Imports;

use App\Models\Incoming;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class IncomingImport implements ToModel, WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        return new Incoming([
            'reference_number'  => $row['reference_number'] ?? null,
            'date_received'     => isset($row['date_received']) ? Carbon::parse($row['date_received']) : null,
            'time_emailed'      => isset($row['time_emailed']) ? Carbon::parse($row['time_emailed']) : null,
            'sender_name'       => $row['sender_name'] ?? null,
            'sender_email'      => $row['sender_email'] ?? null,
            'subject'           => $row['subject'] ?? null,
            'remarks'           => $row['remarks'] ?? null,
            'date_time_routed'  => isset($row['date_time_routed']) ? Carbon::parse($row['date_time_routed']) : null,
            'routed_to'         => $row['routed_to'] ?? null,
            'date_acted_by_es'  => isset($row['date_acted_by_es']) ? Carbon::parse($row['date_acted_by_es']) : null,
            'outgoing_details'  => $row['outgoing_details'] ?? null,
            'q1'                => isset($row['q1']) ? filter_var($row['q1'], FILTER_VALIDATE_BOOLEAN) : false,
            'q2'                => isset($row['q2']) ? filter_var($row['q2'], FILTER_VALIDATE_BOOLEAN) : false,
            'q3'                => isset($row['q3']) ? filter_var($row['q3'], FILTER_VALIDATE_BOOLEAN) : false,
            'q4'                => isset($row['q4']) ? filter_var($row['q4'], FILTER_VALIDATE_BOOLEAN) : false,
            'year'              => $row['year'] ?? null,
        ]);
    }
}
