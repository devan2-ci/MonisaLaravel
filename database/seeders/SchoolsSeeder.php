<?php

namespace Database\Seeders;

use App\Models\Schools;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SchoolsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Schools::create([
            'kode_sekolah' => 'SCH001',
            'nama' => 'SMA Negeri 1 Percobaan',
            'alamat_lengkap' => 'Jl. Percobaan No.1',
            'kode_pos' => '12345',
            'latitude' => '-7.983908',
            'longitude' => '106.865036',
            'no_telepon' => '(021) 1234567',
            'email' => 'info@sman1percobaan.sch.id',
            'link_website' => 'https://www.sman1percobaan.sch.id',
            'photo' => 'school_photos/sch001.jpg'
        ]);
        
        Schools::create([
            'kode_sekolah' => 'SCH002',
            'nama' => 'SMA Negeri 2 Percobaan',
            'alamat_lengkap' => 'Jl. Percobaan No.2',
            'kode_pos' => '12345',
            'latitude' => '-7.983908',
            'longitude' => '106.865036',
            'no_telepon' => '(021) 1234567',
            'email' => 'info@sman2percobaan.sch.id',
            'link_website' => 'https://www.sman2percobaan.sch.id',
            'photo' => 'school_photos/sch002.jpg'
        ]);
    }
}
