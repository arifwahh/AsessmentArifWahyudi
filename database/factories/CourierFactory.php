<?php

namespace Database\Factories;

use App\Models\Courier;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Courier>
 */
class CourierFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'phone' => fake()->unique()->numerify('08##########'),
            'level' => fake()->numberBetween(Courier::LEVEL_MIN, Courier::LEVEL_MAX),
            'vehicle_type' => fake()->randomElement(Courier::VEHICLE_TYPES),
            'vehicle_plate' => strtoupper(fake()->unique()->bothify('? #### ???')),
            'city' => fake()->city(),
            'address' => fake()->address(),
            'is_active' => fake()->boolean(90),
        ];
    }
}
