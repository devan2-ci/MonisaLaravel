<?php

namespace Database\Seeders;

use App\Models\LessonPeriod;
use App\Models\Rombel;
use App\Models\Teacher;
use App\Models\TeacherSchedule;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TeacherScheduleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Hapus data schedule lama
        TeacherSchedule::withTrashed()->forceDelete();

        // Ambil semua rombel aktif
        $rombels = Rombel::where('is_active', true)
            ->with('school')
            ->get();

        foreach ($rombels as $rombel) {

            $schoolId = $rombel->school_id;

            /*
             * Ambil guru dari sekolah yang sama.
             *
             * Tidak menggunakan teachers.school_mapel_id
             * karena sekarang relasi guru-mapel menggunakan
             * tabel teacher_school_mapel.
             */
            $teachers = Teacher::where('school_id', $schoolId)
                ->with('schoolMapels')
                ->get();

            if ($teachers->isEmpty()) {
                continue;
            }

            // Pilih guru secara acak
            $teacher = $teachers->random();

            /*
             * Ambil mapel yang memang diajarkan guru tersebut.
             */
            $schoolMapels = $teacher->schoolMapels
                ->where('school_id', $schoolId);

            if ($schoolMapels->isEmpty()) {
                continue;
            }

            // Pilih salah satu mapel guru secara acak
            $schoolMapel = $schoolMapels->random();

            /*
             * Ambil periode pelajaran sekolah.
             */
            $periods = LessonPeriod::where('school_id', $schoolId)
                ->where('is_active', true)
                ->orderBy('jam_ke')
                ->take(2)
                ->get();

            if ($periods->count() < 2) {
                continue;
            }

            /*
             * Buat jadwal.
             *
             * TIDAK ADA school_id di teacher_schedules.
             * Sekolah diketahui melalui teacher, rombel,
             * school_mapel, dan lesson_period.
             */
            TeacherSchedule::create([
                'teacher_id' => $teacher->id,
                'rombel_id' => $rombel->id,
                'school_mapel_id' => $schoolMapel->id,

                'lesson_period_start_id' => $periods->first()->id,
                'lesson_period_end_id' => $periods->last()->id,

                'hari' => 'senin',
                'is_active' => true,
            ]);
        }
    }
}