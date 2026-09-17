<?php

namespace Database\Factories;

use App\Models\Teacher;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

/**
 * @extends Factory<Teacher>
 */
class TeacherFactory extends Factory
{
    protected $model = Teacher::class;

    public function definition(): array
    {
        $nip = $this->faker->unique()->numerify('199##### 2026 ## # ###');
        $nuptk = $this->faker->unique()->numerify('199##### 2026 ## # ###');
        $name = $this->faker->name();
        $user = User::factory()->create([
            'email' => $nuptk . '@monisa.com',
            'password' => Hash::make($nuptk),
            'name' => $name,
            'username' => $nuptk,
        ]);

        $user->assignRole('teacher');
        return [
            'user_id' => $user->id,
            'name' => $name,
            'nuptk' => $nuptk,
            'nip' => $nip,
            'gender' => $this->faker->randomElement(['l', 'p']),
        ];
    }
}
