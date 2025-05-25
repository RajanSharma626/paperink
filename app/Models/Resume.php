<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Resume extends Model
{
    use HasFactory, SoftDeletes;

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
        'is_active'
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
        return $this->hasMany(Experience::class)->orderBy('start_date', 'desc');
    }

    public function education()
    {
        return $this->hasMany(Education::class)->orderBy('start_date', 'desc');
    }

    public function skills()
    {
        return $this->hasMany(Skill::class)->orderBy('level', 'desc');
    }

    // Accessor for full name
    public function getFullNameAttribute()
    {
        return $this->name . ' ' . $this->last_name;
    }
}
