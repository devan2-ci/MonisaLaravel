<?php

namespace Database\Factories;

use App\Models\Guardian;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

/**
 * @extends Factory<Guardian>
 */
class GuardianFactory extends Factory
{
    protected $model = Guardian::class;

    public function definition(): array
    {
        $name = $this->faker->name();
        $phone = $this->faker->phoneNumber();
        $email = $phone . '@monisa.com';

        $user = User::factory()->create([
            'email' => $email,
            'password' => Hash::make($phone),
            'name' => $name,
            'username' => $name . $this->faker->unique()->numerify('##'),
        ]);

        $user->assignRole('parent');
        return [
            'user_id' => $user->id,
            'student_id' => null,
            'name' => $name,
            'relationship' => fake()->randomElement([
                'ayah',
                'ibu',
                'wali',
            ]),
            'email' => $email,
            'phone' => $phone,
            'address' => $this->faker->address(),
        ];
    }
}
