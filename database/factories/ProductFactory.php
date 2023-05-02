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
//            'title' => fake()->title(),
            'title' => fake('ru')->realText(50),
            'price' => rand(100, 1000) . '.' . rand(10, 99),
        ];
    }
}
