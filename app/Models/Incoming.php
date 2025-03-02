<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
class Incoming extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     * 
     * Since the table name is 'incoming' and Laravel expects plural table names by default,
     * it's good practice to specify the table name explicitly.
     *
     * @var string
     */

     
    protected $table = 'incoming';

    /**
     * The attributes that are mass assignable.
     *
     * These fields can be filled via mass assignment, such as when using
     * Incoming::create($request->all()).
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'reference_number',
        'date_received',
        'time_emailed',
        'sender_name',
        'sender_email',
        'subject',
        'remarks',
        'date_time_routed',
        'routed_to',
        'date_acted_by_es',
        'outgoing_details',
        'year',
        'outgoing_id',
        'date_released',
        'chedrix_2025',
        'location',
        'No', 
        'quarter',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * This ensures that when you retrieve these attributes, they are automatically
     * converted to the specified type.
     *
     * @var array<string, string>
     */
    protected $appends = ['no_formatted'];
    protected $casts = [
        'date_received' => 'date',
        'time_emailed' => 'string',
        'date_time_routed' => 'datetime',
        'date_acted_by_es' => 'date',
        'year' => 'integer',
    ];
    public function getNoFormattedAttribute()
    {
        // Cast the stored "No" to an integer.
        $number = (int) $this->getAttribute('No');
        if ($number === 0) {
            // Fallback: use the record's id.
            $number = $this->id;
        }
        return str_pad($number, 4, '0', STR_PAD_LEFT);
    }
    public function getDateReceivedAttribute($value)
{
    if (!$value || $value === '0000-00-00') {
        return null;
    }
    return \Carbon\Carbon::parse($value)->format('Y-m-d');
}

    public function outgoing()
    {
        return $this->belongsTo(Outgoing::class, 'outgoing_id');
    }
}
