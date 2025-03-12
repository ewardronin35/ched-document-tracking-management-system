<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CavLocal extends Model
{
    protected $table = 'cav_locals';
    protected $fillable = [
        'quarter', 'cav_no', 'region', 'surname', 'first_name', 'extension_name', 'middle_name', 'sex',
        'institution_code', 'full_name_of_hei', 'address_of_hei', 'official_receipt_number', 'type_of_heis',
        'discipline_code', 'program_name', 'major', 'program_level', 'status_of_the_program', 'date_started',
        'date_ended', 'graduation_date', 'units_earned', 'special_order_no', 'series', 'date_applied',
        'date_released', 'airway_bill_no', 'serial_number_of_security_paper', 'purpose_of_cav', 'target_country',
    ];
}