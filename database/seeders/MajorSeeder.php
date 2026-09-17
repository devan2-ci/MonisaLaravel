<?php

namespace Database\Seeders;

use App\Models\Major;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MajorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Major::withTrashed()->forceDelete();

        Major::create([
            'kode_jur' => 'RPL',
            'name' => 'Rekayasa Perangkat Lunak',
        ]);

        Major::create([
            'kode_jur' => 'TKJ',
            'name' => 'Teknik Komputer dan Jaringan',
        ]);

        Major::create([
            'kode_jur' => 'DKV',
            'name' => 'Desain Komunikasi Visual',
        ]);

        Major::create([
            'kode_jur' => 'TL',
            'name' => 'Teknik Logistik',
        ]);
    }
}
