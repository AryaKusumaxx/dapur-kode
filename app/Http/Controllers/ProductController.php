<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::latest()->paginate(10);
        
        return view('products.index', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Check if user is admin or manager using gate
        if (!Gate::allows('admin-access')) {
            return redirect()->route('dashboard')
                ->with('error', 'Anda tidak memiliki akses untuk membuat produk.');
        }
        
        return view('products.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Check if user is admin or manager using gate
        if (!Gate::allows('admin-access')) {
            return redirect()->route('dashboard')
                ->with('error', 'Anda tidak memiliki akses untuk membuat produk.');
        }
        
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'type' => 'required|in:paket,jasa_pasang,lepas',
            'base_price' => 'required|numeric|min:0',
            'has_warranty' => 'boolean',
        ]);
        
        $product = Product::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
            'type' => $request->type,
            'base_price' => $request->base_price,
            'has_warranty' => $request->has_warranty ?? false,
        ]);
        
        return redirect()->route('products.index')->with('success', 'Produk berhasil dibuat.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        $product->load('variants', 'warrantyPrices', 'activeDiscounts');
        
        return view('products.show', compact('product'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        $product->load('variants', 'warrantyPrices');
        
        return view('products.edit', compact('product'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'type' => 'required|in:paket,jasa_pasang,lepas',
            'base_price' => 'required|numeric|min:0',
            'has_warranty' => 'boolean',
        ]);
        
        $product->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
            'type' => $request->type,
            'base_price' => $request->base_price,
            'has_warranty' => $request->has_warranty ?? false,
        ]);
        
        return redirect()->route('products.index')->with('success', 'Produk berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        $product->delete();
        
        return redirect()->route('products.index')->with('success', 'Produk berhasil dihapus.');
    }
    
    /**
     * Display a listing of products for public viewing.
     */
    public function catalog()
    {
        $products = Product::latest()->paginate(12);
        
        return view('products.catalog', compact('products'));
    }
    
    /**
     * Display a product detail for public viewing.
     */
    public function detail($slug)
    {
        $product = Product::where('slug', $slug)
            ->with('variants', 'warrantyPrices')
            ->firstOrFail();
        
        return view('products.detail', compact('product'));
    }
    
    /**
     * Show the checkout form for a product.
     */
    public function checkout($slug)
    {
        $product = Product::where('slug', $slug)
            ->with('variants', 'warrantyPrices')
            ->firstOrFail();
        
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu untuk melakukan pembelian.');
        }
        
        return view('products.checkout', compact('product'));
    }
}
