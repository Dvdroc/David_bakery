@extends('admin.layout')

@section('content')
    <div class="mb-8">
        <h2 class="text-2xl font-bold text-gray-800">Rekap Penjualan</h2>
        <p class="text-sm text-gray-500">Arsip transaksi yang telah selesai diproses.</p>
    </div>

    <div class="flex flex-wrap gap-4 mb-8">
        <div class="relative">
            <select class="appearance-none bg-white border border-gray-300 text-gray-700 py-2.5 px-4 pr-10 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 cursor-pointer">
                <option>Status Pembayaran (Paid)</option>
                <option>Unpaid</option>
            </select>
            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-gray-500">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
            </div>
        </div>

        <div class="relative">
            <select class="appearance-none bg-white border border-gray-300 text-gray-700 py-2.5 px-4 pr-10 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 cursor-pointer">
                <option>Tanggal (Hari Ini)</option>
                <option>Bulan Ini</option>
            </select>
            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-gray-500">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                {{-- Header Tabel --}}
                <thead class="bg-gray-50 text-gray-500 text-xs uppercase border-b border-gray-100">
                    <tr>
                        <th class="px-6 py-4 font-semibold">Faktur</th>
                        {{-- PERUBAHAN: Menambahkan Kolom Dibuat & Diambil --}}
                        <th class="px-6 py-4 font-semibold">Dibuat</th>
                        <th class="px-6 py-4 font-semibold">Diambil</th>
                        <th class="px-6 py-4 font-semibold">Pembayaran</th>
                        <th class="px-6 py-4 font-semibold text-center">Jml Item</th>
                        <th class="px-6 py-4 font-semibold">Status</th>
                        <th class="px-6 py-4 font-semibold text-right">Nama</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-sm">
                    @forelse($completedOrders as $order)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 font-bold text-gray-900">#APM-{{ $order->id }}</td>
                        
                        {{-- Data Waktu Pembuatan --}}
                        <td class="px-6 py-4">
                            <div class="font-medium text-gray-900">{{ $order->created_at->format('d M Y') }}</div>
                            <div class="text-xs text-gray-500">{{ $order->created_at->format('H:i') }} WIB</div>
                        </td>

                        {{-- Data Waktu Pengambilan --}}
                        <td class="px-6 py-4">
                            <div class="font-medium text-gray-900">{{ \Carbon\Carbon::parse($order->pickup_date)->format('d M Y') }}</div>
                            <div class="text-xs text-gray-500">
                                {{ $order->pickup_time ? substr($order->pickup_time, 0, 5) . ' WIB' : '-' }}
                            </div>
                        </td>

                        <td class="px-6 py-4 text-gray-500">Tunai</td>
                        
                        <td class="px-6 py-4 text-center font-medium text-gray-700">
                            {{ $order->orderItems->sum('quantity') }} pcs
                        </td>
                        
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 bg-green-100 text-green-700 rounded-full text-xs font-bold border border-green-200">
                                Selesai
                            </span>
                        </td>
                        
                        <td class="px-6 py-4 text-right font-medium text-gray-800">
                            {{ $order->user->name ?? 'Guest' }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-10 text-center text-gray-500">
                            <div class="flex flex-col items-center justify-center">
                                <svg class="w-10 h-10 text-gray-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                <p>Belum ada transaksi yang selesai.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection