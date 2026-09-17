<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SchoolGallery extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'school_galleries';
    protected $guarded = [];

    public function school()
    {
        return $this->belongsTo(Schools::class, 'school_id');
    }
}
