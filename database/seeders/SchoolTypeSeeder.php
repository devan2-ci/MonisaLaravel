<?php

namespace Database\Seeders;

use App\Models\SchoolLevel;
use App\Models\SchoolType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SchoolTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $types = [
            [
                'kode' => 'SD',
                'name' => 'Sekolah Dasar',
                'levels' => [
                    'I',
                    'II',
                    'III',
                    'IV',
                    'V',
                    'VI',
                ],
            ],
            [
                'kode' => 'SMP',
                'name' => 'Sekolah Menengah Pertama',
                'levels' => [
                    'VII',
                    'VIII',
                    'IX',
                ],
            ],
            [
                'kode' => 'SMA',
                'name' => 'Sekolah Menengah Atas',
                'levels' => [
                    'X',
                    'XI',
                    'XII',
                ],
            ],
        ];

        foreach ($types as $item) {
            $schoolType = SchoolType::updateOrCreate(
                ['kode' => $item['kode']],
                ['name' => $item['name']],
            );

            foreach ($item['levels'] as $levelName) {
                SchoolLevel::updateOrCreate(
                    [
                        'school_type_id' => $schoolType->id,
                        'name' => $levelName,
                    ]
                );
            }
        }
    }
}
