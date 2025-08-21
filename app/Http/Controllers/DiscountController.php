<?php

namespace App\Http\Controllers;

use App\Models\Discount;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DiscountController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Only managers and admins can manage discounts
        if (!Auth::user()->isManager() && !Auth::user()->isAdmin()) {
            abort(403);
        }
        
        $discounts = Discount::with('product')->latest()->paginate(10);
        
        return view('discounts.index', compact('discounts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Only managers and admins can create discounts
        if (!Auth::user()->isManager() && !Auth::user()->isAdmin()) {
            abort(403);
        }
        
        $products = Product::all();
        
        return view('discounts.create', compact('products'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Only managers and admins can store discounts
        if (!Auth::user()->isManager() && !Auth::user()->isAdmin()) {
            abort(403);
        }
        
        $request->validate([
            'code' => 'required|string|unique:discounts,code',
            'description' => 'nullable|string',
            'type' => 'required|in:percentage,fixed',
            'value' => 'required|numeric|min:0',
            'product_id' => 'nullable|exists:products,id',
            'starts_at' => 'nullable|date',
            'expires_at' => 'nullable|date|after_or_equal:starts_at',
            'max_uses' => 'nullable|integer|min:1',
            'is_active' => 'boolean',
        ]);
        
        Discount::create([
            'code' => strtoupper($request->code),
            'description' => $request->description,
            'type' => $request->type,
            'value' => $request->value,
            'product_id' => $request->product_id,
            'starts_at' => $request->starts_at,
            'expires_at' => $request->expires_at,
            'max_uses' => $request->max_uses,
            'used_count' => 0,
            'is_active' => $request->is_active ?? true,
        ]);
        
        return redirect()->route('admin.discounts.index')
            ->with('success', 'Kode diskon berhasil dibuat.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Discount $discount)
    {
        // Only managers and admins can edit discounts
        if (!Auth::user()->isManager() && !Auth::user()->isAdmin()) {
            abort(403);
        }
        
        $products = Product::all();
        
        return view('discounts.edit', compact('discount', 'products'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Discount $discount)
    {
        // Only managers and admins can update discounts
        if (!Auth::user()->isManager() && !Auth::user()->isAdmin()) {
            abort(403);
        }
        
        $request->validate([
            'code' => 'required|string|unique:discounts,code,' . $discount->id,
            'description' => 'nullable|string',
            'type' => 'required|in:percentage,fixed',
            'value' => 'required|numeric|min:0',
            'product_id' => 'nullable|exists:products,id',
            'starts_at' => 'nullable|date',
            'expires_at' => 'nullable|date|after_or_equal:starts_at',
            'max_uses' => 'nullable|integer|min:1',
            'is_active' => 'boolean',
        ]);
        
        $discount->update([
            'code' => strtoupper($request->code),
            'description' => $request->description,
            'type' => $request->type,
            'value' => $request->value,
            'product_id' => $request->product_id,
            'starts_at' => $request->starts_at,
            'expires_at' => $request->expires_at,
            'max_uses' => $request->max_uses,
            'is_active' => $request->is_active ?? true,
        ]);
        
        return redirect()->route('admin.discounts.index')
            ->with('success', 'Kode diskon berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Discount $discount)
    {
        // Only managers and admins can delete discounts
        if (!Auth::user()->isManager() && !Auth::user()->isAdmin()) {
            abort(403);
        }
        
        $discount->delete();
        
        return redirect()->route('admin.discounts.index')
            ->with('success', 'Kode diskon berhasil dihapus.');
    }
    
    /**
     * Validate a discount code for a product.
     */
    public function validateCode(Request $request)
    {
        $request->validate([
            'code' => 'required|string',
            'product_id' => 'required|exists:products,id',
        ]);
        
        $product = Product::findOrFail($request->product_id);
        
        $discount = Discount::where('code', $request->code)
            ->where('is_active', true)
            ->where(function ($query) use ($product) {
                $query->whereNull('product_id')
                      ->orWhere('product_id', $product->id);
            })
            ->where(function ($query) {
                $query->whereNull('starts_at')
                      ->orWhere('starts_at', '<=', now());
            })
            ->where(function ($query) {
                $query->whereNull('expires_at')
                      ->orWhere('expires_at', '>=', now());
            })
            ->where(function ($query) {
                $query->whereNull('max_uses')
                      ->orWhereRaw('used_count < max_uses');
            })
            ->first();
        
        if (!$discount) {
            return response()->json([
                'valid' => false,
                'message' => 'Kode diskon tidak valid.'
            ]);
        }
        
        // Calculate discount amount
        $price = $product->base_price;
        if ($request->variant_id) {
            $variant = $product->variants()->find($request->variant_id);
            if ($variant) {
                $price += $variant->price_adjustment;
            }
        }
        
        $discountAmount = 0;
        if ($discount->type === 'percentage') {
            $discountAmount = ($discount->value / 100) * $price;
        } else {
            $discountAmount = min($discount->value, $price);
        }
        
        return response()->json([
            'valid' => true,
            'discount' => [
                'code' => $discount->code,
                'type' => $discount->type,
                'value' => $discount->value,
                'discount_amount' => $discountAmount,
                'description' => $discount->description,
            ],
            'message' => 'Kode diskon berhasil diterapkan.'
        ]);
    }
}
