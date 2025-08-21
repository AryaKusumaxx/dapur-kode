@extends('layouts.main')

@section('title', 'Produk')

@section('header')
    <h1 class="text-3xl font-bold text-gray-900">
        Produk
    </h1>
    <p class="mt-2 text-sm text-gray-600">Pilih produk yang sesuai dengan kebutuhan Anda</p>
@endsection

@section('content')
    <!-- Product Filters -->
    <div class="mb-8 bg-white rounded-lg shadow-sm p-4">
        <form action="{{ route('products.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label for="search" class="block text-sm font-medium text-gray-700">Cari</label>
                <input type="text" name="search" id="search" value="{{ request('search') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" placeholder="Cari produk...">
            </div>
            
            <div>
                <label for="category" class="block text-sm font-medium text-gray-700">Kategori</label>
                <select name="category" id="category" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    <option value="">Semua Kategori</option>
                    <option value="paket" {{ request('category') === 'paket' ? 'selected' : '' }}>Paket Website</option>
                    <option value="jasa_pasang" {{ request('category') === 'jasa_pasang' ? 'selected' : '' }}>Jasa Pasang</option>
                    <option value="lepas" {{ request('category') === 'lepas' ? 'selected' : '' }}>Source Code Lepas</option>
                </select>
            </div>
            
            <div>
                <label for="sort" class="block text-sm font-medium text-gray-700">Urutkan</label>
                <select name="sort" id="sort" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    <option value="latest" {{ request('sort') === 'latest' ? 'selected' : '' }}>Terbaru</option>
                    <option value="price_low" {{ request('sort') === 'price_low' ? 'selected' : '' }}>Harga Terendah</option>
                    <option value="price_high" {{ request('sort') === 'price_high' ? 'selected' : '' }}>Harga Tertinggi</option>
                    <option value="name_asc" {{ request('sort') === 'name_asc' ? 'selected' : '' }}>Nama (A-Z)</option>
                </select>
            </div>
            
            <div class="flex items-end">
                <button type="submit" class="px-4 py-2 w-full bg-indigo-600 border border-transparent rounded-md font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:text-sm">
                    Filter
                </button>
            </div>
        </form>
    </div>

    <!-- Product List -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($products as $product)
            <div class="bg-white rounded-lg shadow-sm overflow-hidden flex flex-col">
                <img src="{{ $product->featured_image ? asset('storage/' . $product->featured_image) : asset('images/product-placeholder.jpg') }}" 
                     alt="{{ $product->name }}" 
                     class="w-full h-48 object-cover">
                     
                <div class="p-4 flex-1 flex flex-col">
                    <div class="flex justify-between items-start">
                        <h3 class="text-lg font-semibold text-gray-900">
                            <a href="{{ route('products.show', $product) }}" class="hover:text-indigo-600">
                                {{ $product->name }}
                            </a>
                        </h3>
                        
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                            {{ $product->type === 'paket' ? 'bg-blue-100 text-blue-800' : 
                              ($product->type === 'jasa_pasang' ? 'bg-green-100 text-green-800' : 'bg-purple-100 text-purple-800') }}">
                            {{ $product->type === 'paket' ? 'Paket' : 
                               ($product->type === 'jasa_pasang' ? 'Jasa Pasang' : 'Source Code') }}
                        </span>
                    </div>
                    
                    <p class="mt-2 text-sm text-gray-600 flex-1">
                        {{ Str::limit($product->short_description, 100) }}
                    </p>
                    
                    <div class="mt-4">
                        <div class="flex items-center justify-between">
                            <div class="text-xl font-bold text-gray-900">
                                Rp {{ number_format($product->base_price, 0, ',', '.') }}
                            </div>
                            
                            <a href="{{ route('products.show', $product) }}" 
                               class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Detail
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full bg-white rounded-lg shadow-sm p-6 text-center">
                <p class="text-gray-500">Tidak ada produk yang ditemukan.</p>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="mt-8">
        {{ $products->links() }}
    </div>
@endsection
