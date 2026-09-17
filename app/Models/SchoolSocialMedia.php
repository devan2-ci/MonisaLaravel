<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SchoolSocialMedia extends Model
{
    use HasFactory;
    protected $table = 'school_social_media';
    protected $guarded = [];

    public function school()
    {
        return $this->belongsTo(Schools::class, 'school_id');
    }
}
