<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Arr;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $array = ['01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12'];
        return [
            'date' => rand(0, 2) . rand(1, 9) . '.' . Arr::random($array) . '.' .rand(2018, 2022),
            'phone' => fake('ru')->phoneNumber(),
            'email' => fake()->email(),
            'address' => fake()->address(),
        ];
    }
}
