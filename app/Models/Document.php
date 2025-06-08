<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    use HasFactory;

    protected $table = 'documents';

    protected $fillable = [
        'file_name',
        'file_path',
        'status',
        'created_at',
        'updated_at',
    ];

    public const UPLOAD_S3    = 1;
    public const UPLOAD_LOCAL = 2;
    
}
