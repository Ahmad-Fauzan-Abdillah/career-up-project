<?php

namespace Database\Factories;

use App\Models\UserExperience;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<UserExperience>
 */
class UserExperienceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => \App\Models\User::factory(),
            'position' => fake()->jobTitle(),
            'company' => fake()->company(),
            'period' => fake()->date('F Y').' - '.fake()->date('F Y'),
            'description' => fake()->paragraph(),
        ];
    }
}
