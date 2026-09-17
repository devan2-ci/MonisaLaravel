<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Teacher extends Model
{
    /** @use HasFactory<\Database\Factories\TeacherFactory> */
    use HasFactory, SoftDeletes;

    protected $table = 'teachers';

    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function school()
    {
        return $this->belongsTo(Schools::class, 'school_id');
    }

    public function schoolMapel()
    {
        return $this->belongsTo(SchoolMapel::class, 'school_mapel_id');
    }

    public function schedules()
    {
        return $this->hasMany(TeacherSchedule::class, 'teacher_id');
    }
}
