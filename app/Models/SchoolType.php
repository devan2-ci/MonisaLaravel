<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SchoolType extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'school_types';
    protected $guarded = [];

    public function levels()
    {
        return $this->hasMany(SchoolLevel::class);
    }

    public function schools()
    {
        return $this->hasMany(Schools::class);
    }
}
