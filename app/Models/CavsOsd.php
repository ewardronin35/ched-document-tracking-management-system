<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CavsOsd extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     */
    protected $table = 'cavs_osd';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'quarter',
        'o',
        'seq',
        'cav_osds',
        'surname',
        'first_name',
        'extension_name',
        'middle_name',
        'sex',
        'institution_code',
        'full_name_of_hei',
        'address_of_hei',
        'type_of_heis',
        'discipline_code',
        'program_name',
        'major',
        'program_level',
        'status_of_the_program',
        'date_started',
        'semester1',
        'semester2',
        'date_ended',
        'graduation_date',
        'units_earned',
        'special_order_no',
        'date_applied',
        'date_released',
        'purpose_of_cav',
        'target_country',
        'semester',
        'academic_year',
    ];
}
