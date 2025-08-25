@extends('layouts.main')

@section('title', 'Produk')

@push('styles')
<style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f5f7fa;
        }
        
        .product-card {
            transition: all 0.3s ease;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        }
        
        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }
        
        .product-image {
            height: 200px;
            transition: transform 0.5s ease;
        }
        
        .product-card:hover .product-image {
            transform: scale(1.05);
        }
        
        .category-badge {
            transition: all 0.3s ease;
        }
        
        .pagination .page-item.active .page-link {
            background-color: #4f46e5;
            border-color: #4f46e5;
            color: white;
        }
        
        .pagination .page-link {
            color: #4f46e5;
        }
        
        .filter-section {
            border-radius: 12px;
            background: linear-gradient(to right, #ffffff, #f8fafc);
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        }
        
        .price-tag {
            background: linear-gradient(to right, #4f46e5, #6366f1);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            font-weight: 700;
        }
        
        .hero-section {
            background: linear-gradient(120deg, #4f46e5 0%, #7c3aed 100%);
            border-radius: 12px;
        }
        
        .animate-float {
            animation: float 3s ease-in-out infinite;
        }
        
        @keyframes float {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
            100% { transform: translateY(0px); }
        }
        
        .loading-bar {
            display: none;
            height: 3px;
            width: 100%;
            position: relative;
            overflow: hidden;
            background-color: #e5e7eb;
        }
        
        .loading-bar:before {
            display: block;
            position: absolute;
            content: "";
            left: -200px;
            width: 200px;
            height: 3px;
            background-color: #4f46e5;
            animation: loading 2s linear infinite;
        }
        
        @keyframes loading {
            from {left: -200px; width: 30%;}
            to {left: 100%; width: 30%;}
        }
</style>
@endpush

@section('header')
    <header class="bg-white shadow-sm">
        <div class="max-w-7xl mx-auto px-4 py-4 sm:px-6 lg:px-8 flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Produk Kami</h1>
                <p class="opacity-90">Kami menyediakan berbagai paket website, jasa pemasangan, dan source code lepas untuk kebutuhan bisnis Anda.</p>
            </div>
            <div class="flex items-center">
                <span class="bg-primary rounded-full p-2 animate-float">
                    <i class="fas fa-box-open text-white text-lg"></i>
                </span>
            </div>
        </div>
    </header>
@endsection

@section('content')
    <main class="max-w-7xl mx-auto px-4 py-8 sm:px-6 lg:px-8">

        <!-- Loading Bar -->
        <div id="loadingBar" class="loading-bar mb-6"></div>

        <!-- Product Filters -->
        <div class="filter-section p-5 mb-8">
            <form action="{{ route('products.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-5 gap-4">
                <div>
                    <label for="category" class="block text-sm font-medium text-gray-700 mb-1">Kategori</label>
                    <div class="relative w-full">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-tag text-gray-400"></i>
                        </div>
                        <select name="category" id="category" class=" pl-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary sm:text-sm py-2">
                            <option value="">Semua Kategori</option>
                            <option value="paket" {{ request('category') === 'paket' ? 'selected' : '' }}>Paket Website</option>
                            <option value="jasa_pasang" {{ request('category') === 'jasa_pasang' ? 'selected' : '' }}>Jasa Pasang</option>
                            <option value="lepas" {{ request('category') === 'lepas' ? 'selected' : '' }}>Source Code Lepas</option>
                        </select>
                    </div>
                </div>
                
                <div>
                    <label for="sort" class="block text-sm font-medium text-gray-700 mb-1">Urutkan</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-sort text-gray-400"></i>
                        </div>
                        <select name="sort" id="sort" class="pl-10 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary sm:text-sm py-2">
                            <option value="latest" {{ request('sort') === 'latest' ? 'selected' : '' }}>Terbaru</option>
                            <option value="price_low" {{ request('sort') === 'price_low' ? 'selected' : '' }}>Harga Terendah</option>
                            <option value="price_high" {{ request('sort') === 'price_high' ? 'selected' : '' }}>Harga Tertinggi</option>
                            <option value="name_asc" {{ request('sort') === 'name_asc' ? 'selected' : '' }}>Nama (A-Z)</option>
                        </select>
                    </div>
                </div>
                
                <button type="submit" class="px-4 py-2 w-full bg-indigo-600 border border-transparent rounded-md font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:text-sm">
                    Filter
                </button>
            </form>
        </div>

        <!-- Product Count & View Toggle -->
        <div class="flex justify-between items-center mb-6">
            <p class="text-gray-600">Menampilkan <span class="font-semibold">{{ $products->count() }}</span> produk</p>
            <div class="flex items-center">
                <span class="text-sm text-gray-600 mr-2">Tampilan:</span>
                <button id="gridView" class="p-2 rounded-lg bg-primary text-white mr-2">
                    <i class="fas fa-th"></i>
                </button>
                <button id="listView" class="p-2 rounded-lg bg-gray-200 text-gray-700">
                    <i class="fas fa-list"></i>
                </button>
            </div>
        </div>

        <!-- Product List -->
        <div id="productContainer" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($products as $product)
                <div class="product-card bg-white flex flex-col">
                    <div class="relative overflow-hidden">
                        <img src="{{ $product->featured_image ? asset('storage/' . $product->featured_image) : asset('images/product-placeholder.jpg') }}" 
                             alt="{{ $product->name }}" 
                             class="product-image w-full object-cover">
                        <span class="category-badge absolute top-3 right-3 inline-flex items-center px-3 py-1 rounded-full text-xs font-medium 
                            {{ $product->type === 'paket' ? 'bg-blue-100 text-blue-800' : 
                              ($product->type === 'jasa_pasang' ? 'bg-green-100 text-green-800' : 'bg-purple-100 text-purple-800') }}">
                            {{ $product->type === 'paket' ? 'Paket' : 
                               ($product->type === 'jasa_pasang' ? 'Jasa Pasang' : 'Source Code') }}
                        </span>
                    </div>
                    
                    <div class="p-5 flex-1 flex flex-col">
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">
                            <a href="{{ route('products.show', $product) }}" class="hover:text-primary transition-colors">
                                {{ $product->name }}
                            </a>
                        </h3>
                        
                        <p class="text-sm text-gray-600 mb-4 flex-1">
                            {{ Str::limit($product->short_description, 100) }}
                        </p>
                        
                        <div class="mt-auto">
                            <div class="flex items-center justify-between mb-3">
                                <div class="price-tag text-xl font-bold">
                                    Rp {{ number_format($product->base_price, 0, ',', '.') }}
                                </div>
                                
                                <div class="flex items-center">
                                    <i class="fas fa-star text-yellow-400 mr-1"></i>
                                    <span class="text-sm text-gray-600">4.8</span>
                                </div>
                            </div>
                            
                            <div class="grid grid-cols-2 gap-2">
                                <a href="{{ route('products.show', $product) }}" 
                                   class="inline-flex items-center justify-center px-3 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-primary hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary">
                                    <i class="fas fa-eye mr-2"></i> Detail
                                </a>
                                <button class="inline-flex items-center justify-center px-3 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary">
                                    <i class="fas fa-shopping-cart mr-2"></i> Beli
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full bg-white rounded-lg shadow-sm p-8 text-center">
                    <i class="fas fa-search fa-3x text-gray-300 mb-4"></i>
                    <h3 class="text-xl font-medium text-gray-700 mb-2">Tidak ada produk yang ditemukan</h3>
                    <p class="text-gray-500 mb-4">Coba gunakan kata kunci atau filter yang berbeda</p>
                    <a href="{{ route('products.index') }}" class="px-4 py-2 bg-primary border border-transparent rounded-md font-medium text-white hover:bg-indigo-700">
                        Reset Pencarian
                    </a>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        <div class="mt-8 flex justify-center">
            {{ $products->links() }}
        </div>
    </main>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Toggle Grid/List View
    const gridViewBtn = document.getElementById('gridView');
    const listViewBtn = document.getElementById('listView');
    const productContainer = document.getElementById('productContainer');

    if (gridViewBtn && listViewBtn && productContainer) {
        gridViewBtn.addEventListener('click', function() {
            productContainer.className = 'grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6';
            gridViewBtn.classList.add('bg-primary', 'text-white');
            gridViewBtn.classList.remove('bg-gray-200', 'text-gray-700');
            listViewBtn.classList.add('bg-gray-200', 'text-gray-700');
            listViewBtn.classList.remove('bg-primary', 'text-white');

            // Reset cards to grid view
            const cards = document.querySelectorAll('.product-card');
            cards.forEach(card => {
                card.classList.remove('flex-row');
                const img = card.querySelector('.product-image');
                if (img) img.classList.remove('w-1/3');
            });
        });

        listViewBtn.addEventListener('click', function() {
            productContainer.className = 'grid grid-cols-1 gap-6';
            listViewBtn.classList.add('bg-primary', 'text-white');
            listViewBtn.classList.remove('bg-gray-200', 'text-gray-700');
            gridViewBtn.classList.add('bg-gray-200', 'text-gray-700');
            gridViewBtn.classList.remove('bg-primary', 'text-white');

            // Convert cards to list view
            const cards = document.querySelectorAll('.product-card');
            cards.forEach(card => {
                card.classList.add('flex-row');
                const img = card.querySelector('.product-image');
                if (img) img.classList.add('w-1/3');
            });
        });
    }

    // Show loading bar on filter form submission
    const filterForm = document.querySelector('form');
    const loadingBar = document.getElementById('loadingBar');
    if (filterForm && loadingBar) {
        filterForm.addEventListener('submit', function() {
            loadingBar.style.display = 'block';
        });
    }

    // Submit filter form otomatis saat kategori dipilih
    const categorySelect = document.getElementById('category');
    if (categorySelect && filterForm) {
        categorySelect.addEventListener('change', function() {
            filterForm.submit();
        });
    }

    // Initialize tooltips if needed (Bootstrap)
    if (window.bootstrap) {
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    }
});
</script>
@endpush