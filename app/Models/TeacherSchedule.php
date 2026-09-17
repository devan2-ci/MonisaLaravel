<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TeacherSchedule extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'teacher_schedules';

    protected $guarded = [];

    public function school()
    {
        return $this->belongsTo(Schools::class, 'school_id');
    }

    public function teacher()
    {
        return $this->belongsTo(Teacher::class, 'teacher_id');
    }

    public function rombel()
    {
        return $this->belongsTo(Rombel::class, 'rombel_id');
    }

    public function schoolMapel()
    {
        return $this->belongsTo(SchoolMapel::class, 'school_mapel_id');
    }

    public function LessonPeriodStart()
    {
        return $this->belongsTo(
            LessonPeriod::class,
            'lesson_period_start_id'
        );
    }

    public function LessonPeriodEnd()
    {
        return $this->belongsTo(
            LessonPeriod::class,
            'lesson_period_end_id'
        );
    }
}
