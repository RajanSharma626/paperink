<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResumeTemplate extends Model
{
    use HasFactory;

    protected $table = 'resume_templates';
    protected $fillable = [
        'name',
        'preview_img',
        'component',
    ];
}
