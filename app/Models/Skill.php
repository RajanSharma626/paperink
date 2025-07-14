<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Skill extends Model
{
    use HasFactory;

    protected $table = "skills";

    protected $fillable = [
        'resume_id',
        'skill',
        'level',
        'category',
        'years_of_experience'
    ];

    protected $casts = [
        'years_of_experience' => 'integer'
    ];

    // Define skill levels
    const SKILL_LEVELS = [
        'Beginner' => 1,
        'Intermediate' => 2,
        'Expert' => 3
    ];

    // Relationships
    public function resume()
    {
        return $this->belongsTo(Resume::class);
    }

    // Accessor for level numeric value
    public function getLevelValueAttribute()
    {
        return self::SKILL_LEVELS[$this->level] ?? 0;
    }

    // Scope for filtering by level
    public function scopeByLevel($query, $level)
    {
        return $query->where('level', $level);
    }

    // Scope for filtering by category
    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }
}
