<?php

namespace Database\Seeders;

use App\Models\LessonPeriod;
use App\Models\Rombel;
use App\Models\SchoolMapel;
use App\Models\Teacher;
use App\Models\TeacherSchedule;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TeacherScheduleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $rombels = Rombel::with('school')->get();

        foreach ($rombels as $rombel) {

            $schoolId = $rombel->school_id;

            $teacher = Teacher::where('school_id', $schoolId)->whereNotNull('school_mapel_id')->first();

            $periods = LessonPeriod::where('school_id', $schoolId)->orderBy('jam_ke')->take(2)->get();
            
            if (
                !$teacher ||
                $periods->count() < 2
            ) {
                continue;
            }

            TeacherSchedule::firstOrCreate(
                [
                    'school_id' => $schoolId,
                    'teacher_id' => $teacher->id,
                    'rombel_id' => $rombel->id,
                    'school_mapel_id' => $teacher->school_mapel_id,
                    'hari' => 'senin',
                ],
                [
                    'lesson_period_start_id' => $periods->first()->id,
                    'lesson_period_end_id' => $periods->last()->id,
                    'is_active' => true,
                ]
            );
        }
    }
}
