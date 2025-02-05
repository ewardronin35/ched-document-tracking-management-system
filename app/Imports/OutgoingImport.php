<?php

namespace App\Imports;

use App\Models\Outgoing;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithValidation;

class OutgoingImport implements ToModel, WithHeadingRow, WithChunkReading, WithValidation
{
    /**
     * Define how each row should be imported into the Outgoing model.
     *
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        return new Outgoing([
            'control_no' => $row['control_no'],
            'date_released' => $row['date_released'],
            'category' => $row['category'],
            'addressed_to' => $row['addressed_to'],
            'email' => $row['email'],
            'subject_of_letter' => $row['subject_of_letter'],
            'remarks' => $row['remarks'],
            'libcap_no' => $row['libcap_no'],
            'status' => $row['status'],
        ]);
    }

    /**
     * Define the chunk size for chunk reading.
     *
     * @return int
     */
    public function chunkSize(): int
    {
        return 1000;
    }

    /**
     * Define validation rules for each row.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'control_no' => 'required|string|unique:outgoings,control_no',
            'date_released' => 'required|date',
            'category' => 'required|string',
            'addressed_to' => 'required|string',
            'email' => 'required|email',
            'subject_of_letter' => 'required|string',
            'status' => 'required|in:Pending,In Progress,Completed,Rejected',
            // Add more validation rules as needed
        ];
    }

    /**
     * Optional: Customize how failures are handled.
     */
    // public function onFailure(Failure ...$failures)
    // {
    //     // Handle the failures how you'd like.
    // }
}
