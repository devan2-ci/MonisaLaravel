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
        $no_hp = $this->faker->unique()->numerify('0###########');
        $email = $nisn . '@monisa.com';
        $user = User::factory()->create([
            'email' => $email,
            'password' => Hash::make($nisn),
            'name' => $name,
            'username' => $nisn,
            'phone' => $no_hp,
        ]);

        $user->assignRole('student');
        return [
            'user_id' => $user->id,
            'name' => $name,
            'nis' => $this->faker->unique()->numerify('2026#####'),
            'nisn' => $nisn,
            'email' => $email,
            'gender' => $this->faker->randomElement(['l', 'p']),
            'tanggal_lahir' => $this->faker->date('Y-m-d', '2009-12-31'),
            'alamat' => $this->faker->address(),
            'no_hp' => $no_hp,
        ];
    }
}
