<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Record extends Model
{
    use HasFactory;

    // Table name is pluralized by default; override if needed
    protected $table = 'records';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'control_no',
        'project',
        'relevant_hei',
        'document_type',
        'name_of_document',
        'status',
        'transaction_type',
        'assigned_staff',
        'collaborators',
        'upload_files',
    ];
}
