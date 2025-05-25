<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Experience extends Model
{
    use HasFactory;

    protected $fillable = [
        'resume_id',
        'job_title',
        'company',
        'start_date',
        'end_date',
        'city',
        'description',
        'is_current'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'is_current' => 'boolean',
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

    // Accessor for duration
    public function getDurationAttribute()
    {
        if (!$this->start_date) return null;

        $endDate = $this->is_current ? Carbon::now() : $this->end_date;
        if (!$endDate) return null;

        $diff = $this->start_date->diff($endDate);
        $years = $diff->y;
        $months = $diff->m;

        $duration = [];
        if ($years > 0) $duration[] = $years . ' year' . ($years > 1 ? 's' : '');
        if ($months > 0) $duration[] = $months . ' month' . ($months > 1 ? 's' : '');

        return implode(' ', $duration) ?: 'Less than a month';
    }
}
