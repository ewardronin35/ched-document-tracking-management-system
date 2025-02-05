<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SoMasterList extends Model
{
    use HasFactory;

    protected $table = 'so_master_lists'; // Specify the table name

    protected $fillable = [
        'hei_name',
        'hei_uii',
        'last_name',
        'first_name',
        'middle_name',
        'extension_name',
        'sex',
        'program_id',
        'major_id',
        'started',
        'ended',
        'academic_year',
        'date_of_application',
        'date_of_issuance',
        'registrar',
        'govt_permit_reco',
        'total',
        'semester',
        'date_of_graduation',
        'semester1_start',
        'semester1_end',
        'semester2_start',
        'semester2_end',
    ];

    // Relationships
    
    protected $casts = [
        'started' => 'date',
        'ended' => 'date',
        'date_of_application' => 'date',
        'date_of_issuance' => 'date',
        'date_of_graduation' => 'date',
        'semester1_start' => 'date',
        'semester1_end' => 'date',
        'semester2_start' => 'date',
        'semester2_end' => 'date',
        'semester' => 'integer',
        'total' => 'integer',
    ];
    public function program()
    {
        return $this->belongsTo(Programs::class);
    }

    public function major()
    {
        return $this->belongsTo(Majors::class);
    }
}
