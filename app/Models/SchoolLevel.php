<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SchoolLevel extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'school_levels';
    protected $guarded = [];

    public function schoolType()
    {
        return $this->belongsTo(SchoolType::class, 'school_type_id');
    }
}
