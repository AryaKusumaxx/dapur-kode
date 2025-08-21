<?php

namespace Tests\Unit;

use App\Models\Order;
use App\Models\Product;
use App\Models\ProductWarrantyPrice;
use App\Models\User;
use App\Models\Warranty;
use App\Services\WarrantyService;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WarrantyServiceTest extends TestCase
{
    use RefreshDatabase;

    protected $warrantyService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->warrantyService = new WarrantyService();
    }

    /** @test */
    public function it_creates_warranty_for_paid_order()
    {
        // Create a user
        $user = User::factory()->create();

        // Create a product with warranty
        $product = Product::factory()->create([
            'name' => 'Test Product',
            'has_warranty' => true
        ]);

        // Create warranty price
        $warrantyPrice = ProductWarrantyPrice::factory()->create([
            'product_id' => $product->id,
            'months' => 6,
            'price' => 100000,
            'is_default' => true
        ]);

        // Create an order that's paid
        $order = Order::factory()->create([
            'user_id' => $user->id,
            'status' => 'paid',
            'total' => 1000000
        ]);

        // Create order items
        $order->items()->create([
            'product_id' => $product->id,
            'name' => $product->name,
            'quantity' => 1,
            'unit_price' => 1000000,
            'subtotal' => 1000000
        ]);

        // Execute the method
        $this->warrantyService->createWarrantyForOrder($order);

        // Check that a warranty was created
        $this->assertDatabaseHas('warranties', [
            'order_id' => $order->id,
            'product_id' => $product->id,
            'is_active' => true,
        ]);

        // Check warranty details
        $warranty = Warranty::where('order_id', $order->id)->first();
        $this->assertNotNull($warranty);
        
        // Verify warranty duration (6 months)
        $expectedEnd = Carbon::parse($warranty->starts_at)->addMonths(6);
        $this->assertEquals($expectedEnd->format('Y-m-d'), $warranty->ends_at->format('Y-m-d'));
    }

    /** @test */
    public function it_doesnt_create_warranty_for_unpaid_order()
    {
        // Create a user
        $user = User::factory()->create();

        // Create a product with warranty
        $product = Product::factory()->create([
            'name' => 'Test Product',
            'has_warranty' => true
        ]);

        // Create warranty price
        $warrantyPrice = ProductWarrantyPrice::factory()->create([
            'product_id' => $product->id,
            'months' => 6,
            'price' => 100000,
            'is_default' => true
        ]);

        // Create an order that's pending (not paid)
        $order = Order::factory()->create([
            'user_id' => $user->id,
            'status' => 'pending',
            'total' => 1000000
        ]);

        // Create order items
        $order->items()->create([
            'product_id' => $product->id,
            'name' => $product->name,
            'quantity' => 1,
            'unit_price' => 1000000,
            'subtotal' => 1000000
        ]);

        // Execute the method
        $this->warrantyService->createWarrantyForOrder($order);

        // Check that no warranty was created
        $this->assertDatabaseMissing('warranties', [
            'order_id' => $order->id,
        ]);
    }

    /** @test */
    public function it_doesnt_create_warranty_for_product_without_warranty()
    {
        // Create a user
        $user = User::factory()->create();

        // Create a product without warranty
        $product = Product::factory()->create([
            'name' => 'Test Product',
            'has_warranty' => false
        ]);

        // Create an order that's paid
        $order = Order::factory()->create([
            'user_id' => $user->id,
            'status' => 'paid',
            'total' => 1000000
        ]);

        // Create order items
        $order->items()->create([
            'product_id' => $product->id,
            'name' => $product->name,
            'quantity' => 1,
            'unit_price' => 1000000,
            'subtotal' => 1000000
        ]);

        // Execute the method
        $this->warrantyService->createWarrantyForOrder($order);

        // Check that no warranty was created
        $this->assertDatabaseMissing('warranties', [
            'order_id' => $order->id,
        ]);
    }

    /** @test */
    public function it_can_extend_warranty()
    {
        // Create a user
        $user = User::factory()->create();

        // Create a product with warranty
        $product = Product::factory()->create([
            'name' => 'Test Product',
            'has_warranty' => true
        ]);

        // Create warranty price options
        $defaultWarrantyPrice = ProductWarrantyPrice::factory()->create([
            'product_id' => $product->id,
            'months' => 6,
            'price' => 100000,
            'is_default' => true
        ]);

        $extensionWarrantyPrice = ProductWarrantyPrice::factory()->create([
            'product_id' => $product->id,
            'months' => 12,
            'price' => 180000,
            'is_default' => false
        ]);

        // Create an order that's paid
        $order = Order::factory()->create([
            'user_id' => $user->id,
            'status' => 'paid',
            'total' => 1000000
        ]);

        // Create order items
        $order->items()->create([
            'product_id' => $product->id,
            'name' => $product->name,
            'quantity' => 1,
            'unit_price' => 1000000,
            'subtotal' => 1000000
        ]);

        // Create initial warranty (6 months)
        $startDate = Carbon::now();
        $endDate = $startDate->copy()->addMonths(6);
        
        $warranty = Warranty::factory()->create([
            'order_id' => $order->id,
            'product_id' => $product->id,
            'starts_at' => $startDate,
            'ends_at' => $endDate,
            'is_active' => true
        ]);

        // Create a new order for the extension
        $extensionOrder = Order::factory()->create([
            'user_id' => $user->id,
            'status' => 'paid',
            'total' => $extensionWarrantyPrice->price
        ]);
        
        // Execute the warranty extension (add 12 months)
        $result = $this->warrantyService->extendWarranty($warranty, $extensionWarrantyPrice, $extensionOrder);

        // Get the updated warranty
        $updatedWarranty = Warranty::find($warranty->id);

        // Check that warranty end date was extended by 12 months
        $expectedNewEndDate = $endDate->copy()->addMonths(12);
        $this->assertEquals(
            $expectedNewEndDate->format('Y-m-d'), 
            Carbon::parse($updatedWarranty->ends_at)->format('Y-m-d')
        );
        
        // Check that a warranty extension was created
        $this->assertDatabaseHas('warranty_extensions', [
            'warranty_id' => $warranty->id,
            'old_ends_at' => $endDate->format('Y-m-d'),
            'new_ends_at' => $expectedNewEndDate->format('Y-m-d'),
            'price' => $extensionWarrantyPrice->price,
        ]);
        
        // Check the result of the extension
        $this->assertTrue($result['success']);
        $this->assertNotNull($result['extension']);
        $this->assertNotNull($result['order']);
    }
}
