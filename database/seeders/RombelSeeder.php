<?php

namespace Database\Seeders;

use App\Models\Rombel;
use App\Models\SchoolMajor;
use App\Models\Schools;
use App\Models\Teacher;
use Illuminate\Support\Str;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RombelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tahunAjaran = '2026/2027';

        $schools = Schools::all();

        foreach ($schools as $school) {
            
            $teachers = Teacher::where('school_id', $school->id)->get();

            $schoolMajors = SchoolMajor::with('major')->where('school_id', $school->id)->get();

            if (
                $teachers->isEmpty() ||
                $schoolMajors->isEmpty()
            ) {
                continue;
            }

            $teacherIndex = 0;

            foreach ($schoolMajors as $index => $schoolMajor) {
                if ($teacherIndex >= $teachers->count()) {
                    break;
                }

                $teacher = $teachers->get($teacherIndex);

                $jenjang = match ($teacherIndex % 3) {
                    0 => '10',
                    1 => '11',
                    default => '12',
                };

                $name = (string) (($teacherIndex % 3) + 1);

                Rombel::updateOrCreate(
                    [
                        'school_id' => $school->id,
                        'school_major_id' => $schoolMajor->id,
                        'jenjang' => $jenjang,
                        'name' => $name,
                        'tahun_ajaran' => $tahunAjaran,
                    ],
                    [
                        'teacher_id' => $teacher->id,
                        'qr_code' => 'ROMBEL-' . strtoupper(Str::random(20)),
                        'is_active' => true,
                    ]
                );

                $teacherIndex++;
            }
        }
    }
}
