<?php

namespace Database\Seeders;

use App\Models\Major;
use App\Models\SchoolMajor;
use App\Models\Schools;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SchoolMajorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        SchoolMajor::withTrashed()->forceDelete();

        $schools = Schools::all();
        $majors = Major::all();

        foreach ($schools as $school) {

            // Ambil 2–4 jurusan secara acak
            $randomMajors = $majors
                ->shuffle()
                ->take(rand(2, 4));

            foreach ($randomMajors as $major) {
                $maxRombels = rand(4, 6);
                SchoolMajor::create([
                    'school_id' => $school->id,
                    'major_id' => $major->id,
                    'max_rombels' => $maxRombels,
                ]);
            }
        }
    }
}
