@extends('layouts.admin')

@section('title', 'Tambah Produk')

@section('header', 'Tambah Produk Baru')

@section('admin-content')
    <div class="max-w-3xl mx-auto">
        <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="mb-6">
                <label for="name" class="block text-sm font-medium text-gray-700">Nama Produk</label>
                <input type="text" name="name" id="name" value="{{ old('name') }}" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" required>
                @error('name')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <div class="mb-6">
                <label for="description" class="block text-sm font-medium text-gray-700">Deskripsi</label>
                <textarea name="description" id="description" rows="5" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">{{ old('description') }}</textarea>
                @error('description')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label for="type" class="block text-sm font-medium text-gray-700">Tipe Produk</label>
                    <select name="type" id="type" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        <option value="paket" {{ old('type') == 'paket' ? 'selected' : '' }}>Paket</option>
                        <option value="jasa_pasang" {{ old('type') == 'jasa_pasang' ? 'selected' : '' }}>Jasa Pasang</option>
                        <option value="lepas" {{ old('type') == 'lepas' ? 'selected' : '' }}>Lepas</option>
                    </select>
                    @error('type')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="base_price" class="block text-sm font-medium text-gray-700">Harga Dasar (Rp)</label>
                    <input type="number" name="base_price" id="base_price" value="{{ old('base_price') }}" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" min="0" required>
                    @error('base_price')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            
            <div class="mb-6">
                <div class="flex items-start">
                    <div class="flex items-center h-5">
                        <input id="has_warranty" name="has_warranty" type="checkbox" value="1" {{ old('has_warranty') ? 'checked' : '' }} class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded">
                    </div>
                    <div class="ml-3 text-sm">
                        <label for="has_warranty" class="font-medium text-gray-700">Produk Memiliki Garansi</label>
                        <p class="text-gray-500">Centang jika produk ini menawarkan garansi</p>
                    </div>
                </div>
                @error('has_warranty')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <div id="warranty_section" class="mb-6 p-4 bg-gray-50 rounded-md border border-gray-200 {{ old('has_warranty') ? '' : 'hidden' }}">
                <h3 class="font-medium text-gray-700 mb-3">Harga Garansi</h3>
                
                <div class="mb-4 grid grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Durasi (bulan)</label>
                        <input type="number" name="warranty_months[]" value="6" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" min="1">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Harga (Rp)</label>
                        <input type="number" name="warranty_prices[]" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" min="0">
                    </div>
                    <div class="flex items-center mt-5">
                        <input type="radio" name="is_default_warranty" value="0" checked class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300">
                        <span class="ml-2 text-sm text-gray-700">Default</span>
                    </div>
                </div>
                
                <div class="mb-4 grid grid-cols-3 gap-4">
                    <div>
                        <input type="number" name="warranty_months[]" value="12" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" min="1">
                    </div>
                    <div>
                        <input type="number" name="warranty_prices[]" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" min="0">
                    </div>
                    <div class="flex items-center">
                        <input type="radio" name="is_default_warranty" value="1" class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300">
                        <span class="ml-2 text-sm text-gray-700">Default</span>
                    </div>
                </div>
                
                <div id="warranty_template" class="hidden mb-4 grid grid-cols-3 gap-4">
                    <div>
                        <input type="number" name="warranty_months[]" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" min="1">
                    </div>
                    <div>
                        <input type="number" name="warranty_prices[]" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" min="0">
                    </div>
                    <div class="flex items-center">
                        <input type="radio" name="is_default_warranty" class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300">
                        <span class="ml-2 text-sm text-gray-700">Default</span>
                        <button type="button" class="ml-auto remove-warranty text-red-600 hover:text-red-900">
                            <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    </div>
                </div>
                
                <button type="button" id="add_warranty" class="mt-2 inline-flex items-center px-2 py-1 border border-transparent text-xs leading-4 font-medium rounded text-indigo-700 bg-indigo-100 hover:bg-indigo-200 focus:outline-none focus:border-indigo-300 focus:shadow-outline-indigo active:bg-indigo-200 transition ease-in-out duration-150">
                    Tambah Opsi Garansi
                </button>
            </div>
            
            <div class="mt-8">
                <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Simpan Produk
                </button>
                <a href="{{ route('admin.products.index') }}" class="ml-2 inline-flex justify-center py-2 px-4 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Batal
                </a>
            </div>
        </form>
    </div>
    
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const hasWarrantyCheckbox = document.getElementById('has_warranty');
            const warrantySection = document.getElementById('warranty_section');
            const addWarrantyBtn = document.getElementById('add_warranty');
            const warrantyTemplate = document.getElementById('warranty_template');
            let warrantyCount = 2; // Start with 2 as we already have 2 warranty options
            
            hasWarrantyCheckbox.addEventListener('change', function() {
                if (this.checked) {
                    warrantySection.classList.remove('hidden');
                } else {
                    warrantySection.classList.add('hidden');
                }
            });
            
            addWarrantyBtn.addEventListener('click', function() {
                const newWarranty = warrantyTemplate.cloneNode(true);
                newWarranty.classList.remove('hidden');
                newWarranty.id = '';
                
                const radioInput = newWarranty.querySelector('input[type="radio"]');
                radioInput.value = warrantyCount;
                
                warrantyTemplate.parentNode.insertBefore(newWarranty, warrantyTemplate);
                
                newWarranty.querySelector('.remove-warranty').addEventListener('click', function() {
                    newWarranty.remove();
                });
                
                warrantyCount++;
            });
        });
    </script>
@endsection
