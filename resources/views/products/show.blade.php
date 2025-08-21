@extends('layouts.main')

@section('title', $product->name)

@section('content')
    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Product Images -->
            <div class="p-6">
                <div class="mb-4">
                    <img src="{{ $product->featured_image ? asset('storage/' . $product->featured_image) : asset('images/product-placeholder.jpg') }}" 
                         alt="{{ $product->name }}" 
                         class="w-full h-auto object-cover rounded-lg">
                </div>
                
                @if($product->gallery_images && is_string($product->gallery_images) && count(json_decode($product->gallery_images, true) ?: []) > 0)
                    <div class="grid grid-cols-4 gap-2">
                        @foreach(json_decode($product->gallery_images, true) as $image)
                            <img src="{{ asset('storage/' . $image) }}" 
                                alt="{{ $product->name }}" 
                                class="w-full h-20 object-cover rounded-lg">
                        @endforeach
                    </div>
                @endif
            </div>
            
            <!-- Product Details -->
            <div class="p-6">
                <div class="flex items-center">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                        {{ $product->type === 'paket' ? 'bg-blue-100 text-blue-800' : 
                          ($product->type === 'jasa_pasang' ? 'bg-green-100 text-green-800' : 'bg-purple-100 text-purple-800') }}">
                        {{ $product->type === 'paket' ? 'Paket Website' : 
                           ($product->type === 'jasa_pasang' ? 'Jasa Pasang' : 'Source Code Lepas') }}
                    </span>
                </div>
                
                <h1 class="mt-2 text-3xl font-bold text-gray-900">{{ $product->name }}</h1>
                
                <div class="mt-4 text-3xl font-bold text-gray-900">
                    Rp {{ number_format($product->base_price, 0, ',', '.') }}
                </div>
                
                <div class="mt-4">
                    <p class="text-gray-600">{{ $product->short_description ?? $product->description ? Str::limit(strip_tags($product->description), 150) : 'Belum ada deskripsi singkat untuk produk ini.' }}</p>
                </div>
                
                <div class="mt-6">
                    <form action="{{ route('orders.store') }}" method="POST" id="orderForm">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        
                        <!-- Product Variants -->
                        @if($product->variants->count() > 0)
                            <div class="mb-6">
                                <label for="variant_id" class="block text-sm font-medium text-gray-700 mb-2">Varian</label>
                                <select name="variant_id" id="variant_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                    <option value="">Pilih Varian</option>
                                    @foreach($product->variants as $variant)
                                        <option value="{{ $variant->id }}" data-price="{{ $variant->price_adjustment }}">
                                            {{ $variant->name }} 
                                            ({{ $variant->price_adjustment > 0 ? '+' : '' }}Rp {{ number_format($variant->price_adjustment, 0, ',', '.') }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        @endif
                        
                        <!-- Warranty Selection -->
                        @if($product->warrantyPrices && $product->warrantyPrices->count() > 0)
                            <div class="mb-6">
                                <label for="warranty_months" class="block text-sm font-medium text-gray-700 mb-2">Garansi</label>
                                <select name="warranty_months" id="warranty_months" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
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
                        
                        <!-- Discount Code -->
                        <div class="mb-6">
                            <label for="discount_code" class="block text-sm font-medium text-gray-700 mb-2">Kode Diskon</label>
                            <div class="flex">
                                <input type="text" name="discount_code" id="discount_code" class="mt-1 block w-full rounded-l-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" placeholder="Masukkan kode diskon">
                                <button type="button" id="apply-discount" class="mt-1 inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-r-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    Terapkan
                                </button>
                            </div>
                            <div id="discount-message" class="mt-2 text-sm"></div>
                        </div>
                        
                        <!-- Total Price -->
                        <div class="mb-6 p-4 bg-gray-50 rounded-lg">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Harga Produk:</span>
                                <span id="base-price" class="font-medium">Rp {{ number_format($product->base_price, 0, ',', '.') }}</span>
                            </div>
                            
                            <div class="variant-price-row" style="display: none">
                                <div class="flex justify-between text-sm mt-2">
                                    <span class="text-gray-600">Varian:</span>
                                    <span id="variant-price" class="font-medium">Rp 0</span>
                                </div>
                            </div>
                            
                            <div class="warranty-price-row" style="display: none">
                                <div class="flex justify-between text-sm mt-2">
                                    <span class="text-gray-600">Garansi:</span>
                                    <span id="warranty-price" class="font-medium">Rp 0</span>
                                </div>
                            </div>
                            
                            <div class="discount-row" style="display: none">
                                <span class="text-gray-600">Diskon:</span>
                                <span id="discount-amount" class="font-medium text-green-600">-Rp 0</span>
                            </div>
                            
                            <div class="flex justify-between text-lg mt-3 pt-3 border-t border-gray-200">
                                <span class="font-medium text-gray-900">Total:</span>
                                <span id="total-price" class="font-bold text-gray-900">Rp {{ number_format($product->base_price, 0, ',', '.') }}</span>
                            </div>
                            <input type="hidden" name="total_price" id="total-price-input" value="{{ $product->base_price }}">
                            <input type="hidden" name="discount_amount" id="discount-amount-input" value="0">
                        </div>
                        
                        <!-- Order Button -->
                        <div class="mt-6">
                            @auth
                                <button type="submit" class="w-full bg-indigo-600 border border-transparent rounded-md py-3 px-8 flex items-center justify-center text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    Beli Sekarang
                                </button>
                            @else
                                <a href="{{ route('login') }}" class="w-full bg-indigo-600 border border-transparent rounded-md py-3 px-8 flex items-center justify-center text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    Login untuk Membeli
                                </a>
                            @endauth
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <!-- Product Description -->
        <div class="p-6 border-t border-gray-200">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">Deskripsi Produk</h2>
            <div class="prose max-w-none">
                {!! $product->description ?? 'Belum ada deskripsi untuk produk ini.' !!}
            </div>
        </div>
        
        <!-- Product Features -->
        @if(isset($product->features))
        <div class="p-6 border-t border-gray-200">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">Fitur</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @foreach(explode("\n", $product->features) as $feature)
                    @if(!empty(trim($feature)))
                        <div class="flex items-start">
                            <svg class="h-5 w-5 text-green-500 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span class="ml-2 text-gray-700">{{ trim($feature) }}</span>
                        </div>
                    @endif
                @endforeach
            </div>
        </div>
        @endif
    </div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const basePrice = {{ $product->base_price }};
        const variantSelect = document.getElementById('variant_id');
        const warrantySelect = document.getElementById('warranty_months');
        const discountCodeInput = document.getElementById('discount_code');
        const applyDiscountBtn = document.getElementById('apply-discount');
        const discountMessage = document.getElementById('discount-message');
        const totalPriceDisplay = document.getElementById('total-price');
        const totalPriceInput = document.getElementById('total-price-input');
        const discountAmountDisplay = document.getElementById('discount-amount');
        const discountAmountInput = document.getElementById('discount-amount-input');
        const variantPriceDisplay = document.getElementById('variant-price');
        const warrantyPriceDisplay = document.getElementById('warranty-price');
        
        let variantPrice = 0;
        let warrantyPrice = 0;
        let discountAmount = 0;
        
        function updateTotalPrice() {
            const total = basePrice + variantPrice + warrantyPrice - discountAmount;
            totalPriceDisplay.textContent = 'Rp ' + total.toLocaleString('id-ID');
            totalPriceInput.value = total;
        }
        
        // Variant selection
        if (variantSelect) {
            variantSelect.addEventListener('change', function() {
                const variantOption = this.options[this.selectedIndex];
                const priceAdjustment = variantOption.dataset.price ? parseInt(variantOption.dataset.price) : 0;
                
                variantPrice = priceAdjustment;
                
                if (priceAdjustment !== 0) {
                    variantPriceDisplay.textContent = (priceAdjustment > 0 ? '+' : '') + 'Rp ' + Math.abs(priceAdjustment).toLocaleString('id-ID');
                    document.querySelector('.variant-price-row').style.display = 'block';
                } else {
                    document.querySelector('.variant-price-row').style.display = 'none';
                }
                
                updateTotalPrice();
                validateDiscount(); // Re-validate discount when variant changes
            });
        }
        
        // Warranty selection
        if (warrantySelect) {
            warrantySelect.addEventListener('change', function() {
                const warrantyOption = this.options[this.selectedIndex];
                const warrantyPriceValue = warrantyOption.dataset.price ? parseInt(warrantyOption.dataset.price) : 0;
                
                warrantyPrice = warrantyPriceValue;
                
                if (warrantyPriceValue > 0) {
                    warrantyPriceDisplay.textContent = 'Rp ' + warrantyPriceValue.toLocaleString('id-ID');
                    document.querySelector('.warranty-price-row').style.display = 'block';
                } else {
                    document.querySelector('.warranty-price-row').style.display = 'none';
                }
                
                updateTotalPrice();
            });
        }
        
        // Apply discount
        if (applyDiscountBtn) {
            applyDiscountBtn.addEventListener('click', validateDiscount);
        }
        
        function validateDiscount() {
            const code = discountCodeInput.value.trim();
            
            if (!code) {
                discountMessage.innerHTML = '<span class="text-red-600">Masukkan kode diskon terlebih dahulu.</span>';
                return;
            }
            
            discountMessage.innerHTML = '<span class="text-gray-600">Memvalidasi kode diskon...</span>';
            
            // Make AJAX request to validate discount
            fetch('{{ route('discounts.validate') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    code: code,
                    product_id: {{ $product->id }},
                    variant_id: variantSelect ? variantSelect.value : null
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.valid) {
                    discountMessage.innerHTML = `<span class="text-green-600">${data.message}</span>`;
                    
                    discountAmount = data.discount.discount_amount;
                    discountAmountDisplay.textContent = '-Rp ' + discountAmount.toLocaleString('id-ID');
                    discountAmountInput.value = discountAmount;
                    
                    document.querySelector('.discount-row').style.display = 'block';
                    updateTotalPrice();
                } else {
                    discountMessage.innerHTML = `<span class="text-red-600">${data.message}</span>`;
                    discountAmount = 0;
                    discountAmountInput.value = 0;
                    document.querySelector('.discount-row').style.display = 'none';
                    updateTotalPrice();
                }
            })
            .catch(error => {
                console.error('Error validating discount:', error);
                discountMessage.innerHTML = '<span class="text-red-600">Terjadi kesalahan saat memvalidasi kode.</span>';
            });
        }
    });
</script>
@endpush
