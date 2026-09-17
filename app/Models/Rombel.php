<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Rombel extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'rombels';

    protected $guarded = [];

    public function school()
    {
        return $this->belongsTo(Schools::class, 'school_id');
    }

    public function teacher()
    {
        return $this->belongsTo(Teacher::class, 'teacher_id');
    }

    public function schoolMajor()
    {
        return $this->belongsTo(SchoolMajor::class, 'school_major_id');
    }

    public function students()
    {
        return $this->hasMany(Student::class, 'rombel_id');
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    public function schedules()
    {
        return $this->hasMany(TeacherSchedule::class, 'rombel_id');
    }
}
