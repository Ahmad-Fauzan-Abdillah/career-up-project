<?php

namespace Database\Factories;

use App\Models\Application;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Application>
 */
class ApplicationFactory extends Factory
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
            'job_listing_id' => \App\Models\JobListing::factory()->published(),
            'full_name' => fake()->name(),
            'phone' => fake()->numerify('08##########'),
            'email' => fake()->unique()->safeEmail(),
            'domicile' => fake()->city(),
            'cv_path' => 'cvs/'.fake()->uuid().'.pdf',
            'status' => fake()->randomElement(['Diproses', 'Diterima', 'Ditolak']),
            'notes' => fake()->optional()->sentence(),
        ];
    }
}
