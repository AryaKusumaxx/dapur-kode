@extends('layouts.main')

@section('title', 'Produk')

@push('styles')
<style>
    /* Menggunakan Poppins untuk tampilan yang lebih modern */
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap');

    body {
        font-family: 'Poppins', sans-serif;
        background: #f5f7fa;
    }

    /* Hero Section (Non-aktif - Pastikan ini tidak digunakan lagi) */
    .hero-section {
        display: none;
    }

    /* Page Header Baru */
    .page-header {
        padding: 0.5rem 0;
    }

    .page-header h1 {
        font-size: 2rem;
        font-weight: 700;
        color: #1f2937;
        margin-bottom: 0.25rem;
        line-height: 1.3;
    }

    .page-header p {
        color: #6b7280;
        font-size: 0.95rem;
        max-width: 500px;
        line-height: 1.5;
    }

    /* Filter Section (Desain baru) */
    .filter-section {
        background: #fff;
        border-radius: 14px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
        padding: 1.5rem;
        transition: all 0.3s ease;
    }

    .filter-section:hover {
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.08);
    }

    .custom-select:focus {
        border-color: #2563eb;
        box-shadow: 0 0 0 2px rgba(37, 99, 235, 0.25);
        outline: none;
    }

    /* Product Card */
    .product-card {
        border-radius: 16px;
        background: #fff;
        overflow: hidden;
        box-shadow: 0 8px 20px rgba(0,0,0,0.05);
        display: flex;
        flex-direction: column;
        transition: all 0.3s ease;
    }

    .product-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 16px 30px rgba(0,0,0,0.12);
    }

    .product-image {
        height: 200px;
        object-fit: cover;
        transition: transform 0.5s ease;
    }

    .product-card:hover .product-image {
        transform: scale(1.05);
    }

    .category-badge {
        border-radius: 30px;
        padding: 4px 10px;
        font-size: 0.7rem;
        font-weight: 600;
    }

    /* Price */
    .price-tag {
        color: #111827;
        font-weight: 700;
        font-size: 1.15rem;
    }

    /* Buttons */
    .btn-custom {
        border-radius: 10px;
        transition: all 0.3s ease;
        font-size: 0.9rem;
    }

    .btn-custom:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(0,0,0,0.12);
    }

    /* Loading bar */
    .loading-bar {
        display: none;
        height: 4px;
        width: 100%;
        position: relative;
        overflow: hidden;
        background-color: #e5e7eb;
        border-radius: 3px;
    }

    .loading-bar:before {
        display: block;
        position: absolute;
        content: "";
        left: -200px;
        width: 200px;
        height: 4px;
        background-color: #2563eb;
        animation: loading 2s linear infinite;
    }

    @keyframes loading {
        from {left: -200px; width: 30%;}
        to {left: 100%; width: 30%;}
    }
    
</style>
@endpush

@section('header')
    <header class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-6">
        <div class="page-header">
            <h1 class="text-3xl font-bold text-gray-900 leading-snug">Produk Kami</h1>
            <p class="text-md text-gray-600 max-w-xl mt-1">Temukan paket website, jasa pemasangan, dan source code lepas yang siap mendukung kebutuhan bisnis Anda dengan tampilan profesional.</p>
        </div>
    </header>
@endsection

@section('content')
    <main class="max-w-7xl mx-auto px-4 py-8 sm:px-6 lg:px-8">

        <div id="loadingBar" class="loading-bar mb-6"></div>

        <div class="filter-section p-5 mb-8">
    <form action="{{ route('products.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-5 gap-4 items-end">
        
        <!-- Dropdown Kategori -->
        <div class="md:col-span-2">
            <label for="category" class="block text-sm font-medium text-gray-700 mb-1">Kategori</label>
            <div class="relative inline-block w-full">
                <button type="button" id="categoryBtn"
                    class="custom-select w-full flex justify-between items-center px-4 py-2 bg-white border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-400 transition-all duration-300">
                    <span id="categoryText">
                        {{ request('category') ? ucfirst(str_replace('_', ' ', request('category'))) : 'Pilih Kategori' }}
                    </span>
                    <svg class="w-5 h-5 text-gray-500 transform transition-transform duration-300" id="categoryIcon"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
                <ul id="categoryMenu"
                    class="absolute left-0 mt-2 w-full bg-white border border-gray-200 rounded-lg shadow-lg opacity-0 scale-y-0 transform origin-top transition-all duration-300 z-10">
                    <li class="dropdown-item px-4 py-2 hover:bg-blue-100 cursor-pointer" data-value="">Semua Kategori</li>
                    <li class="dropdown-item px-4 py-2 hover:bg-blue-100 cursor-pointer" data-value="paket">Paket Website</li>
                    <li class="dropdown-item px-4 py-2 hover:bg-blue-100 cursor-pointer" data-value="jasa_pasang">Jasa Pasang</li>
                    <li class="dropdown-item px-4 py-2 hover:bg-blue-100 cursor-pointer" data-value="lepas">Source Code Lepas</li>
                </ul>
                <!-- hidden input agar tetap bisa dikirim ke server -->
                <input type="hidden" name="category" id="categoryInput" value="{{ request('category') }}">
            </div>
        </div>

        <!-- Dropdown Urutkan -->
        <div class="md:col-span-2">
            <label for="sort" class="block text-sm font-medium text-gray-700 mb-1">Urutkan</label>
            <div class="relative inline-block w-full">
                <button type="button" id="sortBtn"
                    class="custom-select w-full flex justify-between items-center px-4 py-2 bg-white border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-400 transition-all duration-300">
                    <span id="sortText">
                        @php
                            $sortMap = [
                                'latest' => 'Terbaru',
                                'price_low' => 'Harga Terendah',
                                'price_high' => 'Harga Tertinggi',
                                'name_asc' => 'Nama (A-Z)',
                            ];
                        @endphp
                        {{ $sortMap[request('sort')] ?? 'Pilih Urutan' }}
                    </span>
                    <svg class="w-5 h-5 text-gray-500 transform transition-transform duration-300" id="sortIcon"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
                <ul id="sortMenu"
                    class="absolute left-0 mt-2 w-full bg-white border border-gray-200 rounded-lg shadow-lg opacity-0 scale-y-0 transform origin-top transition-all duration-300 z-10">
                    <li class="dropdown-item px-4 py-2 hover:bg-blue-100 cursor-pointer" data-value="latest">Terbaru</li>
                    <li class="dropdown-item px-4 py-2 hover:bg-blue-100 cursor-pointer" data-value="price_low">Harga Terendah</li>
                    <li class="dropdown-item px-4 py-2 hover:bg-blue-100 cursor-pointer" data-value="price_high">Harga Tertinggi</li>
                    <li class="dropdown-item px-4 py-2 hover:bg-blue-100 cursor-pointer" data-value="name_asc">Nama (A-Z)</li>
                </ul>
                <input type="hidden" name="sort" id="sortInput" value="{{ request('sort') }}">
            </div>
        </div>

        <!-- Tombol Submit -->
        <div>
            <button type="submit" class="btn-custom px-4 py-2 w-full bg-blue-600 text-white font-medium hover:bg-blue-700 h-10">
                Terapkan
            </button>
        </div>
    </form>
</div>

@push('styles')
<style>
    .custom-select { transition: all 0.3s ease; }
    #categoryMenu.show, #sortMenu.show {
        opacity: 1;
        transform: scaleY(1);
    }
</style>
@endpush

@push('scripts')
<script>
    function setupDropdown(btnId, menuId, iconId, textId, inputId) {
        const btn = document.getElementById(btnId);
        const menu = document.getElementById(menuId);
        const icon = document.getElementById(iconId);
        const text = document.getElementById(textId);
        const input = document.getElementById(inputId);

        btn.addEventListener("click", () => {
            menu.classList.toggle("show");
            icon.classList.toggle("rotate-180");
        });

        menu.querySelectorAll(".dropdown-item").forEach(item => {
            item.addEventListener("click", () => {
                text.textContent = item.textContent;
                input.value = item.dataset.value;
                menu.classList.remove("show");
                icon.classList.remove("rotate-180");
            });
        });

        window.addEventListener("click", (e) => {
            if (!btn.contains(e.target) && !menu.contains(e.target)) {
                menu.classList.remove("show");
                icon.classList.remove("rotate-180");
            }
        });
    }

    setupDropdown("categoryBtn", "categoryMenu", "categoryIcon", "categoryText", "categoryInput");
    setupDropdown("sortBtn", "sortMenu", "sortIcon", "sortText", "sortInput");
</script>
@endpush


        <div class="flex justify-between items-center mb-6">
            <p class="text-gray-600">Menampilkan <span class="font-semibold">{{ $products->count() }}</span> produk</p>
            <div class="flex items-center space-x-2">
                <span class="text-sm text-gray-600">Tampilan:</span>
                <button id="gridView" class="p-2 rounded-lg bg-blue-600 text-white transition-colors duration-200">
                    <i class="fas fa-th"></i>
                </button>
                <button id="listView" class="p-2 rounded-lg bg-gray-200 text-gray-700 transition-colors duration-200">
                    <i class="fas fa-list"></i>
                </button>
            </div>
        </div>

        <div id="productContainer" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($products as $product)
                <div class="product-card">
                    <div class="relative overflow-hidden product-image-wrapper">
                        <img src="{{ $product->featured_image ? asset('storage/' . $product->featured_image) : asset('images/product-placeholder.jpg') }}" 
                                alt="{{ $product->name }}" class="product-image w-full">
                        <span class="category-badge absolute top-3 right-3 
                                {{ $product->type === 'paket' ? 'bg-blue-100 text-blue-800' : 
                                ($product->type === 'jasa_pasang' ? 'bg-green-100 text-green-800' : 'bg-purple-100 text-purple-800') }}">
                            {{ $product->type === 'paket' ? 'Paket' : 
                                ($product->type === 'jasa_pasang' ? 'Jasa Pasang' : 'Source Code') }}
                        </span>
                    </div>
                    <div class="p-5 flex-1 flex flex-col">
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">
                            <a href="{{ route('products.show', $product) }}" class="hover:text-blue-600">{{ $product->name }}</a>
                        </h3>
                        <p class="text-sm text-gray-600 mb-4 flex-1">{{ Str::limit($product->short_description, 100) }}</p>
                        <div class="mt-auto">
                            <div class="flex items-center justify-between mb-3">
                                <div class="price-tag">Rp {{ number_format($product->base_price, 0, ',', '.') }}</div>
                                <div class="flex items-center">
                                    <i class="fas fa-star text-yellow-400 mr-1"></i>
                                    <span class="text-sm text-gray-600">4.8</span>
                                </div>
                            </div>
                            <div class="grid grid-cols-2 gap-2">
                                <a href="{{ route('products.show', $product) }}" class="btn-custom inline-flex items-center justify-center px-3 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700">
                                    <i class="fas fa-eye mr-2"></i> Detail
                                </a>
                                <button class="btn-custom inline-flex items-center justify-center px-3 py-2 text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200">
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
                    <a href="{{ route('products.index') }}" class="btn-custom px-4 py-2 bg-blue-600 rounded-md font-medium text-white hover:bg-blue-700">
                        Reset Pencarian
                    </a>
                </div>
            @endforelse
        </div>

        <div class="mt-8 flex justify-center">
            {{ $products->links() }}
        </div>
    </main>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const gridViewBtn = document.getElementById('gridView');
    const listViewBtn = document.getElementById('listView');
    const productContainer = document.getElementById('productContainer');

    if (gridViewBtn && listViewBtn && productContainer) {
        // Set default view from localStorage or to grid
        const savedView = localStorage.getItem('productView') || 'grid';
        if (savedView === 'list') {
            applyListView();
        } else {
            applyGridView();
        }

        gridViewBtn.addEventListener('click', function() {
            applyGridView();
            localStorage.setItem('productView', 'grid');
        });

        listViewBtn.addEventListener('click', function() {
            applyListView();
            localStorage.setItem('productView', 'list');
        });
    }

    function applyGridView() {
        productContainer.className = 'grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6';
        gridViewBtn.classList.add('bg-blue-600', 'text-white');
        listViewBtn.classList.remove('bg-blue-600', 'text-white');
        listViewBtn.classList.add('bg-gray-200', 'text-gray-700');
        const cards = document.querySelectorAll('.product-card');
        cards.forEach(card => {
            card.classList.remove('flex-row', 'md:gap-6', 'items-start');
            const imageWrapper = card.querySelector('.product-image-wrapper');
            if (imageWrapper) {
                imageWrapper.classList.remove('md:w-1/3', 'lg:w-1/4', 'xl:w-1/5', 'shrink-0');
            }
            const image = card.querySelector('.product-image-wrapper img');
            if (image) {
                image.classList.remove('h-48', 'rounded-lg');
                image.classList.add('product-image');
            }
        });
    }

    function applyListView() {
        productContainer.className = 'grid grid-cols-1 gap-6'; // Hanya satu kolom
        listViewBtn.classList.add('bg-blue-600', 'text-white');
        gridViewBtn.classList.remove('bg-blue-600', 'text-white');
        gridViewBtn.classList.add('bg-gray-200', 'text-gray-700');

        const cards = document.querySelectorAll('.product-card');
        cards.forEach(card => {
            card.classList.add('flex-row', 'md:gap-6', 'items-start');
            const imageWrapper = card.querySelector('.product-image-wrapper');
            if (imageWrapper) {
                imageWrapper.classList.add('w-full', 'md:w-1/3', 'lg:w-1/4', 'xl:w-1/5', 'shrink-0');
            }
            const image = card.querySelector('.product-image-wrapper img');
            if (image) {
                image.classList.remove('product-image');
                image.classList.add('h-48', 'w-full', 'object-cover', 'rounded-lg');
            }
        });
    }

    const filterForm = document.querySelector('form');
    const loadingBar = document.getElementById('loadingBar');
    if (filterForm && loadingBar) {
        filterForm.addEventListener('submit', function() {
            loadingBar.style.display = 'block';
        });
    }
});
</script>
@endpush
