<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Outgoing extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'outgoings';
    protected $appends = ['quarter_label', 'no_formatted'];

    protected $fillable = [
        'No',
        'date_released',
        'category',
        'quarter',
        'addressed_to',
        'email',
        'subject_of_letter',
        'remarks',
        'libcap_no',
        'status',
        'chedrix_2025',          // New Column
        'o',                      // New Column
        'incoming_id',  
        'sub_aro_2024_30',
        'travel_date',
        'es_in_charge', // Add this field to mass assignable if needed
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'date_received'     => 'datetime:Y-m-d', // or 'date'
        'time_emailed'      => 'datetime:H:i:s',
        'date_time_routed'  => 'datetime',
        'date_acted_by_es'  => 'datetime',
        'date_released'     => 'date',
        'travel_date'       => 'date',

    ];
    public function incoming()
    {
        return $this->belongsTo(Incoming::class);
    }
    public function getDateReleasedAttribute($value)
{
    // If the value is "0000-00-00" or empty, return null; otherwise, format it.
    if (!$value || $value === '0000-00-00') {
        return null;
    }
    return Carbon::parse($value)->format('Y-m-d');
}

    public function getNoFormattedAttribute()
    {
        // Get the stored value from the DB (the column name is "No")
        // If it exists and is not zero, cast it to an integer.
        $number = (int) $this->getAttribute('No');
        // If the value is zero (or null), use the model’s id
        if ($number === 0) {
            $number = $this->id;
        }
        return str_pad($number, 4, '0', STR_PAD_LEFT);
    }
    
    public function getQuarterLabelAttribute()
    {
        if (!$this->date_released) {
            return 'Unknown Quarter';
        }
    
        $month = Carbon::parse($this->date_released)->month;
    
        switch (true) {
            case in_array($month, [1, 2, 3]):
                return 'Q1 JAN-FEB-MAR';
            case in_array($month, [4, 5, 6]):
                return 'Q2 APR-MAY-JUN';
            case in_array($month, [7, 8, 9]):
                return 'Q3 JUL-AUG-SEP';
            case in_array($month, [10, 11, 12]):
                return 'Q4 OCT-NOV-DEC';
            default:
                return 'Unknown Quarter';
        }
    }
    
}
