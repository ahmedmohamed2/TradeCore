<?php

namespace Database\Factories;

use App\Models\SystemSetting;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<SystemSetting>
 */
class SystemSettingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'system_name' => fake()->company(),
            'system_photo' => null,
            'active' => true,
            'general_alert' => fake()->sentence(),
            'address' => fake()->address(),
            'phone' => fake()->numerify('01#########'),
            'created_by' => User::factory(),
            'updated_by' => User::factory(),
            'company_code' => fake()->unique()->bothify('??###'),
        ];
    }

    /**
     * Indicate that the system setting is inactive.
     */
    public function inactive(): static
    {
        return $this->state(fn (array $attributes): array => [
            'active' => false,
        ]);
    }
}
