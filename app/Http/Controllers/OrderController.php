<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\ProductWarrantyPrice;
use App\Services\DiscountValidator;
use App\Services\InvoiceNumberService;
use App\Services\NotificationService;
use App\Services\WarrantyService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    protected $discountValidator;
    protected $invoiceNumberService;
    protected $notificationService;
    protected $warrantyService;
    
    /**
     * Constructor
     */
    public function __construct(
        DiscountValidator $discountValidator,
        InvoiceNumberService $invoiceNumberService,
        NotificationService $notificationService,
        WarrantyService $warrantyService
    ) {
        $this->discountValidator = $discountValidator;
        $this->invoiceNumberService = $invoiceNumberService;
        $this->notificationService = $notificationService;
        $this->warrantyService = $warrantyService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        
        if ($user->isManager() || $user->isAdmin()) {
            $orders = Order::with('user')->latest()->paginate(10);
        } else {
            $orders = Order::where('user_id', $user->id)->latest()->paginate(10);
        }
        
        return view('orders.index', compact('orders'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order)
    {
        $user = Auth::user();
        
        // Check if the user is authorized to view this order
        if (!$user->isManager() && !$user->isAdmin() && $order->user_id !== $user->id) {
            abort(403);
        }
        
        $order->load('items.product', 'items.productVariant', 'invoice', 'invoice.payments');
        
        return view('orders.show', compact('order'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Cek apakah pengguna adalah customer
        if (!Auth::user()->isCustomer()) {
            return redirect()->route('dashboard')
                ->with('error', 'Hanya customer yang dapat membuat pesanan.');
        }
        
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'variant_id' => 'nullable|exists:product_variants,id',
            'warranty_price_id' => 'nullable|exists:product_warranty_prices,id',
            'discount_code' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);
        
        $user = Auth::user();
        $product = Product::findOrFail($request->product_id);
        
        // Calculate base price
        $price = $product->base_price;
        
        // Add variant price adjustment if selected
        $variant = null;
        if ($request->variant_id) {
            $variant = ProductVariant::where('id', $request->variant_id)
                ->where('product_id', $product->id)
                ->firstOrFail();
            
            $price += $variant->price_adjustment;
        }
        
        // Initialize subtotal, discount, tax and total
        $subtotal = $price;
        $discountAmount = 0;
        
        // Apply discount if provided
        $appliedDiscount = null;
        if ($request->discount_code) {
            $validation = $this->discountValidator->validate($request->discount_code, $product);
            
            if ($validation['valid']) {
                $appliedDiscount = $validation['discount'];
                $discountAmount = $this->discountValidator->calculateDiscountAmount($appliedDiscount, $subtotal);
            }
        }
        
        // Calculate tax (e.g., 11% VAT)
        $taxPercentage = 0.11; // Get from settings in real implementation
        $taxAmount = ($subtotal - $discountAmount) * $taxPercentage;
        
        // Calculate total
        $total = $subtotal - $discountAmount + $taxAmount;
        
        DB::beginTransaction();
        
        try {
            // Create the order
            $order = Order::create([
                'order_number' => 'ORD-' . strtoupper(Str::random(8)),
                'user_id' => $user->id,
                'status' => 'pending',
                'subtotal' => $subtotal,
                'discount' => $discountAmount,
                'tax' => $taxAmount,
                'total' => $total,
                'notes' => $request->notes,
            ]);
            
            // Create order item
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $product->id,
                'product_variant_id' => $variant ? $variant->id : null,
                'name' => $product->name . ($variant ? ' - ' . $variant->name : ''),
                'quantity' => 1,
                'unit_price' => $price,
                'subtotal' => $price,
            ]);
            
            // Create invoice
            $invoice = $order->invoice()->create([
                'invoice_number' => $this->invoiceNumberService->generate(),
                'due_date' => now()->addDays(7), // 7 days due date
                'status' => 'pending',
                'payment_instructions' => 'Silahkan melakukan pembayaran ke rekening yang tersedia dan upload bukti pembayaran.',
            ]);
            
            // If discount was applied, increment its usage
            if ($appliedDiscount) {
                $this->discountValidator->incrementUsage($appliedDiscount);
            }
            
            DB::commit();
            
            // Send notification
            $this->notificationService->notifyNewOrder($order);
            
            return redirect()->route('customer.invoices.show', $invoice->id)
                ->with('success', 'Pesanan berhasil dibuat. Silakan lakukan pembayaran.');
            
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Update the order status.
     */
    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,processing,paid,completed,cancelled,refunded',
        ]);
        
        $oldStatus = $order->status;
        $order->status = $request->status;
        $order->save();
        
        // If order is marked as paid, create warranty if applicable
        if ($oldStatus !== 'paid' && $request->status === 'paid') {
            $this->warrantyService->createWarrantyForOrder($order);
        }
        
        return back()->with('success', 'Status pesanan berhasil diperbarui.');
    }

    /**
     * Cancel an order.
     */
    public function cancel(Order $order)
    {
        if (!$order->canBeCancelled()) {
            return back()->with('error', 'Pesanan ini tidak dapat dibatalkan.');
        }
        
        $order->status = 'cancelled';
        $order->save();
        
        // Update related invoice
        if ($order->invoice) {
            $order->invoice->status = 'cancelled';
            $order->invoice->save();
        }
        
        return back()->with('success', 'Pesanan berhasil dibatalkan.');
    }
}
