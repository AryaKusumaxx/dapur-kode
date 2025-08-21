@extends('layouts.main')

@section('title', 'Detail Invoice')

@section('header')
    <h1 class="text-3xl font-bold text-gray-900">
        Invoice #{{ $invoice->invoice_number }}
    </h1>
    <p class="mt-2 text-sm text-gray-600">
        Tanggal: {{ $invoice->created_at->format('d M Y') }} | Status: 
        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
            @if($invoice->status == 'paid') bg-green-100 text-green-700
            @elseif($invoice->status == 'overdue') bg-red-100 text-red-700
            @else bg-yellow-100 text-yellow-700 @endif">
            {{ ucfirst($invoice->status) }}
        </span>
    </p>
@endsection

@section('content')
    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
        <div class="p-6">
            <div class="flex justify-between mb-8">
                <div>
                    <div class="text-gray-700">
                        <h2 class="font-bold text-lg">Dari</h2>
                        <p>{{ config('app.name') }}</p>
                        <p>{{ \App\Models\Setting::getValue('company_address', 'Alamat Perusahaan') }}</p>
                        <p>{{ \App\Models\Setting::getValue('company_email', 'info@dapurkode.com') }}</p>
                        <p>{{ \App\Models\Setting::getValue('company_phone', '0812-3456-7890') }}</p>
                    </div>
                </div>
                <div>
                    <div class="text-gray-700 text-right">
                        <h2 class="font-bold text-lg">Untuk</h2>
                        <p>{{ $invoice->order->user->name }}</p>
                        <p>{{ $invoice->order->user->email }}</p>
                        @if($invoice->order->user->phone)
                            <p>{{ $invoice->order->user->phone }}</p>
                        @endif
                        @if($invoice->order->user->address)
                            <p>{{ $invoice->order->user->address }}</p>
                        @endif
                    </div>
                </div>
            </div>

            <div class="mb-8">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">Detail Pesanan</h2>
                <div class="overflow-x-auto">
                    <table class="min-w-full bg-white">
                        <thead>
                            <tr class="bg-gray-200 text-gray-600 uppercase text-sm leading-normal">
                                <th class="py-3 px-6 text-left">Produk</th>
                                <th class="py-3 px-6 text-center">Jumlah</th>
                                <th class="py-3 px-6 text-right">Harga Satuan</th>
                                <th class="py-3 px-6 text-right">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-600 text-sm">
                            @foreach($invoice->order->items as $item)
                                <tr class="border-b border-gray-200 hover:bg-gray-100">
                                    <td class="py-3 px-6 text-left">
                                        <div>
                                            <div class="font-medium">{{ $item->name }}</div>
                                            @if($item->product && $item->product->type == 'paket')
                                                <div class="text-xs text-gray-500">Paket</div>
                                            @elseif($item->product && $item->product->type == 'jasa_pasang')
                                                <div class="text-xs text-gray-500">Jasa Pemasangan</div>
                                            @elseif($item->product && $item->product->type == 'lepas')
                                                <div class="text-xs text-gray-500">Produk Lepas</div>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="py-3 px-6 text-center">{{ $item->quantity }}</td>
                                    <td class="py-3 px-6 text-right">Rp {{ number_format($item->unit_price, 0, ',', '.') }}</td>
                                    <td class="py-3 px-6 text-right">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="flex justify-between mb-8">
                <div class="w-1/2">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4">Instruksi Pembayaran</h2>
                    <div class="bg-gray-50 p-4 rounded-md">
                        {!! $invoice->payment_instructions !!}
                    </div>
                </div>
                <div class="w-1/3">
                    <div class="bg-gray-50 p-4 rounded-md">
                        <div class="flex justify-between mb-2">
                            <div class="text-gray-600">Subtotal:</div>
                            <div class="text-gray-800 font-medium">Rp {{ number_format($invoice->order->subtotal, 0, ',', '.') }}</div>
                        </div>
                        @if($invoice->order->discount > 0)
                            <div class="flex justify-between mb-2">
                                <div class="text-gray-600">Diskon:</div>
                                <div class="text-green-600 font-medium">- Rp {{ number_format($invoice->order->discount, 0, ',', '.') }}</div>
                            </div>
                        @endif
                        @if($invoice->order->tax > 0)
                            <div class="flex justify-between mb-2">
                                <div class="text-gray-600">Pajak:</div>
                                <div class="text-gray-800 font-medium">Rp {{ number_format($invoice->order->tax, 0, ',', '.') }}</div>
                            </div>
                        @endif
                        <div class="flex justify-between mb-2 pt-2 border-t border-gray-300">
                            <div class="text-gray-600 font-semibold">Total:</div>
                            <div class="text-gray-800 font-bold">Rp {{ number_format($invoice->order->total, 0, ',', '.') }}</div>
                        </div>
                        <div class="flex justify-between mb-2">
                            <div class="text-gray-600">Terbayar:</div>
                            <div class="text-green-600 font-medium">Rp {{ number_format($invoice->total_paid, 0, ',', '.') }}</div>
                        </div>
                        @if($invoice->remaining_balance > 0)
                            <div class="flex justify-between pt-2 border-t border-gray-300">
                                <div class="text-gray-600 font-semibold">Sisa:</div>
                                <div class="text-red-600 font-bold">Rp {{ number_format($invoice->remaining_balance, 0, ',', '.') }}</div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="mb-8">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">Pembayaran</h2>
                @if($invoice->payments->isNotEmpty())
                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white">
                            <thead>
                                <tr class="bg-gray-200 text-gray-600 uppercase text-sm leading-normal">
                                    <th class="py-3 px-6 text-left">ID</th>
                                    <th class="py-3 px-6 text-left">Tanggal</th>
                                    <th class="py-3 px-6 text-left">Metode</th>
                                    <th class="py-3 px-6 text-right">Jumlah</th>
                                    <th class="py-3 px-6 text-center">Status</th>
                                    <th class="py-3 px-6 text-center">Bukti</th>
                                </tr>
                            </thead>
                            <tbody class="text-gray-600 text-sm">
                                @foreach($invoice->payments as $payment)
                                    <tr class="border-b border-gray-200 hover:bg-gray-100">
                                        <td class="py-3 px-6 text-left">#{{ $payment->id }}</td>
                                        <td class="py-3 px-6 text-left">{{ $payment->created_at->format('d M Y H:i') }}</td>
                                        <td class="py-3 px-6 text-left">{{ ucfirst(str_replace('_', ' ', $payment->payment_method)) }}</td>
                                        <td class="py-3 px-6 text-right">Rp {{ number_format($payment->amount, 0, ',', '.') }}</td>
                                        <td class="py-3 px-6 text-center">
                                            <span class="px-2 py-1 text-xs rounded-full 
                                                @if($payment->status == 'verified') bg-green-100 text-green-700 
                                                @elseif($payment->status == 'rejected') bg-red-100 text-red-700
                                                @else bg-yellow-100 text-yellow-700 @endif">
                                                {{ ucfirst($payment->status) }}
                                            </span>
                                        </td>
                                        <td class="py-3 px-6 text-center">
                                            @if($payment->proof_file)
                                                <a href="{{ $payment->proof_file_url }}" target="_blank" class="text-indigo-600 hover:text-indigo-900">Lihat</a>
                                            @else
                                                -
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="bg-yellow-50 p-4 rounded-md text-yellow-700">
                        Belum ada pembayaran untuk invoice ini.
                    </div>
                @endif
            </div>

            <div class="flex justify-between mt-8">
                <div>
                    <a href="{{ route('orders.show', $invoice->order) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Kembali ke Order
                    </a>
                </div>
                <div class="space-x-2">
                    <a href="{{ route('invoices.download', $invoice) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                        </svg>
                        Download PDF
                    </a>
                    
                    @if($invoice->status != 'paid' && $invoice->remaining_balance > 0)
                        <a href="{{ route('payments.create', $invoice) }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path>
                            </svg>
                            Bayar Sekarang
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
