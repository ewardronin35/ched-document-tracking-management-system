<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class HEI extends Model
{
    use HasFactory;

    protected $table = 'HEIs'; // Match your table name exactly

    protected $fillable = [
        'Region', // Use exact column names from your table
        'HEIs',
        'UII',
    ];
}    
