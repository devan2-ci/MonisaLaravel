<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LessonPeriod extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'lesson_periods';

    protected $guarded = [];

    public function school()
    {
        return $this->belongsTo(Schools::class, 'school_id');
    }

    public function startSchedules()
    {
        return $this->hasMany(
            TeacherSchedule::class,
            'lesson_period_start_id'
        );
    }

    public function endSchedules()
    {
        return $this->hasMany(
            TeacherSchedule::class,
            'lesson_period_end_id'
        );
    }
}
