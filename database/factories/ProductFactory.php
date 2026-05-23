<?php

namespace Database\Factories;

use App\Models\Product;
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
            'nombre'      => $this->faker->words(2, true),
            'descripcion' => $this->faker->sentence(),
            'cantidad'    => $this->faker->numberBetween(1, 50),
            'precio'      => $this->faker->randomFloat(2, 10, 500),
            'disponible'  => $this->faker->boolean(),
        ];
    }
}
