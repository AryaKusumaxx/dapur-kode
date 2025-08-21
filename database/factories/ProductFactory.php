<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

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
        $name = fake()->words(3, true);
        $slug = Str::slug($name);
        $productTypes = ['paket', 'jasa_pasang', 'lepas'];

        return [
            'name' => ucwords($name),
            'slug' => $slug,
            'description' => fake()->paragraph(),
            'type' => fake()->randomElement($productTypes),
            'base_price' => fake()->numberBetween(10000, 10000000),
            'has_warranty' => fake()->boolean(),
        ];
    }



    /**
     * Indicate that the product has warranty.
     */
    public function withWarranty(): static
    {
        return $this->state(fn (array $attributes) => [
            'has_warranty' => true,
        ]);
    }

    /**
     * Indicate that the product has no warranty.
     */
    public function withoutWarranty(): static
    {
        return $this->state(fn (array $attributes) => [
            'has_warranty' => false,
        ]);
    }
}
