<?php

namespace App\Imports;

use App\Models\SoMasterList;
use App\Models\Programs;
use App\Models\Majors;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class SoMasterListImport implements ToModel, WithHeadingRow
{
    /**
     * Define the mapping of CSV columns to model attributes.
     *
     * @param array $row
     * @return SoMasterList|null
     */
    public function model(array $row)
    {
        // Trim all row values to remove any leading/trailing spaces
        $row = array_map('trim', $row);

        // Validate required fields
        $validator = Validator::make($row, [
            'hei_name' => 'required|string|max:255',
            'hei_uii' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'first_name' => 'required|string|max:255',
            'sex' => 'required|in:Male,Female,Other',
            'program' => 'required|string|max:255',
            'psced_code' => 'nullable|string|max:50',
            'major' => 'required|string|max:255',
            'semester' => 'nullable|string|max:50',
            'academic_year' => 'nullable|string|max:20',
        ]);

        if ($validator->fails()) {
            // Log the validation errors and skip this row
            Log::error('Validation failed for row: ' . json_encode($row) . ' Errors: ' . json_encode($validator->errors()->all()));
            return null;
        }

        // Lookup Program by name
        $program = Programs::where('name', $row['program'])->first();
        if (!$program) {
            Log::warning('Program not found: ' . $row['program'] . ' for row: ' . json_encode($row));
            return null; // Skip this row or handle accordingly
        }

        // Lookup Major by name within the fetched Program
        $major = Majors::where('name', $row['major'])->where('program_id', $program->id)->first();
        if (!$major) {
            Log::warning('Major not found: ' . $row['major'] . ' for Program: ' . $row['program'] . ' in row: ' . json_encode($row));
            return null; // Skip this row or handle accordingly
        }

        // Convert dates to Y-m-d format using Carbon
        $started = $this->convertDate($row['started']);
        $ended = $this->convertDate($row['ended']);
        $dateOfApplication = $this->convertDate($row['date_of_application']);
        $dateOfIssuance = $this->convertDate($row['date_of_issuance']);
        $dateOfGraduation = $this->convertDate($row['date_of_graduation']);

        return new SoMasterList([
            'hei_name' => $row['hei_name'],
            'hei_uii' => $row['hei_uii'],
            'last_name' => $row['last_name'],
            'first_name' => $row['first_name'],
            'middle_name' => $row['middle_name'] ?? null,
            'extension_name' => $row['extension_name'] ?? null,
            'sex' => $row['sex'],
            'program_id' => $program->id,
            'psced_code' => $row['psced_code'] ?? null,
            'major_id' => $major->id,
            'started' => $started,
            'ended' => $ended,
            'academic_year' => $row['academic_year'] ?? null,
            'date_of_application' => $dateOfApplication,
            'date_of_issuance' => $dateOfIssuance,
            'registrar' => $row['registrar'] ?? null,
            'govt_permit_reco' => $row['govt_permit_reco'] ?? null,
            'total' => isset($row['total']) ? (float)$row['total'] : 0,
            'semester' => $row['semester'] ?? null,
            'date_of_graduation' => $dateOfGraduation,
            'semester1_start' => null, // Not provided in CSV
            'semester1_end' => null,   // Not provided in CSV
            'semester2_start' => null, // Not provided in CSV
            'semester2_end' => null,   // Not provided in CSV
        ]);
    }

    /**
     * Convert various date formats to Y-m-d.
     *
     * @param string|null $date
     * @return string|null
     */
    private function convertDate($date)
    {
        if (!$date) {
            return null;
        }

        try {
            // Attempt to create a Carbon instance with multiple formats
            return \Carbon\Carbon::createFromFormat('Y/m/d', $date)->format('Y-m-d');
        } catch (\Exception $e) {
            try {
                return \Carbon\Carbon::createFromFormat('Y-m-d', $date)->format('Y-m-d');
            } catch (\Exception $e) {
                // Log invalid date formats
                Log::error('Invalid date format: ' . $date);
                return null;
            }
        }
    }
}
