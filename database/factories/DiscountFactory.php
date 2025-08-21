<?php

namespace Database\Factories;

use App\Models\Discount;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Discount>
 */
class DiscountFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $discountTypes = ['percentage', 'fixed'];
        
        return [
            'code' => strtoupper(Str::random(8)),
            'description' => fake()->sentence(),
            'type' => fake()->randomElement($discountTypes),
            'value' => fake()->randomElement($discountTypes) === 'percentage' 
                ? fake()->numberBetween(5, 50) 
                : fake()->numberBetween(50000, 500000),
            'product_id' => null, // Global discount by default
            'is_active' => true,
            'starts_at' => now()->subDays(5),
            'expires_at' => now()->addDays(30),
            'max_uses' => fake()->numberBetween(10, 1000),
            'used_count' => 0,
        ];
    }

    /**
     * Indicate that the discount is for a specific product.
     */
    public function forProduct(Product $product = null): static
    {
        return $this->state(fn (array $attributes) => [
            'product_id' => $product?->id ?? Product::factory(),
        ]);
    }

    /**
     * Indicate that the discount is inactive.
     */
    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => false,
        ]);
    }

    /**
     * Indicate that the discount is expired.
     */
    public function expired(): static
    {
        return $this->state(fn (array $attributes) => [
            'expires_at' => now()->subDays(1),
        ]);
    }

    /**
     * Indicate that the discount has reached max usage.
     */
    public function maxedOut(): static
    {
        return $this->state(fn (array $attributes) => [
            'max_uses' => 10,
            'used_count' => 10,
        ]);
    }
}
