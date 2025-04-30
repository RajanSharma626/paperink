<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CoverTemplate extends Model
{
    use HasFactory;

    protected $table = 'coverletter_templates';
    protected $fillable = [
        'name',
        'preview_img',
        'component',
    ];
}
