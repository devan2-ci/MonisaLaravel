<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SchoolMajor extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'school_majors';
    protected $guarded = [];

    public function school()
    {
        return $this->belongsTo(Schools::class, 'school_id');
    }

    public function major()
    {
        return $this->belongsTo(Major::class, 'major_id');
    }
}
