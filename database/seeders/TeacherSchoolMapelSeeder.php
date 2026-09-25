<?php

namespace Database\Seeders;

use App\Models\SchoolMapel;
use App\Models\Teacher;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TeacherSchoolMapelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $teachers = Teacher::with('school')->get();

        foreach ($teachers as $teacher) {
            // Ambil semua mapel yang tersedia di sekolah guru
            $schoolMapels = SchoolMapel::where('school_id', $teacher->school_id)->get();

            if ($schoolMapels->isEmpty()) {
                continue;
            }

            // Jumlah mapel yang diajarkan guru
            $jumlahMapel = min(
                rand(1, 3),
                $schoolMapels->count()
            );

            // Ambil mapel secara acak
            $selectedMapels = $schoolMapels
                ->shuffle()
                ->take($jumlahMapel);

            foreach ($selectedMapels as $schoolMapel) {
                DB::table('teacher_school_mapel')->insertOrIgnore([
                    'teacher_id' => $teacher->id,
                    'school_mapel_id' => $schoolMapel->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
