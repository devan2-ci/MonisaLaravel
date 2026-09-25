<?php

namespace Database\Seeders;

use App\Models\TeacherClass;
use App\Models\TeacherSchedule;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class TeacherClassSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $teachers = TeacherSchedule::with(['teacher','rombel','schoolMapel.masterMapel',])
            ->where('is_active', true)->get()->groupBy('teacher_id');

        foreach ($teachers as $teacherId => $schedules) {
            $schedule = $schedules->first();
            $teacher = $schedule->teacher;
            $rombel = $schedule->rombel;
            $schoolMapel = $schedule->schoolMapel;

            if (!$teacher || !$rombel || !$schoolMapel) {
                continue;
            }

            $alreadyExists = TeacherClass::where('teacher_id', $teacher->id)->exists();

            if ($alreadyExists) {
                continue;
            }

            $mapelName = $schoolMapel->masterMapel?->name;

            $className = $mapelName . ' - ' . $rombel->jenjang;

            if ($rombel->schoolMajor?->major?->name) {
                $className .= ' ' . $rombel->schoolMajor->major->name;
            }

            $className .= ' ' . $rombel->name;

            TeacherClass::create([
                'school_id' => $teacher->school_id,
                'teacher_id' => $teacher->id,
                'rombel_id' => $rombel->id,
                'school_mapel_id' => $schoolMapel->id,
                'name' => $className,
                'description' => 'Kelas digital ' . $mapelName . ' untuk ' . $className,
                'join_code' => $this->generateJoinCode(),
                'tahun_ajaran' => '2026/2027',
                'is_active' => true,
            ]);
        }
    }

    private function generateJoinCode(): string
    {
        do {
            $code = 'MONISA-' . strtoupper(Str::random(6));
        } while (
            TeacherClass::where('join_code', $code)->exists()
        );

        return $code;
    }
}
