@extends('layouts.admin')

@section('title', 'Verifikasi Pembayaran')

@section('header', 'Verifikasi Pembayaran')

@section('admin-content')
    <div class="bg-white shadow-md rounded my-6">
        <table class="min-w-full bg-white">
            <thead>
                <tr class="bg-gray-200 text-gray-600 uppercase text-sm leading-normal">
                    <th class="py-3 px-6 text-left">Invoice</th>
                    <th class="py-3 px-6 text-left">Pelanggan</th>
                    <th class="py-3 px-6 text-left">Jumlah</th>
                    <th class="py-3 px-6 text-left">Tanggal</th>
                    <th class="py-3 px-6 text-left">Metode</th>
                    <th class="py-3 px-6 text-center">Status</th>
                    <th class="py-3 px-6 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="text-gray-600 text-sm">
                @forelse($pendingPayments as $payment)
                    <tr class="border-b border-gray-200 hover:bg-gray-100">
                        <td class="py-3 px-6 text-left">
                            <a href="{{ route('admin.invoices.show', $payment->invoice) }}" class="text-blue-600 hover:text-blue-800">
                                {{ $payment->invoice->invoice_number }}
                            </a>
                        </td>
                        <td class="py-3 px-6 text-left">
                            {{ $payment->invoice->order->user->name }}
                        </td>
                        <td class="py-3 px-6 text-left">
                            Rp {{ number_format($payment->amount, 0, ',', '.') }}
                        </td>
                        <td class="py-3 px-6 text-left">
                            {{ $payment->created_at->format('d M Y H:i') }}
                        </td>
                        <td class="py-3 px-6 text-left">
                            {{ $payment->payment_method }}
                        </td>
                        <td class="py-3 px-6 text-center">
                            <span class="bg-yellow-200 text-yellow-600 py-1 px-3 rounded-full text-xs">
                                {{ ucfirst($payment->status) }}
                            </span>
                        </td>
                        <td class="py-3 px-6 text-center">
                            <div class="flex item-center justify-center space-x-2">
                                <a href="{{ route('admin.payments.show', $payment) }}" class="text-blue-600 hover:text-blue-900 mr-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </a>
                                
                                <form action="{{ route('admin.payments.verify', $payment) }}" method="POST" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="text-green-600 hover:text-green-900" onclick="return confirm('Verifikasi pembayaran ini?')">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                        </svg>
                                    </button>
                                </form>
                                
                                <form action="{{ route('admin.payments.reject', $payment) }}" method="POST" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Tolak pembayaran ini?')">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="py-4 px-6 text-center">
                            Tidak ada pembayaran yang menunggu verifikasi.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <div class="mt-4">
        {{ $pendingPayments->links() }}
    </div>
@endsection
