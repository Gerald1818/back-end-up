<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
        return [
            'barcode' => $this->faker->unique()->numberBetween(100000, 999999),
            'product_name' => $this->faker->word(),  
            'description' => $this->faker->sentence(10),
            'price' => $this->faker->numberBetween(100, 10000),
            'available_quantity' => $this->faker->numberBetween(1, 100),
            'category' => $this->faker->randomElement(['books', 'foods', 'toys', 'electronics']),
        ];
    }
}
