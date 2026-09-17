<?php

namespace Database\Factories;

use App\Models\Student;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

/**
 * @extends Factory<Student>
 */
class StudentFactory extends Factory
{
    protected $model = Student::class;
    
    public function definition(): array
    {
        $nisn = $this->faker->unique()->numerify('00#####');
        $name = $this->faker->name();
        $user = User::factory()->create([
            'email' => $nisn . '@monisa.com',
            'password' => Hash::make($nisn),
            'name' => $name,
            'username' => $nisn
        ]);

        $user->assignRole('student');
        return [
            'user_id' => $user->id,
            'name' => $name,
            'nis' => $this->faker->unique()->numerify('2026#####'),
            'nisn' => $nisn,
            'gender' => $this->faker->randomElement(['l', 'p']),
        ];
    }
}
