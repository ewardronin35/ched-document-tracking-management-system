<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Majors;
use App\Models\SOmasterList;

class Programs extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'psced_code'];

    public function majors()
    {
        return $this->hasMany(Majors::class);
    }

    public function somasterList()
    {
        return $this->hasMany(SOmasterList::class);
    }
}
