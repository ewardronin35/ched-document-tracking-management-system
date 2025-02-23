<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Certification extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     */
    protected $table = 'certifications';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'quarter',
        'o_prefix',
        'cav_no',
        'certification_type',
        'surname',
        'first_name',
        'extension_name',
        'middle_name',
        'full_name_of_hei',
        'program_name',
        'major',
        'date_of_entry',
        'date_ended',
        'year_graduated',
        'so_no',
        'or_no',
        'date_applied',
        'date_released',
        'remarks',
    ];
}
