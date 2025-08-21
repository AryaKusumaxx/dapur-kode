@extends('layouts.admin')

@section('title', 'Detail Pembayaran')

@section('header', 'Detail Pembayaran')

@section('admin-content')
    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <div class="p-6">
            <div class="flex justify-between">
                <h2 class="text-xl font-bold text-gray-800">Invoice #{{ $payment->invoice->invoice_number }}</h2>
                <div>
                    <span class="px-4 py-1 rounded-full text-sm 
                        @if($payment->status == 'pending') bg-yellow-100 text-yellow-800 
                        @elseif($payment->status == 'verified') bg-green-100 text-green-800 
                        @else bg-red-100 text-red-800 @endif">
                        {{ ucfirst($payment->status) }}
                    </span>
                </div>
            </div>
            
            <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h3 class="text-md font-semibold text-gray-700 mb-2">Informasi Pembayaran</h3>
                    <div class="bg-gray-50 p-4 rounded-md">
                        <p class="mb-2"><span class="font-medium">Metode:</span> {{ $payment->payment_method }}</p>
                        <p class="mb-2"><span class="font-medium">Jumlah:</span> Rp {{ number_format($payment->amount, 0, ',', '.') }}</p>
                        <p class="mb-2"><span class="font-medium">Tanggal:</span> {{ $payment->created_at->format('d M Y H:i') }}</p>
                        <p class="mb-2"><span class="font-medium">ID Transaksi:</span> {{ $payment->transaction_id ?? 'Tidak ada' }}</p>
                        
                        @if($payment->status != 'pending')
                            <p class="mb-2"><span class="font-medium">Diverifikasi Oleh:</span> {{ $payment->verifier->name ?? 'Sistem' }}</p>
                            <p class="mb-2"><span class="font-medium">Diverifikasi Pada:</span> {{ $payment->verified_at ? $payment->verified_at->format('d M Y H:i') : '-' }}</p>
                        @endif
                        
                        @if($payment->notes)
                            <p class="mb-2"><span class="font-medium">Catatan:</span> {{ $payment->notes }}</p>
                        @endif
                    </div>
                </div>
                
                <div>
                    <h3 class="text-md font-semibold text-gray-700 mb-2">Informasi Pelanggan</h3>
                    <div class="bg-gray-50 p-4 rounded-md">
                        <p class="mb-2"><span class="font-medium">Nama:</span> {{ $payment->invoice->order->user->name }}</p>
                        <p class="mb-2"><span class="font-medium">Email:</span> {{ $payment->invoice->order->user->email }}</p>
                        <p class="mb-2"><span class="font-medium">Telepon:</span> {{ $payment->invoice->order->user->phone ?? '-' }}</p>
                    </div>
                </div>
            </div>
            
            @if($payment->proof_file)
                <div class="mt-6">
                    <h3 class="text-md font-semibold text-gray-700 mb-2">Bukti Pembayaran</h3>
                    <div class="bg-gray-50 p-4 rounded-md">
                        <img src="{{ $payment->proof_file_url }}" alt="Bukti Pembayaran" class="max-w-full max-h-96 mx-auto rounded">
                    </div>
                </div>
            @endif
            
            @if($payment->status == 'pending')
                <div class="mt-6 flex gap-4">
                    <form action="{{ route('admin.payments.verify', $payment) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 active:bg-green-800 focus:outline-none focus:border-green-800 focus:shadow-outline-green transition ease-in-out duration-150">
                            Verifikasi Pembayaran
                        </button>
                    </form>
                    
                    <form action="{{ route('admin.payments.reject', $payment) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 active:bg-red-800 focus:outline-none focus:border-red-800 focus:shadow-outline-red transition ease-in-out duration-150">
                            Tolak Pembayaran
                        </button>
                    </form>
                </div>
            @endif
        </div>
    </div>
    
    <div class="mt-4">
        <a href="{{ route('admin.payments.index') }}" class="text-indigo-600 hover:text-indigo-800">
            &larr; Kembali ke Daftar Pembayaran
        </a>
    </div>
@endsection
