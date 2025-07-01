<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CoverLetter extends Model
{
    protected $fillable = [
    'user_id',
    'name',
    'job_title',
    'company_name',
    'hiring_manager_name',
    'email',
    'phone',
    'address',
    'content',
    'template_id'
];

    protected $table = 'cover_letters';

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function template()
    {
        return $this->belongsTo(CoverTemplate::class, 'template_id');
    }
}
