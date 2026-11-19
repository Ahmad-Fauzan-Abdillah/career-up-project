<?php

namespace Database\Factories;

use App\Models\UserEducation;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<UserEducation>
 */
class UserEducationFactory extends Factory
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
            'institution' => fake()->company(),
            'major' => fake()->word(),
            'start_year' => (string) now()->subYears(2)->year,
            'end_year' => (string) now()->year,
        ];
    }
}
