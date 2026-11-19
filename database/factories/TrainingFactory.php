<?php

namespace Database\Factories;

use App\Models\Training;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Training>
 */
class TrainingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->unique()->sentence(3),
            'category' => 'Gratis',
            'schedule_date' => today()->addWeek(),
            'start_time' => '09:00:00',
            'end_time' => '11:00:00',
            'platform' => 'Zoom',
            'zoom_link' => fake()->url(),
            'mentor_name' => fake()->name(),
            'mentor_role' => fake()->jobTitle(),
            'mentor_avatar' => null,
            'duration' => '2 jam',
            'level' => 'Pemula',
            'description' => fake()->paragraph(),
            'syllabus' => fake()->sentence(),
            'thumbnail' => null,
            'status' => 'draft',
            'quota' => null,
        ];
    }

    public function published(): static
    {
        return $this->state(fn (array $attributes) => ['status' => 'terbit']);
    }
}
