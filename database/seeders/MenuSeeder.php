<?php

namespace Database\Seeders;

use App\Models\Menu;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Menu::insert([
            [
                'name'=>'Dashboard',
                'route'=>'dashboard.index',
                'icon'=>'fas fa-home',
                'parent_id'=>null,
                'order'=>1
            ],
            [
                'name'=>'Settings',
                'route'=>null,
                'icon'=>'fas fa-users',
                'parent_id'=>null,
                'order'=>2
            ],
            [
                'name'=>'Menus',
                'route'=>'menus.index',
                'icon'=>'fas fa-user',
                'parent_id'=>2,
                'order'=>1
            ],
            [
                'name'=>'Roles',
                'route'=>'roles.index',
                'icon'=>'fas fa-user-tag',
                'parent_id'=>2,
                'order'=>2
            ],
            [
                'name'=>'User Management',
                'route'=>null,
                'icon'=>'fas fa-users',
                'parent_id'=>null,
                'order'=>3
            ],
            [
                'name'=>'Admin',
                'route'=>'admins.index',
                'icon'=>null,
                'parent_id'=>5,
                'order'=>1
            ],
            [
                'name'=>'Student',
                'route'=>'students.index',
                'icon'=>null,
                'parent_id'=>5,
                'order'=>2
            ],
            [
                'name'=>'Teacher',
                'route'=>'teachers.index',
                'icon'=>null,
                'parent_id'=>5,
                'order'=>1
            ],
            [
                'name'=>'School Management',
                'route'=>null,
                'icon'=>null,
                'parent_id'=>null,
                'order'=>4
            ],
            [
                'name'=>'School',
                'route'=>'schools.index',
                'icon'=>null,
                'parent_id'=>9,
                'order'=>1
            ],
            [
                'name'=>'School Profile',
                'route'=>'my-school.index',
                'icon'=>null,
                'parent_id'=>9,
                'order'=>1
            ],
            [
                'name'=>'Fasilities',
                'route'=>'fasilities.index',
                'icon'=>null,
                'parent_id'=>9,
                'order'=>2
            ],
        ]);
    }
}
