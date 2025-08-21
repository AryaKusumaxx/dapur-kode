<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class LandingPageController extends Controller
{
    public function index()
    {
        // Get featured products (latest 6 products)
        $featuredProducts = Product::latest()->take(6)->get();
        
        // Count products by type
        $productStats = [
            'paket' => Product::where('type', 'paket')->count(),
            'jasa_pasang' => Product::where('type', 'jasa_pasang')->count(),
            'lepas' => Product::where('type', 'lepas')->count(),
        ];
        
        return view('landing', compact('featuredProducts', 'productStats'));
    }
}
