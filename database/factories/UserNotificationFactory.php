<?php

namespace Database\Factories;

use App\Models\UserNotification;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<UserNotification>
 */
class UserNotificationFactory extends Factory
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
            'title' => fake()->sentence(4),
            'message' => fake()->sentence(),
            'type' => fake()->randomElement(['success', 'info', 'warning']),
            'is_read' => false,
        ];
    }
}
