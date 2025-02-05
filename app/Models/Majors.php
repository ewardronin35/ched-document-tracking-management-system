<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Programs;
use App\Models\SOmasterList;

class Majors extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'program_id'];

    public function program()
    {
        return $this->belongsTo(Programs::class);
    }

    public function SOmasterList()
    {
        return $this->hasMany(SOmasterList::class);
    }
}

