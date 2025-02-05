<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GmailToken extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'access_token',
    ];

    protected $casts = [
        'access_token' => 'array', // Automatically casts JSON to array
    ];

    /**
     * Get the user that owns the GmailToken.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
