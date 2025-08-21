<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\Product;
use App\Models\Warranty;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Warranty>
 */
class WarrantyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $startsAt = now();
        $endsAt = now()->addMonths(6);
        
        return [
            'order_id' => Order::factory()->paid(),
            'product_id' => Product::factory()->withWarranty(),
            'starts_at' => $startsAt,
            'ends_at' => $endsAt,
            'is_active' => true,
        ];
    }

    /**
     * Indicate that the warranty is inactive.
     */
    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => false,
        ]);
    }

    /**
     * Indicate that the warranty has expired.
     */
    public function expired(): static
    {
        return $this->state(fn (array $attributes) => [
            'starts_at' => now()->subMonths(12),
            'ends_at' => now()->subDays(1),
        ]);
    }
}
