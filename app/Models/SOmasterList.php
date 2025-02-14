<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SoMasterList extends Model
{
    use HasFactory;

    protected $table = 'so_master_lists'; // Make sure this matches your actual table name

    /**
     * Fillable columns to match your Excel structure.
     * Rename any column if it differs in your actual DB.
     */
    protected $fillable = [
        'status',
        'processing_slip_number',
        'region',
        'hei_name',
        'hei_uii',
        'special_order_number',
        'last_name',
        'first_name',
        'middle_name',
        'extension_name',
        'sex',
        'total',
        'program_id',
        'psced_code',
        'major_id',
        'started',
        'ended',
        'date_of_application',
        'date_of_issuance',
        'registrar',
        'govt_permit_recognition',   // renamed from govt_permit_reco
        'signed_by',                 // "Signed By (Approving Authority)"
        'semester',                  // first Semester
        'academic_year',             // first Academic Year
        'date_of_graduation',
        'semester2',                 // second Semester (if needed)
        'academic_year2',            // second Academic Year (if needed)
    ];

    /**
     * Cast columns to proper data types.
     */
    protected $casts = [
        'started'             => 'date',
        'ended'               => 'date',
        'date_of_application' => 'date',
        'date_of_issuance'    => 'date',
        'date_of_graduation'  => 'date',

        // If you store semester as an integer:
        'semester'  => 'string',
        'semester2' => 'string',

        'total' => 'integer',
    ];

    /**
     * Relationships
     */
    public function program()
    {
        return $this->belongsTo(Programs::class, 'program_id');
    }

    public function major()
    {
        return $this->belongsTo(Majors::class, 'major_id');
    }

    public function hei()
    {
        // If you have an 'hei_id' foreign key in your so_master_lists table, 
        // you can use that. Otherwise, adjust as needed.
        return $this->belongsTo(HEI::class, 'hei_id');
    }
}
