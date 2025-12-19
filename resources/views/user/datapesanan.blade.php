<x-app-layout>
<div class="max-w-4xl mx-auto pt-24 pb-12 px-4">
    <h1 class="text-3xl font-bold text-[#ee2b5c] mb-6">Daftar Pesanan</h1>

    @if(session('success'))
        <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg">
            {{ session('error') }}
        </div>
    @endif

    <div class="overflow-x-auto bg-white rounded-xl shadow-lg border border-gray-100">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-[#f3e7ea] text-[#1b0d11]">
                <tr>
                    <th class="px-6 py-4 text-left text-sm font-bold uppercase tracking-wider">Tanggal</th>
                    <th class="px-6 py-4 text-left text-sm font-bold uppercase tracking-wider">Produk</th>
                    <th class="px-6 py-4 text-left text-sm font-bold uppercase tracking-wider">Jumlah</th>
                    <th class="px-6 py-4 text-left text-sm font-bold uppercase tracking-wider">Status</th>
                    <th class="px-6 py-4 text-left text-sm font-bold uppercase tracking-wider">Aksi / Ulasan</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($orders as $order)
                    @foreach($order->orderItems as $item)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                {{ \Carbon\Carbon::parse($order->pickup_date)->format('d M Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                {{ $item->product->name }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                {{ $item->quantity }} pcs
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <span class="px-3 py-1 rounded-full text-xs font-bold 
                                    @if($order->status === 'pending') bg-yellow-100 text-yellow-700
                                    @elseif($order->status === 'completed') bg-green-100 text-green-700
                                    @elseif($order->status === 'cancelled') bg-red-100 text-red-700
                                    @else bg-blue-100 text-blue-700 @endif">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                @if($order->status === 'pending')
                                    <form action="{{ route('user.list-pesanan.cancel', $order->id) }}" method="POST" onsubmit="return confirm('Yakin ingin membatalkan pesanan ini?')">
                                        @csrf
                                        <button type="submit" class="text-red-500 hover:text-red-700 font-semibold underline decoration-2">Batal</button>
                                    </form>

                                @elseif($order->status === 'completed')
                                    @if(!$order->rating)
                                        <button onclick="document.getElementById('modal-review-{{ $order->id }}').classList.remove('hidden')" 
                                                class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-1.5 rounded-lg text-xs font-bold shadow-sm transition">
                                            Beri Ulasan
                                        </button>

                                        <div id="modal-review-{{ $order->id }}" class="fixed inset-0 bg-black/60 backdrop-blur-sm hidden flex items-center justify-center z-50 p-4">
                                            <div class="bg-white rounded-2xl w-full max-w-sm overflow-hidden shadow-2xl animate-fade-in">
                                                <div class="p-6">
                                                    <h3 class="text-xl font-bold text-gray-800 mb-2">Ulasan Anda</h3>
                                                    <p class="text-sm text-gray-500 mb-6">Bagaimana kualitas produk dan layanan kami?</p>
                                                    
                                                    <form action="{{ route('orders.review', $order->id) }}" method="POST">
                                                        @csrf
                                                        <div class="mb-6 text-center">
                                                            <div class="flex justify-center gap-2 mb-2">
                                                                @for($i=1; $i<=5; $i++)
                                                                    <label class="cursor-pointer">
                                                                        <input type="radio" name="rating" value="{{ $i }}" class="hidden peer" required>
                                                                        <span class="text-4xl text-gray-300 peer-checked:text-yellow-400 hover:text-yellow-300 transition-colors">★</span>
                                                                    </label>
                                                                @endfor
                                                            </div>
                                                            <p class="text-xs text-gray-400 italic">Pilih bintang untuk memberi nilai</p>
                                                        </div>

                                                        <div class="mb-6">
                                                            <textarea name="review" 
                                                                    class="w-full border-gray-200 rounded-xl p-4 text-sm focus:ring-[#ee2b5c] focus:border-[#ee2b5c] transition-all" 
                                                                    rows="4" 
                                                                    placeholder="Tulis pendapat Anda tentang kue ini..." 
                                                                    required></textarea>
                                                        </div>

                                                        <div class="flex flex-col gap-2">
                                                            <button type="submit" class="w-full bg-[#ee2b5c] text-white py-3 rounded-xl font-bold hover:bg-red-700 shadow-lg shadow-red-200 transition">Kirim Ulasan</button>
                                                            <button type="button" onclick="this.closest('.fixed').classList.add('hidden')" class="w-full py-2 text-sm text-gray-500 font-medium hover:text-gray-700 transition">Tutup</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        <div class="flex flex-col">
                                            <div class="flex text-yellow-400 text-lg">
                                                @for($i=1; $i<=5; $i++)
                                                    <span>{{ $i <= $order->rating ? '★' : '☆' }}</span>
                                                @endfor
                                            </div>
                                            <p class="text-[11px] text-gray-500 italic mt-0.5 max-w-[150px] truncate" title="{{ $order->review }}">
                                                "{{ $order->review }}"
                                            </p>
                                        </div>
                                    @endif
                                @else
                                    <span class="text-gray-400 italic text-xs">Menunggu Selesai</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                            <div class="flex flex-col items-center">
                                <svg class="w-12 h-12 text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                </svg>
                                <p class="text-lg font-medium">Belum ada pesanan.</p>
                                <a href="{{ route('dashboard') }}" class="mt-2 text-[#ee2b5c] hover:underline">Ayo pesan kue sekarang!</a>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<style>
    @keyframes fade-in {
        from { opacity: 0; transform: scale(0.95); }
        to { opacity: 1; transform: scale(1); }
    }
    .animate-fade-in {
        animation: fade-in 0.2s ease-out;
    }
</style>
</x-app-layout>