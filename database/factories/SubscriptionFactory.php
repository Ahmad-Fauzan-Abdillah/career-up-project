<?php

namespace Database\Factories;

use App\Models\Subscription;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Subscription>
 */
class SubscriptionFactory extends Factory
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
            'training_id' => \App\Models\Training::factory()->state(['category' => 'Premium', 'status' => 'terbit']),
            'plan_type' => 'bulanan',
            'price' => 49000,
            'payment_method' => 'QRIS',
            'status' => 'pending',
            'valid_until' => today()->addMonth(),
        ];
    }

    public function active(): static
    {
        return $this->state(fn (array $attributes) => ['status' => 'aktif']);
    }
}
