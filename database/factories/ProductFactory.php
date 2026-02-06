<?php

namespace Database\Factories;

use App\Models\Category;
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
            'name'        => $this->faker->word(),              // product name
            'description' => $this->faker->sentence(),          // product description
            'price'       => $this->faker->numberBetween(500, 10000),
            'stock'       => $this->faker->numberBetween(1, 10),
            'category_id' => Category::factory(),               // relate to category
        ];
    }
}
