<?php

namespace Tests\Unit;

use App\Models\Discount;
use App\Models\Product;
use App\Services\DiscountValidator;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DiscountValidatorTest extends TestCase
{
    use RefreshDatabase;

    protected $discountValidator;

    protected function setUp(): void
    {
        parent::setUp();
        $this->discountValidator = new DiscountValidator();
    }

    /** @test */
    public function it_should_validate_global_discount()
    {
        // Create a product
        $product = Product::factory()->create([
            'name' => 'Test Product',
            'base_price' => 1000000
        ]);

        // Create a global discount (applicable to any product)
        $discount = Discount::factory()->create([
            'code' => 'TEST25',
            'description' => 'Test discount 25%',
            'type' => 'percentage',
            'value' => 25,
            'product_id' => null, // Global discount
            'is_active' => true,
            'starts_at' => now()->subDay(),
            'expires_at' => now()->addDays(10),
            'max_uses' => 100,
            'used_count' => 0
        ]);

        // Validate the discount
        $result = $this->discountValidator->validate('TEST25', $product);

        // Assert discount is valid
        $this->assertTrue($result['valid']);
        $this->assertEquals($discount->id, $result['discount']->id);
        $this->assertEquals(250000, $result['amount']); // 25% of 1000000
    }

    /** @test */
    public function it_should_validate_product_specific_discount()
    {
        // Create a product
        $product = Product::factory()->create([
            'name' => 'Test Product',
            'base_price' => 1000000
        ]);

        // Create a product-specific discount
        $discount = Discount::factory()->create([
            'code' => 'PROD100K',
            'description' => 'Product specific discount',
            'type' => 'fixed',
            'value' => 100000,
            'product_id' => $product->id, // Specific to this product
            'is_active' => true,
            'starts_at' => now()->subDay(),
            'expires_at' => now()->addDays(10),
            'max_uses' => 100,
            'used_count' => 0
        ]);

        // Validate the discount
        $result = $this->discountValidator->validate('PROD100K', $product);

        // Assert discount is valid
        $this->assertTrue($result['valid']);
        $this->assertEquals($discount->id, $result['discount']->id);
        $this->assertEquals(100000, $result['amount']); // Fixed 100000
    }

    /** @test */
    public function it_should_reject_inactive_discount()
    {
        // Create a product
        $product = Product::factory()->create([
            'name' => 'Test Product',
            'base_price' => 1000000
        ]);

        // Create an inactive discount
        $discount = Discount::factory()->create([
            'code' => 'INACTIVE',
            'description' => 'Inactive discount',
            'type' => 'percentage',
            'value' => 10,
            'product_id' => null,
            'is_active' => false, // Inactive
            'starts_at' => now()->subDay(),
            'expires_at' => now()->addDays(10),
            'max_uses' => 100,
            'used_count' => 0
        ]);

        // Validate the discount
        $result = $this->discountValidator->validate('INACTIVE', $product);

        // Assert discount is invalid
        $this->assertFalse($result['valid']);
        $this->assertNull($result['discount']);
    }

    /** @test */
    public function it_should_reject_expired_discount()
    {
        // Create a product
        $product = Product::factory()->create([
            'name' => 'Test Product',
            'base_price' => 1000000
        ]);

        // Create an expired discount
        $discount = Discount::factory()->create([
            'code' => 'EXPIRED',
            'description' => 'Expired discount',
            'type' => 'percentage',
            'value' => 10,
            'product_id' => null,
            'is_active' => true,
            'starts_at' => now()->subDays(30),
            'expires_at' => now()->subDays(1), // Expired
            'max_uses' => 100,
            'used_count' => 0
        ]);

        // Validate the discount
        $result = $this->discountValidator->validate('EXPIRED', $product);

        // Assert discount is invalid
        $this->assertFalse($result['valid']);
        $this->assertNull($result['discount']);
    }

    /** @test */
    public function it_should_reject_maxed_out_discount()
    {
        // Create a product
        $product = Product::factory()->create([
            'name' => 'Test Product',
            'base_price' => 1000000
        ]);

        // Create a maxed out discount
        $discount = Discount::factory()->create([
            'code' => 'MAXED',
            'description' => 'Maxed out discount',
            'type' => 'percentage',
            'value' => 10,
            'product_id' => null,
            'is_active' => true,
            'starts_at' => now()->subDay(),
            'expires_at' => now()->addDays(10),
            'max_uses' => 10,
            'used_count' => 10 // Used max times
        ]);

        // Validate the discount
        $result = $this->discountValidator->validate('MAXED', $product);

        // Assert discount is invalid
        $this->assertFalse($result['valid']);
        $this->assertNull($result['discount']);
    }

    /** @test */
    public function it_should_reject_wrong_product_discount()
    {
        // Create products
        $product1 = Product::factory()->create([
            'name' => 'Product 1',
            'base_price' => 1000000
        ]);
        
        $product2 = Product::factory()->create([
            'name' => 'Product 2',
            'base_price' => 2000000
        ]);

        // Create a product-specific discount for product 1
        $discount = Discount::factory()->create([
            'code' => 'ONLYPROD1',
            'description' => 'Only for Product 1',
            'type' => 'percentage',
            'value' => 10,
            'product_id' => $product1->id, // Only for product 1
            'is_active' => true,
            'starts_at' => now()->subDay(),
            'expires_at' => now()->addDays(10),
            'max_uses' => 100,
            'used_count' => 0
        ]);

        // Try to validate the discount for product 2
        $result = $this->discountValidator->validate('ONLYPROD1', $product2);

        // Assert discount is invalid for product 2
        $this->assertFalse($result['valid']);
        $this->assertNull($result['discount']);
    }
}
