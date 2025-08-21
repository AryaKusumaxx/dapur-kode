@extends('layouts.main')

@section('title', 'Upload Bukti Pembayaran')

@section('header')
    <h1 class="text-3xl font-bold text-gray-900">
        Upload Bukti Pembayaran
    </h1>
    <p class="mt-2 text-sm text-gray-600">Invoice #{{ $invoice->invoice_number }}</p>
@endsection

@section('content')
    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h2 class="text-lg font-semibold text-gray-800 mb-4">Detail Invoice</h2>
                    <div class="bg-gray-50 p-4 rounded-md">
                        <p class="mb-2"><span class="font-medium">Invoice:</span> #{{ $invoice->invoice_number }}</p>
                        <p class="mb-2"><span class="font-medium">Order:</span> #{{ $invoice->order->order_number }}</p>
                        <p class="mb-2"><span class="font-medium">Tanggal:</span> {{ $invoice->created_at->format('d M Y') }}</p>
                        <p class="mb-2"><span class="font-medium">Jatuh Tempo:</span> {{ $invoice->due_date->format('d M Y') }}</p>
                        <p class="mb-2"><span class="font-medium">Status:</span> 
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                @if($invoice->status == 'paid') bg-green-100 text-green-800
                                @elseif($invoice->status == 'overdue') bg-red-100 text-red-800
                                @else bg-yellow-100 text-yellow-800 @endif">
                                {{ ucfirst($invoice->status) }}
                            </span>
                        </p>
                        <p class="mb-2"><span class="font-medium">Total:</span> Rp {{ number_format($invoice->order->total, 0, ',', '.') }}</p>
                        <p class="mb-2"><span class="font-medium">Terbayar:</span> Rp {{ number_format($invoice->total_paid, 0, ',', '.') }}</p>
                        <p class="mb-2"><span class="font-medium">Sisa:</span> Rp {{ number_format($invoice->remaining_balance, 0, ',', '.') }}</p>
                    </div>
                </div>
                
                <div>
                    <h2 class="text-lg font-semibold text-gray-800 mb-4">Instruksi Pembayaran</h2>
                    <div class="bg-gray-50 p-4 rounded-md">
                        {!! $invoice->payment_instructions !!}
                    </div>
                </div>
            </div>
            
            <div class="mt-8">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">Upload Bukti Pembayaran</h2>
                
                <form action="{{ route('payments.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    <input type="hidden" name="invoice_id" value="{{ $invoice->id }}">
                    
                    <div>
                        <label for="payment_method" class="block text-sm font-medium text-gray-700">Metode Pembayaran</label>
                        <select id="payment_method" name="payment_method" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                            <option value="bank_transfer">Transfer Bank</option>
                            <option value="e_wallet">E-Wallet</option>
                        </select>
                    </div>
                    
                    <div>
                        <label for="amount" class="block text-sm font-medium text-gray-700">Jumlah (Rp)</label>
                        <input type="number" name="amount" id="amount" value="{{ $invoice->remaining_balance }}" min="1" max="{{ $invoice->remaining_balance }}" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                    </div>
                    
                    <div>
                        <label for="transaction_id" class="block text-sm font-medium text-gray-700">ID Transaksi</label>
                        <input type="text" name="transaction_id" id="transaction_id" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" placeholder="Opsional">
                        <p class="mt-1 text-sm text-gray-500">ID transaksi, nomor referensi, atau informasi lain terkait pembayaran Anda.</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Bukti Pembayaran</label>
                        <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md">
                            <div class="space-y-1 text-center">
                                <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                    <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                <div class="flex text-sm text-gray-600">
                                    <label for="proof_file" class="relative cursor-pointer bg-white rounded-md font-medium text-indigo-600 hover:text-indigo-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-indigo-500">
                                        <span>Upload bukti pembayaran</span>
                                        <input id="proof_file" name="proof_file" type="file" class="sr-only" accept="image/*" required>
                                    </label>
                                    <p class="pl-1">atau drag and drop</p>
                                </div>
                                <p class="text-xs text-gray-500">
                                    PNG, JPG, GIF up to 10MB
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    <div>
                        <label for="notes" class="block text-sm font-medium text-gray-700">Catatan</label>
                        <textarea id="notes" name="notes" rows="3" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" placeholder="Informasi tambahan terkait pembayaran (opsional)"></textarea>
                    </div>
                    
                    <div class="flex justify-end">
                        <a href="{{ route('invoices.show', $invoice) }}" class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 mr-2">
                            Kembali
                        </a>
                        <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Upload Bukti Pembayaran
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
