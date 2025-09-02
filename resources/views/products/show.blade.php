@extends('layouts.main')

@section('title', $product->name)

@section('content')
<div class="container mx-auto px-4 py-8 md:py-16">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 md:gap-12 lg:gap-16">

        <div class="md:col-span-2">
            <div class="bg-gray-100 rounded-2xl overflow-hidden shadow-lg p-6 flex justify-center items-center">
                <img src="{{ $product->featured_image ? asset('storage/' . $product->featured_image) : asset('images/product-placeholder.jpg') }}"
                     alt="{{ $product->name }}" 
                     class="w-full h-auto object-contain rounded-xl transition-transform duration-300 transform hover:scale-105">
            </div>

            @if($product->gallery_images && is_string($product->gallery_images) && count(json_decode($product->gallery_images, true) ?: []) > 0)
                <div class="mt-8 grid grid-cols-4 gap-4">
                    @foreach(json_decode($product->gallery_images, true) as $image)
                        <img src="{{ asset('storage/' . $image) }}" 
                             alt="{{ $product->name }}" 
                             class="w-full h-24 object-cover rounded-lg border-2 border-transparent hover:border-indigo-500 cursor-pointer transition-all duration-300">
                    @endforeach
                </div>
            @endif

            <div class="mt-12 p-8 bg-white rounded-2xl shadow-xl">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">Deskripsi Produk</h2>
                <div class="prose max-w-none text-gray-700 leading-relaxed mb-8">
                    {!! $product->description ?? 'Belum ada deskripsi untuk produk ini.' !!}
                </div>
                
                @if(isset($product->features))
                    <h3 class="text-xl font-semibold text-gray-800 mb-4">Fitur Utama</h3>
                    <ul class="space-y-4">
                        @foreach(explode("\n", $product->features) as $feature)
                            @if(!empty(trim($feature)))
                                <li class="flex items-center text-gray-700">
                                    <svg class="h-6 w-6 text-indigo-600 flex-shrink-0 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <span>{{ trim($feature) }}</span>
                                </li>
                            @endif
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>

        <div class="md:col-span-1">
            <div class="sticky top-8 space-y-8">
                <div class="p-8 bg-white rounded-2xl shadow-xl">
                    <span class="inline-flex items-center px-4 py-1 rounded-full text-sm font-semibold tracking-wide 
                        {{ $product->type === 'paket' ? 'bg-blue-100 text-blue-800' : 
                            ($product->type === 'jasa_pasang' ? 'bg-green-100 text-green-800' : 'bg-purple-100 text-purple-800') }}">
                        {{ $product->type === 'paket' ? 'Paket Website' : 
                            ($product->type === 'jasa_pasang' ? 'Jasa Pasang' : 'Source Code Lepas') }}
                    </span>
                    
                    <h1 class="mt-4 text-3xl font-extrabold text-gray-900 leading-tight">
                        {{ $product->name }}
                    </h1>
                    
                    <div class="mt-4 text-4xl font-extrabold text-indigo-600">
                        Rp {{ number_format($product->base_price, 0, ',', '.') }}
                    </div>
                </div>

                <div class="p-8 bg-white rounded-2xl shadow-xl">
                    <form action="{{ route('orders.store') }}" method="POST" id="orderForm" class="space-y-6">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">

                        @if($product->variants->count() > 0)
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Pilih Varian</label>
                                <select name="variant_id" id="variant_id" class="block w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm p-3">
                                    <option value="">-- Pilih Varian --</option>
                                    @foreach($product->variants as $variant)
                                        <option value="{{ $variant->id }}" data-price="{{ $variant->price_adjustment }}">
                                            {{ $variant->name }} 
                                            ({{ $variant->price_adjustment > 0 ? '+' : '' }}Rp {{ number_format($variant->price_adjustment, 0, ',', '.') }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        @endif

                        @if($product->warrantyPrices && $product->warrantyPrices->count() > 0)
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Garansi</label>
                                <select name="warranty_months" id="warranty_months" class="block w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm p-3">
                                    <option value="0">Tanpa Garansi</option>
                                    @foreach($product->warrantyPrices as $warranty)
                                        <option value="{{ $warranty->months }}" data-price="{{ $warranty->price }}">
                                            {{ $warranty->months }} Bulan 
                                            (Rp {{ number_format($warranty->price, 0, ',', '.') }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        @endif

                        <div>
                            <label for="discount_code" class="block text-sm font-semibold text-gray-700 mb-2">Kode Diskon</label>
                            <div class="flex rounded-lg overflow-hidden border border-gray-300 focus-within:border-indigo-500 focus-within:ring-1 focus-within:ring-indigo-500">
                                <input type="text" name="discount_code" id="discount_code" placeholder="Masukkan kode" 
                                       class="flex-1 px-4 py-2 border-none focus:ring-0 focus:border-0 sm:text-sm">
                                <button type="button" id="apply-discount" 
                                        class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none transition">
                                    Terapkan
                                </button>
                            </div>
                            <div id="discount-message" class="mt-1 text-sm"></div>
                        </div>

                        <div class="p-6 bg-gray-50 rounded-xl space-y-3">
                            <div class="flex justify-between items-center text-sm text-gray-600">
                                <span>Harga Produk</span>
                                <span id="base-price" class="font-medium">Rp {{ number_format($product->base_price, 0, ',', '.') }}</span>
                            </div>
                            
                            <div class="variant-price-row hidden flex justify-between items-center text-sm text-gray-600">
                                <span>Varian</span>
                                <span id="variant-price" class="font-medium">Rp 0</span>
                            </div>

                            <div class="warranty-price-row hidden flex justify-between items-center text-sm text-gray-600">
                                <span>Garansi</span>
                                <span id="warranty-price" class="font-medium">Rp 0</span>
                            </div>

                            <div class="discount-row hidden flex justify-between items-center text-sm text-green-600">
                                <span>Diskon</span>
                                <span id="discount-amount" class="font-medium">-Rp 0</span>
                            </div>

                            <div class="border-t border-gray-200 pt-3 flex justify-between items-center text-lg font-bold">
                                <span class="text-gray-900">Total</span>
                                <span id="total-price" class="text-indigo-600">Rp {{ number_format($product->base_price, 0, ',', '.') }}</span>
                            </div>

                            <input type="hidden" name="total_price" id="total-price-input" value="{{ $product->base_price }}">
                            <input type="hidden" name="discount_amount" id="discount-amount-input" value="0">
                        </div>

                        <div>
                            @auth
                                <button type="submit" 
                                        class="w-full py-4 rounded-xl bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-bold shadow-lg hover:from-indigo-700 hover:to-purple-700 focus:outline-none focus:ring-4 focus:ring-indigo-500 focus:ring-opacity-50 transition-all duration-300">
                                    Beli Sekarang
                                </button>
                            @else
                                <a href="{{ route('login') }}" 
                                   class="block w-full py-4 rounded-xl bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-bold shadow-lg hover:from-indigo-700 hover:to-purple-700 focus:outline-none focus:ring-4 focus:ring-indigo-500 focus:ring-opacity-50 transition-all duration-300 text-center">
                                    Login untuk Membeli
                                </a>
                            @endauth
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
