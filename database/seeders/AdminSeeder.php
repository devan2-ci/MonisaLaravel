<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::updateOrCreate([
            'username' => 'AdminSMA1',
            'email' => 'admin1@monisa.com',
            'password' => Hash::make('admin1.123'),
        ]);

        $user->assignRole('admin');

        Admin::updateOrCreate([
            'name' => 'Admin SMA Percobaan 1',
            'user_id' => $user->id,
            'school_id' => 1,
            'is_active' => true,
        ]);
    }
}
