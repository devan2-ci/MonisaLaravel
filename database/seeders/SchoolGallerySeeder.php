<?php

namespace Database\Seeders;

use App\Models\SchoolGalleries;
use App\Models\SchoolGallery;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SchoolGallerySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        SchoolGallery::create([
            'school_id' => 1,
            'name' => 'Foto Kegiatan 1',
            'photo' => 'school_galleries/sch001_1.jpg',
        ]);

        SchoolGallery::create([
            'school_id' => 2,
            'name' => 'Foto Kegiatan 2',
            'photo' => 'school_galleries/sch002_1.jpg',
        ]);

        SchoolGallery::create([
            'school_id' => 2,
            'name' => 'Foto Kegiatan 3',
            'photo' => 'school_galleries/sch002_1.jpg',
        ]);
    }
}
