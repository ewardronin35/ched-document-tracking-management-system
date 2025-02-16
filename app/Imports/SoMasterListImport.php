<?php

namespace App\Imports;

use App\Models\SoMasterList;
use App\Models\Programs;
use App\Models\Majors;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
// Add WithEvents to capture the sheet name:
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\BeforeSheet;
use Maatwebsite\Excel\Jobs\AfterImportJob;
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class SoMasterListImport implements ToModel, WithHeadingRow, WithEvents, ShouldQueue, WithChunkReading
{
    /**
     * The sheet name from which data is being imported.
     *
     * @var string|null
     */
    protected $sheetName;

    /**
     * Optionally, allow an external filter (if needed).
     *
     * @var string|null
     */
    protected $filterProgram;

    /**
     * Create a new import instance.
     *
     * @param string|null $filterProgram  If provided, only rows for this program (case-insensitive) will be imported.
     */
    public function __construct($filterProgram = null)
    {
        $this->filterProgram = $filterProgram;
    }

    /**
     * Register events to capture the sheet name.
     */
    public function registerEvents(): array
    {
        return [
            // BeforeSheet event lets us capture the sheet’s title.
            BeforeSheet::class => function(BeforeSheet $event) {
                // Store the sheet name in a property.
                $this->sheetName = $event->getSheet()->getDelegate()->getTitle();
            },
        ];
    }

    /**
     * Map a row from the Excel file to a SoMasterList model.
     *
     * @param array $row
     * @return SoMasterList|null
     */
    public function model(array $row)
    {
        // Trim all row values to remove any leading/trailing spaces.
        $row = array_map(fn($value) => is_string($value) ? trim($value) : $value, $row);
    
        // 1) MAP HEADERS: Convert CSV headers to expected database fields
        $mapped = [
            'hei_name'         => $row['hei name'] ?? null,    
            'hei_uii'          => $row['hei uii'] ?? null,     
            'last_name'        => $row['last name'] ?? null,
            'first_name'       => $row['first name'] ?? null,
            'middle_name'      => $row['middle name'] ?? null,
            'extension_name'   => $row['extension name (ii, iv, jr., sr., etc)'] ?? null,
            'sex'              => $row['sex (male/female)'] ?? null,
            'program'          => $row['program (please select program from dropdown menu)'] ?? null,
            'psced_code'       => $row['psced code'] ?? null,
            'major'            => $row['major (select major from dropdown menu)'] ?? null,
            'semester'         => $row['semester (select semester from dropdown menu)'] ?? null,
            'academic_year'    => $row['academic year (e.g. 2023-2024)'] ?? null,
            'total'            => $row['total'] ?? 0,
            'special_order_number' => $row['special order number'] ?? null,
            'processing_slip_number' => $row['processing slip number'] ?? null,
            'region'           => $row['region'] ?? null,
            'date_of_application' => $row['date of application (yyyy/mm/dd)'] ?? null,
            'date_of_issuance' => $row['date of issuance (yyyy/mm/dd)'] ?? null,
            'date_of_graduation' => $row['date of graduation (yyyy/mm/dd)'] ?? null,
            'started'          => $row['started'] ?? null,
            'ended'            => $row['ended'] ?? null,
            'registrar'        => $row['registrar'] ?? null,
            'govt_permit_reco' => $row['govt. permit/recognition'] ?? null,
            'signed_by'        => $row['signed by (approving authority)'] ?? null,
        ];
    
        // If a sheet name is being used, override the program field
        if ($this->sheetName) {
            $mapped['program'] = $this->sheetName;
        }
    
        // Skip rows that don't match a filterProgram (if applied)
        if ($this->filterProgram !== null &&
            strtolower($mapped['program']) !== strtolower($this->filterProgram)) {
            Log::info("Row skipped: sheet program '{$mapped['program']}' does not match filter '{$this->filterProgram}'.");
            return null;
        }
    
        // 2) VALIDATE FIELDS
        $validator = Validator::make($mapped, [
            'hei_name'      => 'required|string|max:255',
            'hei_uii'       => 'required|string|max:255',
            'last_name'     => 'required|string|max:255',
            'first_name'    => 'required|string|max:255',
            'sex'           => 'required|in:Male,Female,Other',
            'program'       => 'required|string|max:255',
            'psced_code'    => 'nullable|string|max:50',
            'major'         => 'required|string|max:255',
            'semester'      => 'nullable|string|max:50',
            'academic_year' => 'nullable|string|max:20',
        ]);
    
        if ($validator->fails()) {
            Log::error('Validation failed for row: ' . json_encode($row) . 
                       ' Errors: ' . json_encode($validator->errors()->all()));
            return null;
        }
    
        // 3) LOOKUP PROGRAM
        $program = Programs::where('name', $mapped['program'])->first();
        if (!$program) {
            Log::warning("Program not found: {$mapped['program']} for row: " . json_encode($row));
            return null; 
        }
    
        // 4) LOOKUP MAJOR
        $major = Majors::where('name', $mapped['major'])
                       ->where('program_id', $program->id)
                       ->first();
        if (!$major) {
            Log::warning("Major not found: {$mapped['major']} for Program: {$mapped['program']} in row: " . json_encode($row));
            return null; 
        }
    
        // 5) CONVERT DATES
        $started           = $this->convertDate($mapped['started']);
        $ended             = $this->convertDate($mapped['ended']);
        $dateOfApplication = $this->convertDate($mapped['date_of_application']);
        $dateOfIssuance    = $this->convertDate($mapped['date_of_issuance']);
        $dateOfGraduation  = $this->convertDate($mapped['date_of_graduation']);
    
        // 6) RETURN OBJECT FOR DATABASE INSERTION
        return new SoMasterList([
            'hei_name'             => $mapped['hei_name'],
            'hei_uii'              => $mapped['hei_uii'],
            'last_name'            => $mapped['last_name'],
            'first_name'           => $mapped['first_name'],
            'middle_name'          => $mapped['middle_name'],
            'extension_name'       => $mapped['extension_name'],
            'sex'                  => $mapped['sex'],
            'program_id'           => $program->id,
            'psced_code'           => $mapped['psced_code'],
            'major_id'             => $major->id,
            'started'              => $started,
            'ended'                => $ended,
            'academic_year'        => $mapped['academic_year'],
            'date_of_application'  => $dateOfApplication,
            'date_of_issuance'     => $dateOfIssuance,
            'registrar'            => $mapped['registrar'],
            'govt_permit_reco'     => $mapped['govt_permit_reco'],
            'signed_by'            => $mapped['signed_by'],
            'total'                => (float) ($mapped['total'] ?? 0),
            'semester'             => $mapped['semester'],
            'date_of_graduation'   => $dateOfGraduation,
            'semester1_start'      => null, 
            'semester1_end'        => null, 
            'semester2_start'      => null, 
            'semester2_end'        => null, 
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
            // Try Y/m/d format first.
            return \Carbon\Carbon::createFromFormat('Y/m/d', $date)->format('Y-m-d');
        } catch (\Exception $e) {
            try {
                // Then try Y-m-d format.
                return \Carbon\Carbon::createFromFormat('Y-m-d', $date)->format('Y-m-d');
            } catch (\Exception $e) {
                // Log invalid date formats and return null.
                Log::error('Invalid date format: ' . $date);
                return null;
            }
        }
    }
    public function chunkSize(): int
    {
        return 500;
    }
}
