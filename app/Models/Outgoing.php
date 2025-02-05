<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Outgoing extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'control_no',
        'date_released',
        'category',
        'addressed_to',
        'email',
        'subject_of_letter',
        'remarks',
        'libcap_no',
        'status',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'date_released' => 'date',
    ];
}
