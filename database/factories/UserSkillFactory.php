<?php

namespace Database\Factories;

use App\Models\UserSkill;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<UserSkill>
 */
class UserSkillFactory extends Factory
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
            'skill_name' => fake()->word(),
        ];
    }
}
