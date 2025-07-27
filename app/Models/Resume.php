<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Resume extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'resumes';

    protected $fillable = [
        'user_id',
        'name',
        'last_name',
        'email',
        'phone',
        'address',
        'city',
        'postal_code',
        'country',
        'job_title',
        'summary',
        'template_id',
        'is_active',
        'preview_image'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function experiences()
    {
        return $this->hasMany(Experience::class, 'resume_id')->orderBy('start_date', 'desc');
    }

    public function education()
    {
        return $this->hasMany(Education::class, 'resume_id')->orderBy('start_date', 'desc');
    }

    public function skills()
    {
        return $this->hasMany(Skill::class, 'resume_id')->orderBy('level', 'desc');
    }

    public function template()
    {
        return $this->belongsTo(ResumeTemplate::class, 'template_id');
    }

    // Accessor for full name
    public function getFullNameAttribute()
    {
        return $this->name . ' ' . $this->last_name;
    }
}
