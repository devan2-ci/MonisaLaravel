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
        $nip = $this->faker->unique()->numerify('199#####2026######');
        $nuptk = $this->faker->unique()->numerify('199#####2026######');
        $name = $this->faker->name();
        $no_hp = $this->faker->unique()->numerify('0###########');
        $email = $nuptk . '@monisa.com';
        $user = User::factory()->create([
            'email' => $email,
            'password' => Hash::make($nuptk),
            'name' => $name,
            'username' => $nuptk,
            'phone' => $no_hp,
        ]);

        $user->assignRole('teacher');
        return [
            'user_id' => $user->id,
            'name' => $name,
            'nuptk' => $nuptk,
            'nip' => $nip,
            'email' => $email,
            'gender' => $this->faker->randomElement(['l', 'p']),
            'tanggal_lahir' => $this->faker->date('Y-m-d', '2007-12-31'),
            'alamat' => $this->faker->address(),
            'no_hp' => $no_hp,
        ];
    }
}
