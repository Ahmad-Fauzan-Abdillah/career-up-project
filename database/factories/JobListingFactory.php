<?php

namespace Database\Factories;

use App\Models\JobListing;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<JobListing>
 */
class JobListingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->jobTitle(),
            'company_name' => fake()->company(),
            'company_logo' => null,
            'location' => fake()->city(),
            'type' => 'lowongan',
            'work_system' => 'Full Time',
            'duration' => null,
            'department' => fake()->word(),
            'description' => fake()->paragraph(),
            'qualifications' => fake()->sentence(),
            'is_featured' => false,
            'status' => 'draft',
            'deadline' => null,
            'salary_min' => null,
            'salary_max' => null,
            'created_by' => \App\Models\User::factory()->state(['role' => 'admin']),
        ];
    }

    public function published(): static
    {
        return $this->state(fn (array $attributes) => ['status' => 'terbit']);
    }

    public function internship(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'magang',
            'duration' => '3 bulan',
        ]);
    }
}
