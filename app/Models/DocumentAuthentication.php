<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentAuthentication extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     */
    protected $table = 'document_authentications';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'quarter',
        'No',
        'surname',
        'first_name',
        'extension_name',
        'middle_name',
        'sex',
        'or_number',
        'name_of_heis',
        'program_name',
        'major',
        'date_started',
        'date_ended',
        'year_graduated',
        'units_earned',
        'purpose',
        'no_of_pcs',
        'special_order',
        'date_applied',
        'date_released',
    ];
}
