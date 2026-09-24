<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TeacherClass extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'teacher_classes';

    protected $guarded = [];

    public function school()
    {
        return $this->belongsTo(Schools::class, 'school_id');
    }

    public function teacher()
    {
        return $this->belongsTo(Teacher::class, 'teacher_id');
    }

    public function schoolMapel()
    {
        return $this->belongsTo(SchoolMapel::class, 'school_mapel_id');
    }

    public function rombel()
    {
        return $this->belongsTo(Rombel::class, 'rombel_id');
    }

    public function students()
    {
        return $this->belongsToMany(
            Student::class,
            'teacher_class_students',
            'teacher_class_id',
            'student_id'
        )->withPivot('joined_at');
    }

    // public function materials()
    // {
    //     return $this->hasMany(Material::class, 'teacher_class_id');
    // }

    // public function assignments()
    // {
    //     return $this->hasMany(Assignment::class, 'teacher_class_id');
    // }
}
