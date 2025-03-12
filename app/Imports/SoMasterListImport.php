<?php

namespace App\Imports;

use App\Models\SoMasterList;
use App\Models\Programs;
use App\Models\Majors;
use App\Models\HEI;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\BeforeSheet;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;
use Carbon\Carbon;

class SoMasterListImport implements 
    ToModel, 
    WithHeadingRow, 
    WithEvents, 
    WithChunkReading, 
    WithCalculatedFormulas
{
    protected $sheetName;
    protected $filterProgram;
    protected $skipSheet = false;
    protected $headers = [];

    public function __construct($filterProgram = null)
    {
        $this->filterProgram = $filterProgram;
        Log::info("SoMasterListImport initiated. Filter: " . ($filterProgram ?? 'None'));
    }

    /**
     * Configure the heading row to be none, which means we'll handle headers ourselves
     */
    public function headingRow(): int
    {
        return 1;
    }

    /**
     * Register events for the import process
     */
    public function registerEvents(): array
    {
        return [
            BeforeSheet::class => function (BeforeSheet $event) {
                $sheetDelegate = $event->getSheet()->getDelegate();
                if ($sheetDelegate instanceof \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet) {
                    $this->sheetName = $sheetDelegate->getTitle();
                    $highestRow = $sheetDelegate->getHighestRow();
                    $highestColumn = $sheetDelegate->getHighestColumn();
                    
                    // Get the actual headers from the first row
                    if ($highestRow >= 1) {
                        $headerRow = $sheetDelegate->rangeToArray('A1:' . $highestColumn . '1', null, true, false)[0];
                        $this->headers = array_map('trim', array_filter($headerRow, function($header) {
                            return !is_null($header) && trim($header) !== '';
                        }));
                        Log::debug("Sheet headers:", $this->headers);
                    }
                    
                    Log::info("Processing sheet: {$this->sheetName} with {$highestRow} rows.");
                } else {
                    $this->sheetName = 'CSVSheet';
                    $highestRow = 1;
                    Log::info("Processing CSV file, setting sheet name as CSVSheet.");
                }
                
                if ($highestRow <= ($this->headingRow() + 1)) {
                    $this->skipSheet = true;
                    Log::info("Skipping sheet '{$this->sheetName}' because it appears empty.");
                }
            },
        ];
    }

    /**
     * Process each row from the import
     */
    public function model(array $row)
    {
        if ($this->skipSheet) {
            Log::info("Skipped processing row as sheet '{$this->sheetName}' is marked empty.");
            return null;
        }
        
        // Debug the row structure before processing
        Log::debug('Processing row with keys:', array_keys($row));
        
        // Skip completely empty rows
        if (!array_filter($row, fn($value) => !is_null($value) && (is_string($value) ? trim($value) !== '' : true))) {
            Log::debug("Skipped completely empty row");
            return null;
        }
    
        // Normalize row keys by looking for the expected content rather than exact key matches
        // This handles cases where Excel may change the header format
        $normalizedRow = $this->normalizeRowKeys($row);
        
        // Check if this appears to be a header row
        if ($this->isHeaderRow($normalizedRow)) {
            Log::info("Skipping header row: " . json_encode(array_keys($normalizedRow)));
            return null;
        }
        
        // Map the normalized row to our expected structure
        $mapped = $this->mapRowToFields($normalizedRow);
        
        // Validate the mapped data
        $validator = Validator::make($mapped, [
            'status'                  => 'nullable|string|max:255',
            'processing_slip_number'  => 'nullable|string|max:255',
            'region'                  => 'nullable|string|max:255',
            'hei_name'                => 'nullable|string|max:255',
            'last_name'               => 'nullable|string|max:255',
            'first_name'              => 'nullable|string|max:255',
            'middle_name'             => 'nullable|string|max:255',
            'extension_name'          => 'nullable|string|max:255',
            'sex'                     => 'nullable|string|max:50',
            'program'                 => 'nullable|string|max:255',
            'major'                   => 'nullable|string|max:255',
            'semester'                => 'nullable|string|max:50',
            'academic_year'           => 'nullable|string|max:20',
            'date_of_application'     => 'nullable',
            'date_of_issuance'        => 'nullable',
            'registrar'               => 'nullable|string|max:255',
            'govt_permit_recognition' => 'nullable|string|max:255',
            'signed_by'               => 'nullable|string|max:255',
            'semester2'               => 'nullable|string|max:50',
            'academic_year2'          => 'nullable|string|max:20',
        ]);
    
        if ($validator->fails()) {
            Log::error(
                'Validation failed for row. Errors: ' . json_encode($validator->errors()->all())
            );
            return null;
        }
        
        // Only process rows with essential data
        if (empty($mapped['hei_name']) || empty($mapped['last_name']) || empty($mapped['first_name'])) {
            Log::debug("Skipping row with missing essential data");
            return null;
        }
    
        // Look up HEI to get the UII if available
        if (!empty($mapped['hei_name'])) {
            $hei = HEI::where('HEIs', $mapped['hei_name'])->first();
            if ($hei) {
                $mapped['hei_uii'] = $hei->UII;
            } else {
                Log::warning("HEI not found for name: {$mapped['hei_name']}");
                $mapped['hei_uii'] = null;
            }
        }
    
        // Convert date fields
        $mapped['started'] = $this->convertDate($mapped['started']);
        $mapped['ended'] = $this->convertDate($mapped['ended']);
        $mapped['semester'] = $mapped['semester'];  // Keep as string
        $mapped['academic_year'] = $mapped['academic_year'];  // Keep as string
        $mapped['semester2'] = $mapped['semester2'];  // Keep as string
        $mapped['academic_year2'] = $mapped['academic_year2'];  // Keep as string
        $mapped['date_of_application'] = $this->convertDate($mapped['date_of_application']);
        $mapped['date_of_issuance'] = $this->convertDate($mapped['date_of_issuance']);
        $mapped['date_of_graduation'] = $this->convertDate($mapped['date_of_graduation']);
    
        // Successfully created a normalized row, log it
        Log::info("Successfully mapped row for: {$mapped['last_name']}, {$mapped['first_name']}");
        
        return new SoMasterList([
            'status'                  => $mapped['status'],
            'processing_slip_number'  => $mapped['processing_slip_number'],
            'region'                  => $mapped['region'],
            'hei_name'                => $mapped['hei_name'],
            'hei_uii'                 => $mapped['hei_uii'],
            'special_order_number'    => $mapped['special_order_number'],
            'last_name'               => $mapped['last_name'],
            'first_name'              => $mapped['first_name'],
            'middle_name'             => $mapped['middle_name'],
            'extension_name'          => $mapped['extension_name'],
            'sex'                     => $mapped['sex'],
            'total'                   => $mapped['total'],
            'program'                 => $mapped['program'],
            'major'                   => $mapped['major'],
            'started'                 => $mapped['started'],
            'ended'                   => $mapped['ended'],
            'date_of_application'     => $mapped['date_of_application'],
            'date_of_issuance'        => $mapped['date_of_issuance'],
            'registrar'               => $mapped['registrar'],
            'govt_permit_recognition' => $mapped['govt_permit_recognition'],
            'signed_by'               => $mapped['signed_by'],
            'semester'                => $mapped['semester'],
            'academic_year'           => $mapped['academic_year'],
            'date_of_graduation'      => $mapped['date_of_graduation'],
            'semester2'               => $mapped['semester2'],
            'academic_year2'          => $mapped['academic_year2'],
        ]);
    }
    
    /**
     * Normalize row keys by looking for expected content in any key
     */
    private function normalizeRowKeys(array $row)
    {
        $normalized = [];
        $keyMappings = [
            'status' => ['status'],
            'processing_slip_number' => ['processing_slip_number', 'processing slip number', 'slip number', 'slip'],
            'region' => ['region'],
            'hei_name' => ['hei_name', 'hei name', 'hei'],
            'special_order_number' => ['special_order_number', 'special order number', 'order number', 'order'],
            'last_name' => ['last_name', 'last name', 'surname', 'family name'],
            'first_name' => ['first_name', 'first name', 'given name'],
            'middle_name' => ['middle_name', 'middle name'],
            'extension_name' => ['extension_name', 'extension name', 'extension_name_ii_iv_jr_sr_etc', 'extension'],
            'sex' => ['sex', 'sex_malefemale', 'gender'],
            'total' => ['total'],
            'program' => ['program', 'program_please_select_program_from_dropdown_menu'],
            'major' => ['major', 'major_select_major_from_dropdown_menu'],
            'started' => ['started'],
            'ended' => ['ended'],
            'date_of_application' => ['date_of_application', 'date of application', 'application date', 'date_of_application_yyyymmdd'],
            'date_of_issuance' => ['date_of_issuance', 'date of issuance', 'issuance date', 'date_of_issuance_yyyymmdd'],
            'registrar' => ['registrar'],
            'govt_permit_recognition' => ['govt_permit_recognition', 'govt_permitrecognition', 'permit', 'recognition'],
            'signed_by' => ['signed_by', 'signed by', 'approving authority', 'signed_by_approving_authority'],
            'semester' => ['semester'],
            'academic_year' => ['academic_year', 'academic year'],
            'date_of_graduation' => ['date_of_graduation', 'date of graduation', 'graduation date', 'date_of_graduation_yyyymmdd'],
            'semester2' => ['semester2', 'semester 2'],
            'academic_year2' => ['academic_year2', 'academic year 2', 'academic_year_2'],
        ];
        
        // First try strict matches with our known key mappings
        foreach ($keyMappings as $normalKey => $possibleKeys) {
            foreach ($possibleKeys as $possibleKey) {
                if (isset($row[$possibleKey])) {
                    $normalized[$normalKey] = $row[$possibleKey];
                    break;
                }
            }
        }
        
        // For remaining numeric or unknown keys, try to match by partial text in the header
        foreach ($row as $key => $value) {
            if (is_numeric($key) || !isset($normalized[$key])) {
                // For numeric keys, try to infer what they are based on content
                $assigned = false;
                
                // Try to find a match in our key mappings based on header content
                if (isset($this->headers[$key]) && is_string($this->headers[$key])) {
                    $header = strtolower($this->headers[$key]);
                    foreach ($keyMappings as $normalKey => $possibleKeys) {
                        foreach ($possibleKeys as $possibleKey) {
                            if (strpos($header, strtolower(str_replace('_', ' ', $possibleKey))) !== false) {
                                $normalized[$normalKey] = $value;
                                $assigned = true;
                                break 2;
                            }
                        }
                    }
                }
                
                // If we couldn't assign it, keep it with original key for debugging
                if (!$assigned) {
                    $normalized["unknown_{$key}"] = $value;
                }
            }
        }
        
        return $normalized;
    }
    
    /**
     * Check if a row appears to be a header row rather than data
     */
    private function isHeaderRow(array $row)
    {
        // Check common header indicators
        $headerWords = ['status', 'name', 'sex', 'date', 'semester', 'year', 'number', 'region'];
        $matches = 0;
        
        // Count how many header-like words appear in the row values
        foreach ($row as $key => $value) {
            if (is_string($value)) {
                foreach ($headerWords as $headerWord) {
                    if (stripos($value, $headerWord) !== false) {
                        $matches++;
                        break;
                    }
                }
            }
        }
        
        // If several matches found, this is likely a header row
        return $matches >= 3;
    }
    
    /**
     * Map the normalized row to our expected field structure
     */
    private function mapRowToFields(array $normalizedRow)
    {
        return [
            'status'                  => $normalizedRow['status'] ?? null,
            'processing_slip_number'  => $normalizedRow['processing_slip_number'] ?? null,
            'region'                  => $normalizedRow['region'] ?? null,
            'hei_name'                => $normalizedRow['hei_name'] ?? null,
            'hei_uii'                 => null, // Will be filled via lookup
            'special_order_number'    => $normalizedRow['special_order_number'] ?? null,
            'last_name'               => $normalizedRow['last_name'] ?? null,
            'first_name'              => $normalizedRow['first_name'] ?? null,
            'middle_name'             => $normalizedRow['middle_name'] ?? null,
            'extension_name'          => $normalizedRow['extension_name'] ?? null,
            'sex'                     => $normalizedRow['sex'] ?? null,
            'total'                   => $normalizedRow['total'] ?? 0,
            'program'                 => $normalizedRow['program'] ?? null,
            'major'                   => $normalizedRow['major'] ?? null,
            'started'                 => $normalizedRow['started'] ?? null,
            'ended'                   => $normalizedRow['ended'] ?? null,
            'date_of_application'     => $normalizedRow['date_of_application'] ?? null,
            'date_of_issuance'        => $normalizedRow['date_of_issuance'] ?? null,
            'registrar'               => $normalizedRow['registrar'] ?? null,
            'govt_permit_recognition' => $normalizedRow['govt_permit_recognition'] ?? null,
            'signed_by'               => $normalizedRow['signed_by'] ?? null,
            'semester'                => $normalizedRow['semester'] ?? null,
            'academic_year'           => $normalizedRow['academic_year'] ?? null,
            'date_of_graduation'      => $normalizedRow['date_of_graduation'] ?? null,
            'semester2'               => $normalizedRow['semester2'] ?? null,
            'academic_year2'          => $normalizedRow['academic_year2'] ?? null,
        ];
    }
    
    /**
     * Convert various date formats to Y-m-d
     */
    private function convertDate($date)
    {
        if (empty($date)) {
            return null;
        }
        
        // If it's already a date object, format it
        if ($date instanceof \DateTime || $date instanceof Carbon) {
            return $date->format('Y-m-d');
        }
        
        // If it's a numeric value (Excel date), convert it
        if (is_numeric($date)) {
            try {
                // Excel dates are days since 1900-01-01 (or 1904-01-01 for Mac)
                // We'll assume the Windows 1900 date system
                return Carbon::createFromDate(1900, 1, 1)->addDays((int)$date - 2)->format('Y-m-d');
            } catch (\Exception $e) {
                Log::error("Failed to convert numeric date {$date}: " . $e->getMessage());
                return null;
            }
        }
        
        // Handle string dates
        if (is_string($date)) {
            try {
                return Carbon::parse($date)->format('Y-m-d');
            } catch (\Exception $e) {
                Log::error("Invalid date format: {$date}");
                return null;
            }
        }
        
        return null;
    }

    /**
     * Set chunk size for batch processing
     */
    public function chunkSize(): int
    {
        return 1000;
    }

    /**
     * Set batch size for database operations
     */
    public function batchSize(): int
    {
        return 1000;
    }
}