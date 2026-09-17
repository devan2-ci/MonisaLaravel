<?php

namespace Database\Seeders;

use App\Models\LessonPeriod;
use App\Models\Schools;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LessonPeriodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $schools = Schools::all();

        foreach ($schools as $school) {

            $periods = [
                [
                    'jam_ke' => 1,
                    'jam_mulai' => '07:00',
                    'jam_selesai' => '07:40',
                ],
                [
                    'jam_ke' => 2,
                    'jam_mulai' => '07:40',
                    'jam_selesai' => '08:20',
                ],
                [
                    'jam_ke' => 3,
                    'jam_mulai' => '08:20',
                    'jam_selesai' => '09:00',
                ],
                [
                    'jam_ke' => 4,
                    'jam_mulai' => '09:00',
                    'jam_selesai' => '09:40',
                ],
                [
                    'jam_ke' => 5,
                    'jam_mulai' => '09:40',
                    'jam_selesai' => '10:20',
                ],
                [
                    'jam_ke' => 6,
                    'jam_mulai' => '10:20',
                    'jam_selesai' => '11:00',
                ],
                [
                    'jam_ke' => 7,
                    'jam_mulai' => '11:00',
                    'jam_selesai' => '11:40',
                ],
                [
                    'jam_ke' => 8,
                    'jam_mulai' => '11:40',
                    'jam_selesai' => '12:20',
                ],
                [
                    'jam_ke' => 9,
                    'jam_mulai' => '13:00',
                    'jam_selesai' => '13:40',
                ],
                [
                    'jam_ke' => 10,
                    'jam_mulai' => '13:40',
                    'jam_selesai' => '14:20',
                ],
            ];

            foreach ($periods as $period) {

                LessonPeriod::updateOrCreate(
                    [
                        'school_id' => $school->id,
                        'jam_ke' => $period['jam_ke'],
                    ],
                    [
                        'jam_mulai' => $period['jam_mulai'],
                        'jam_selesai' => $period['jam_selesai'],
                        'is_active' => true,
                    ]
                );
            }
        }
    }
}
