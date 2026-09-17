<?php

namespace Database\Seeders;

use App\Models\Guardian;
use App\Models\Student;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class GuardianSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $students = Student::all();

        foreach ($students as $student) {

            // Setiap siswa memiliki 1-3 wali
            $jumlahWali = rand(1, 3);

            Guardian::factory()->count($jumlahWali)->create([
                'student_id' => $student->id,
            ]);
        }
    }
}
