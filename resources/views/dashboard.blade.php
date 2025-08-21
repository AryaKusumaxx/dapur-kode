@extends('layouts.main')

@section('title', 'Dashboard')

@section('header')
    <h1 class="text-3xl font-bold text-gray-900">
        Dashboard
    </h1>
    <p class="mt-2 text-sm text-gray-600">Selamat datang, {{ Auth::user()->name }}!</p>
@endsection

@section('content')
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <!-- Quick Stats -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-indigo-100 text-indigo-600">
                    <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <h2 class="font-semibold text-gray-600">Total Pembelian</h2>
                    <p class="text-2xl font-bold text-gray-900">{{ $totalOrders }}</p>
                </div>
            </div>
            <div class="mt-4">
                <a href="{{ route('orders.index') }}" class="text-indigo-600 hover:text-indigo-800 text-sm font-medium">Lihat semua pembelian →</a>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100 text-green-600">
                    <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <h2 class="font-semibold text-gray-600">Garansi Aktif</h2>
                    <p class="text-2xl font-bold text-gray-900">{{ $activeWarranties }}</p>
                </div>
            </div>
            <div class="mt-4">
                <a href="{{ route('warranties.index') }}" class="text-indigo-600 hover:text-indigo-800 text-sm font-medium">Lihat semua garansi →</a>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-yellow-100 text-yellow-600">
                    <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <h2 class="font-semibold text-gray-600">Menunggu Pembayaran</h2>
                    <p class="text-2xl font-bold text-gray-900">{{ $pendingPayments }}</p>
                </div>
            </div>
            <div class="mt-4">
                <a href="{{ route('orders.index', ['status' => 'pending']) }}" class="text-indigo-600 hover:text-indigo-800 text-sm font-medium">Lihat pesanan tertunda →</a>
            </div>
        </div>
    </div>
    
    <!-- Recent Orders -->
    <div class="bg-white rounded-lg shadow-sm overflow-hidden mb-8">
        <div class="p-6 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">Pesanan Terbaru</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID Pesanan</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Produk</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($recentOrders as $order)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                #{{ $order->id }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                {{ $order->items->first()->product->name }}
                                @if($order->items->count() > 1)
                                    <span class="text-gray-500 text-xs">(+{{ $order->items->count() - 1 }} lainnya)</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                Rp {{ number_format($order->total_price, 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    {{ $order->status === 'completed' ? 'bg-green-100 text-green-800' : 
                                       ($order->status === 'processing' ? 'bg-blue-100 text-blue-800' : 
                                       ($order->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800')) }}">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $order->created_at->format('d M Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <a href="{{ route('orders.show', $order) }}" class="text-indigo-600 hover:text-indigo-900">Detail</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500">
                                Tidak ada pesanan terbaru.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 bg-gray-50 border-t border-gray-200">
            <a href="{{ route('orders.index') }}" class="text-indigo-600 hover:text-indigo-800 text-sm font-medium">Lihat semua pesanan →</a>
        </div>
    </div>
    
    <!-- Active Warranties -->
    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
        <div class="p-6 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">Garansi Aktif</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Produk</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Masa Berlaku</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($activeWarrantiesList as $warranty)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                {{ $warranty->order_item->product->name }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                {{ $warranty->start_date->format('d M Y') }} - {{ $warranty->end_date->format('d M Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                    $daysLeft = now()->diffInDays($warranty->end_date, false);
                                @endphp
                                
                                @if($daysLeft > 30)
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                        Aktif
                                    </span>
                                @elseif($daysLeft > 0)
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                        Segera Berakhir ({{ $daysLeft }} hari lagi)
                                    </span>
                                @else
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                        Berakhir
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <a href="{{ route('warranties.show', $warranty) }}" class="text-indigo-600 hover:text-indigo-900">Detail</a>
                                
                                @if($daysLeft > 0)
                                    <a href="{{ route('warranties.download', $warranty) }}" class="ml-4 text-indigo-600 hover:text-indigo-900">Unduh Sertifikat</a>
                                @endif
                                
                                @if($daysLeft < 30 && $daysLeft > 0)
                                    <a href="#" onclick="document.getElementById('extendWarranty{{ $warranty->id }}').classList.remove('hidden')" class="ml-4 text-green-600 hover:text-green-900">Perpanjang</a>
                                    
                                    <div id="extendWarranty{{ $warranty->id }}" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full" style="z-index: 20;">
                                        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                                            <div class="mt-3 text-center">
                                                <h3 class="text-lg leading-6 font-medium text-gray-900">Perpanjang Garansi</h3>
                                                <div class="mt-2 px-7 py-3">
                                                    <p class="text-sm text-gray-500">
                                                        Perpanjang garansi produk {{ $warranty->order_item->product->name }} Anda.
                                                    </p>
                                                </div>
                                                <form action="{{ route('warranties.extend', $warranty) }}" method="POST">
                                                    @csrf
                                                    <div class="mt-2 px-7 py-3">
                                                        <div class="mb-4">
                                                            <label for="extension_months" class="block text-sm font-medium text-gray-700 text-left mb-2">Durasi Perpanjangan</label>
                                                            <select name="extension_months" id="extension_months" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                                                @foreach([3, 6, 12] as $months)
                                                                    <option value="{{ $months }}">{{ $months }} Bulan</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="items-center px-4 py-3">
                                                        <button type="button" onclick="document.getElementById('extendWarranty{{ $warranty->id }}').classList.add('hidden')" class="px-4 py-2 bg-white text-gray-700 border border-gray-300 rounded-md mr-2 text-sm">
                                                            Batal
                                                        </button>
                                                        <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 text-sm">
                                                            Perpanjang Garansi
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-4 text-center text-sm text-gray-500">
                                Tidak ada garansi aktif.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 bg-gray-50 border-t border-gray-200">
            <a href="{{ route('warranties.index') }}" class="text-indigo-600 hover:text-indigo-800 text-sm font-medium">Lihat semua garansi →</a>
        </div>
    </div>
@endsection
