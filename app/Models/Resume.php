<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Resume extends Model
{
    
    public function sections()
    {
        return $this->hasMany(ResumeSection::class)->orderBy('position');
    }

    public function customFields()
    {
        return $this->hasMany(ResumeCustomField::class);
    }
}
