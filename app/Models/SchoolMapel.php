<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SchoolMapel extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'school_mapels';
    protected $guarded = [];

    public function school()
    {
        return $this->belongsTo(Schools::class, 'school_id');
    }

    public function masterMapel()
    {
        return $this->belongsTo(MasterMapel::class, 'master_mapel_id');
    }

    public function schedules()
    {
        return $this->hasMany(
            TeacherSchedule::class,
            'school_mapel_id'
        );
    }
}
