<?php

namespace Database\Seeders;

use App\Models\Rombel;
use App\Models\Schools;
use App\Models\Student;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StudentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $rombels = Rombel::all();

        foreach ($rombels as $rombel) {
            Student::factory(5)->create([
                'school_id' => $rombel->school_id,
                'rombel_id' => $rombel->id,
            ]);
        }
    }
}
