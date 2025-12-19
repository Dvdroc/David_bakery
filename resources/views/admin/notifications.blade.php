@extends('admin.layout')

@section('content')
    
    <div class="flex justify-between items-end mb-8">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Notifikasi Pesanan</h2>
            <p class="text-sm text-gray-500">Persetujuan pesanan masuk yang perlu diproses.</p>
        </div>
        <button onclick="window.location.reload()" class="text-sm text-orange-600 font-bold hover:text-orange-700 flex items-center gap-1">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
            Refresh Data
        </button>
    </div>

    <div class="space-y-8">
        @forelse($groupedOrders as $kategori => $orders)
            <div class="bg-white rounded-2xl shadow-sm overflow-hidden border border-gray-200">
                <div class="bg-[#606C38] px-6 py-3 flex justify-between items-center">
                    <h3 class="text-white font-bold text-lg tracking-wide">{{ $kategori }}</h3>
                    <span class="bg-white/20 text-white text-xs px-2 py-1 rounded">{{ count($orders) }} Pesanan</span>
                </div>

                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50 text-gray-500 text-xs uppercase tracking-wider border-b border-gray-200">
                            <th class="px-6 py-4 font-semibold">Faktur</th>
                            <th class="px-6 py-4 font-semibold">Waktu Ambil</th>
                            <th class="px-6 py-4 font-semibold">Pembayaran</th>
                            <th class="px-6 py-4 font-semibold text-center">Total Item</th>
                            <th class="px-6 py-4 font-semibold">Nama Pelanggan</th>
                            <th class="px-6 py-4 font-semibold">Status</th>
                            <th class="px-6 py-4 font-semibold text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 text-sm">
                        {{-- PERBAIKAN: Loop langsung menggunakan $order karena Controller mengirim Collection of Orders --}}
                        @foreach($orders as $order)
                        <tr class="hover:bg-gray-50 transition">
                            {{-- Faktur ID --}}
                            <td class="px-6 py-4 font-medium text-gray-900">#APM-{{ $order->id }}</td>
                            
                            {{-- Waktu Ambil Pesanan --}}
                            <td class="px-6 py-4 text-gray-500">
                                {{ \Carbon\Carbon::parse($order->pickup_date)->format('d M, Y') }}
                                <br>
                                {{-- Menangani jika pickup_time null/tidak ada --}}
                                <span class="text-xs">Pukul: {{ $order->pickup_time ? substr($order->pickup_time, 0, 5) : '09:00' }} WIB</span>
                            </td>
                            
                            <td class="px-6 py-4 text-gray-500">Tunai</td>
                            
                            {{-- Jumlah Kuantitas (Sum dari OrderItems) --}}
                            <td class="px-6 py-4 text-center font-bold text-gray-800">
                                {{ $order->orderItems->sum('quantity') }} pcs
                            </td>
                            
                            {{-- Nama User (Safe Access) --}}
                            <td class="px-6 py-4 text-gray-800 font-medium">{{ $order->user->name ?? 'User Tidak Ditemukan' }}</td>
                            
                            {{-- Status Pesanan --}}
                            <td class="px-6 py-4">
                                <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-yellow-100 text-yellow-700 border border-yellow-200">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </td>
                            
                            {{-- Tombol Aksi --}}
                            <td class="px-6 py-4 text-right">
                                <button onclick="openActionModal(
                                    {{ $order->id }}, 
                                    '{{ addslashes($order->user->name ?? 'User') }}', 
                                    '{{ $order->orderItems->sum('quantity') }} item', 
                                    '{{ \Carbon\Carbon::parse($order->pickup_date)->format('d M Y') }}'
                                )" 
                                class="bg-[#8D9F4F] hover:bg-[#606C38] text-white font-bold py-1.5 px-4 rounded-lg shadow-sm transition text-xs uppercase tracking-wider">
                                    Aksi
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @empty
            <div class="p-12 text-center bg-white rounded-2xl border border-dashed border-gray-300">
                <div class="w-16 h-16 bg-green-50 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <h3 class="text-lg font-medium text-gray-900">Tidak Ada Pesanan Pending</h3>
                <p class="text-gray-500 text-sm mt-1">Saat ini tidak ada pesanan baru yang menunggu persetujuan.</p>
            </div>
        @endforelse
    </div>
    
    <div id="actionSelectionModal" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center z-50 backdrop-blur-sm transition-opacity">
        <div class="bg-white rounded-2xl shadow-2xl w-96 p-6 relative text-center transform transition-all scale-100">
            <h3 class="text-xl font-bold text-gray-800 mb-2">Pilih Aksi Pesanan</h3>
            <p class="text-sm text-gray-500 mb-6">Pilih Terima untuk menyetujui, atau Tolak untuk membatalkan pesanan ini.</p>
            
            <p class="text-gray-800 font-medium text-base mb-8 leading-relaxed">
                Pesanan dari <span id="selectionModalNama" class="font-bold text-gray-700">...</span> 
                (<span id="selectionModalJumlah" class="font-bold">...</span>)
                untuk tanggal <span id="selectionModalTanggal" class="font-bold">...</span>.
            </p>
            
            <div class="flex justify-center gap-4">
                <button type="button" onclick="confirmAction('reject')" 
                        class="w-1/2 bg-red-600 hover:bg-red-700 text-white font-bold py-3 rounded-xl shadow-lg transition">
                    Tolak
                </button>
                <button type="button" onclick="confirmAction('approve')" 
                        class="w-1/2 bg-green-600 hover:bg-green-700 text-white font-bold py-3 rounded-xl shadow-lg transition">
                    Terima
                </button>
            </div>
            
            <button onclick="closeActionModal()" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
    </div>

    <div id="confirmModal" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center z-50 backdrop-blur-sm transition-opacity">
        <div class="bg-white rounded-2xl shadow-2xl w-96 p-8 relative text-center transform transition-all scale-100">
            
            <div class="mx-auto mb-6 w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center">
                <svg class="w-8 h-8 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
            </div>
            
            <form id="actionForm" method="POST">
                @csrf
                {{-- Placeholder untuk input hidden status jika reject --}}
                <div id="hiddenInputs"></div>

                <p class="text-gray-800 font-medium text-lg mb-8 leading-relaxed">
                    Apakah Anda yakin ingin <br>
                    <span id="modalActionText" class="font-bold">...</span> <br>
                    pesanan ini?
                </p>
                <div class="flex justify-center gap-4">
                    <button type="button" onclick="closeModal()" class="w-1/2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold py-3 rounded-xl transition">
                        Tidak (Batal)
                    </button>
                    <button type="submit" id="finalActionButton" class="w-1/2 text-white font-bold py-3 rounded-xl shadow-lg transition">
                        Ya, Lanjutkan
                    </button>
                </div>
            </form>

            <button onclick="closeModal()" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
    </div>

    <script>
        let currentOrderId = null;

        function closeModal() {
            document.getElementById('confirmModal').classList.add('hidden');
        }
        
        function closeActionModal() {
            document.getElementById('actionSelectionModal').classList.add('hidden');
        }

        function openActionModal(id, nama, jumlah, tanggal) {
            currentOrderId = id; 
            document.getElementById('selectionModalNama').innerText = nama;
            document.getElementById('selectionModalJumlah').innerText = jumlah;
            document.getElementById('selectionModalTanggal').innerText = tanggal;
            document.getElementById('actionSelectionModal').classList.remove('hidden');
            closeModal(); 
        }

        function confirmAction(action) {
            closeActionModal(); 

            const actionTextElement = document.getElementById('modalActionText');
            const finalActionButton = document.getElementById('finalActionButton');
            const actionForm = document.getElementById('actionForm');
            const hiddenInputs = document.getElementById('hiddenInputs');

            // Reset
            hiddenInputs.innerHTML = '';
            
            let url = '';
            let buttonClass = '';
            let actionDisplay = '';
            let actionTextColor = '';

            if (action === 'approve') {
                actionDisplay = 'MENERIMA';
                actionTextColor = 'text-green-600';
                buttonClass = 'bg-green-600 hover:bg-green-700';
                url = `/admin/orders/${currentOrderId}/approve`;
            } else if (action === 'reject') {
                actionDisplay = 'MENOLAK';
                actionTextColor = 'text-red-600';
                buttonClass = 'bg-red-600 hover:bg-red-700';
                url = `/admin/orders/${currentOrderId}/update`;

                // Tambah input hidden status = cancelled
                hiddenInputs.innerHTML = '<input type="hidden" name="status" value="cancelled">';
            }

            actionTextElement.innerText = actionDisplay;
            actionTextElement.className = 'font-bold ' + actionTextColor;
            finalActionButton.className = 'w-1/2 text-white font-bold py-3 rounded-xl shadow-lg transition ' + buttonClass;
            actionForm.action = url; 
            
            document.getElementById('confirmModal').classList.remove('hidden');
        }
    </script>
@endsection