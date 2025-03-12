<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SoMasterList extends Model
{
    use HasFactory;

    protected $table = 'so_master_lists'; // Ensure this matches your table name exactly

    /**
     * Fillable columns: storing program and major as strings.
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
        'program',  
        'psced_code',        // Now storing the program name directly
        'major',         // Now storing the major name directly
        'started',
        'ended',
        'date_of_application',
        'date_of_issuance',
        'registrar',
        'govt_permit_recognition',   // previously govt_permit_reco
        'signed_by',                   // "Signed By (Approving Authority)"
        'semester',                    // first Semester
        'academic_year',               // first Academic Year
        'date_of_graduation',
        'semester2',                   // second Semester (if needed)
        'academic_year2',              // second Academic Year (if needed)
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

        'total'               => 'integer',
    ];

    
      
  
}
