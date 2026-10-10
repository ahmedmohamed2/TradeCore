<?php

namespace Database\Factories;

use App\Models\Treasury;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Treasury>
 */
class TreasuryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'code' => fake()->unique()->bothify('TR-####'),
            'name' => fake()->unique()->words(2, true),
            'is_master' => false,
            'opening_balance' => fake()->randomFloat(2, 0, 10000),
            'last_payment_number' => 0,
            'last_collection_number' => 0,
            'notes' => fake()->optional()->sentence(),
            'active' => true,
            'created_by' => User::factory(),
            'updated_by' => User::factory(),
        ];
    }

    /**
     * Indicate that the treasury is the single master treasury.
     */
    public function master(): static
    {
        return $this->state(fn (array $attributes): array => [
            'is_master' => true,
        ]);
    }

    /**
     * Indicate that the treasury is inactive.
     */
    public function inactive(): static
    {
        return $this->state(fn (array $attributes): array => [
            'active' => false,
        ]);
    }
}
