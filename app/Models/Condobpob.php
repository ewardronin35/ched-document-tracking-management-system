<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Condobpob extends Model
{
    //
    protected $table = 'condobpobs';

    protected $fillable = [
        'quarter',
        'No',
        'surname',
        'first_name',
        'extension_name',
        'middle_name',
        'sex',
        'or_number',
        'name_of_hei',
        'special_order_no',
        'type_of_correction',
        'from_date',
        'to_date',
        'date_applied',
        'date_released',
    ];
}
