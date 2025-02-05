<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'tracking_number',
        'email',
        'full_name',
        'document_type',
        'file_path',
        'status',
        'status_details',
        'approval_status',
        'phone_number',
        'route_to',
    ];
    

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'status_details' => 'array',
    ];
    public function user()
    {
        return $this->belongsTo(User::class, 'email', 'email');
    }
    public function routedUser()
    {
        return $this->belongsTo(User::class, 'routed_to', 'id'); // Ensure 'id' is used properly
    }
    
}
