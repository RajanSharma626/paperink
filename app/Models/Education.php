<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Education extends Model
{
     use HasFactory;

    protected $fillable = [
        'resume_id',
        'school',
        'degree',
        'field_of_study',
        'start_date',
        'end_date',
        'city',
        'description',
        'gpa',
        'is_current'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'is_current' => 'boolean',
        'gpa' => 'decimal:2'
    ];

    // Relationships
    public function resume()
    {
        return $this->belongsTo(Resume::class);
    }

    // Accessor for formatted date range
    public function getDateRangeAttribute()
    {
        $start = $this->start_date ? $this->start_date->format('M Y') : '';
        $end = $this->is_current ? 'Present' : ($this->end_date ? $this->end_date->format('M Y') : '');
        
        return $start . ' - ' . $end;
    }
}
