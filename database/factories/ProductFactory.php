<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'barcode' => $this->faker->unique()->numberBetween(100000, 999999),
            'description' => $this->faker->sentence,
            'price' => $this->faker->numberBetween(100, 10000),
            'available_quantity' => $this->faker->numberBetween(1, 100),
            'category' => $this->faker->randomElement(['books', 'foods', 'toys', 'electronics']),
        ];
    }
}
