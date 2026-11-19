<?php

namespace Database\Factories;

use App\Models\TrainingEnrollment;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<TrainingEnrollment>
 */
class TrainingEnrollmentFactory extends Factory
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
            'training_id' => \App\Models\Training::factory()->published(),
            'certificate_issued' => false,
        ];
    }
}
