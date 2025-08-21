<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\ProductWarrantyPrice;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ProductWarrantyPrice>
 */
class ProductWarrantyPriceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'product_id' => Product::factory(),
            'months' => fake()->randomElement([3, 6, 12, 24]),
            'price' => fake()->numberBetween(50000, 1000000),
            'is_default' => false,
        ];
    }

    /**
     * Indicate that this is the default warranty option.
     */
    public function asDefault(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_default' => true,
        ]);
    }
}
