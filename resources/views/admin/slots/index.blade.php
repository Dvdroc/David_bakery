@extends('admin.layout')

@section('content')
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Daftar Slot Produksi</h2>
            <p class="text-sm text-gray-500">
                Menampilkan slot untuk tanggal: <span class="font-bold text-orange-600">{{ $targetDate->translatedFormat('l, d F Y') }}</span>
            </p>
        </div>
        
        <button onclick="openCalendar()" class="bg-orange-100 text-orange-600 px-5 py-2.5 rounded-xl text-sm font-bold hover:bg-orange-200 flex items-center gap-2 shadow-sm transition border border-orange-200">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
            Ganti Tanggal / Kalender
        </button>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-xl relative mb-6 shadow-sm">
            <div class="flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                {{ session('success') }}
            </div>
        </div>
    @endif

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden mb-10">
        <table class="w-full text-left">
            <thead class="bg-gray-50 border-b border-gray-100 text-gray-500 uppercase text-xs tracking-wider">
                <tr>
                    <th class="px-6 py-4 font-semibold">Menu Kue</th>
                    <th class="px-6 py-4 font-semibold text-center">Max Pesanan</th>
                    <th class="px-6 py-4 font-semibold text-center">Terjual</th>
                    <th class="px-6 py-4 font-semibold text-center">Sisa Slot</th>
                    <th class="px-6 py-4 font-semibold text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach($products as $product)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-6 py-4 font-medium text-gray-800 flex items-center gap-4">
                        <div class="w-12 h-12 bg-gray-200 rounded-lg overflow-hidden shadow-sm">
                            @if($product->image) <img src="{{ asset('storage/' . $product->image) }}" class="w-full h-full object-cover"> @endif
                        </div>
                        <span class="text-base">{{ $product->name }}</span>
                    </td>
                    <td class="px-6 py-4 text-center text-gray-500 text-base">{{ $product->daily_quota }}</td>
                    <td class="px-6 py-4 text-center text-gray-500 text-base">{{ $product->sold_today }}</td>
                    <td class="px-6 py-4 text-center font-bold text-base {{ $product->remaining_quota == 0 ? 'text-red-600' : 'text-green-600' }}">
                        {{ $product->remaining_quota }}
                    </td>
                    <td class="px-6 py-4 text-right">
                        @if($product->is_closed_manual)
                            <form action="{{ route('admin.slots.update') }}" method="POST">
                                @csrf
                                <input type="hidden" name="date" value="{{ $targetDate->format('Y-m-d') }}">
                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                <input type="hidden" name="action" value="open">
                                <button type="submit" class="bg-green-500 hover:bg-green-600 text-white px-5 py-2 rounded-lg text-sm font-bold shadow-sm transition transform hover:scale-105">Buka Slot</button>
                            </form>
                        @else
                            <button onclick="openConfirmModal('{{ $product->id }}', '{{ $product->name }}')" 
                                    class="bg-red-500 hover:bg-red-600 text-white px-5 py-2 rounded-lg text-sm font-bold shadow-sm transition transform hover:scale-105">
                                Tutup Slot
                            </button>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div id="calendarModal" class="fixed inset-0 bg-black bg-opacity-60 hidden flex items-center justify-center z-50 backdrop-blur-sm transition-opacity duration-300">
        <div class="bg-white rounded-3xl shadow-2xl p-8 w-full max-w-4xl relative transform transition-all scale-100">
            
            <div class="flex justify-between items-center mb-8">
                <div>
                    <h3 class="text-2xl font-bold text-gray-800">Pilih Tanggal Produksi</h3>
                    <p class="text-gray-500 text-sm">Klik tanggal untuk melihat atau mengatur slot.</p>
                </div>
                <button onclick="closeCalendar()" class="bg-gray-100 hover:bg-gray-200 p-2 rounded-full transition">
                    <svg class="w-6 h-6 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <div class="flex justify-between items-center mb-6 bg-gray-50 p-3 rounded-2xl border border-gray-100">
                <a href="{{ route('admin.slots.index', $prevMonthDate) }}" class="flex items-center px-4 py-2 text-gray-600 hover:bg-white hover:shadow-sm hover:text-orange-600 rounded-xl transition font-medium">
                    <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                    Bulan Lalu
                </a>
                <span class="text-xl font-bold text-gray-800 uppercase tracking-wide">{{ $monthName }}</span>
                <a href="{{ route('admin.slots.index', $nextMonthDate) }}" class="flex items-center px-4 py-2 text-gray-600 hover:bg-white hover:shadow-sm hover:text-orange-600 rounded-xl transition font-medium">
                    Bulan Depan
                    <svg class="w-5 h-5 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                </a>
            </div>

            <div class="grid grid-cols-7 gap-3 text-center">
                <div class="py-3 text-sm font-bold text-gray-400 uppercase tracking-wider">MINGGU</div>
                <div class="py-3 text-sm font-bold text-gray-400 uppercase tracking-wider">SENIN</div>
                <div class="py-3 text-sm font-bold text-gray-400 uppercase tracking-wider">SELASA</div>
                <div class="py-3 text-sm font-bold text-gray-400 uppercase tracking-wider">RABU</div>
                <div class="py-3 text-sm font-bold text-gray-400 uppercase tracking-wider">KAMIS</div>
                <div class="py-3 text-sm font-bold text-gray-400 uppercase tracking-wider">JUMAT</div>
                <div class="py-3 text-sm font-bold text-gray-400 uppercase tracking-wider">SABTU</div>

                @for ($i = 0; $i < $startDayIndex; $i++) <div></div> @endfor

                @for ($day = 1; $day <= $daysInMonth; $day++)
                    @php
                        $dateStr = $targetDate->copy()->setDay($day)->format('Y-m-d');
                        $isMarked = in_array($day, $markedDates);
                        $isToday = ($dateStr == $targetDate->format('Y-m-d'));
                    @endphp
                    
                    <a href="{{ route('admin.slots.index', $dateStr) }}" 
                       class="h-24 flex flex-col items-center justify-center rounded-xl text-lg font-medium transition relative border border-transparent hover:border-orange-300 hover:shadow-md
                       {{ $isToday ? 'bg-orange-600 text-white shadow-lg ring-2 ring-orange-300' : 'bg-gray-50 hover:bg-white text-gray-700' }}">
                        
                        <span class="text-2xl font-bold mb-1">{{ $day }}</span>
                        
                        @if($isMarked && !$isToday)
                            <span class="flex items-center gap-1 text-xs text-red-500 font-semibold bg-red-50 px-2 py-0.5 rounded-full border border-red-100">
                                <span class="w-1.5 h-1.5 bg-red-500 rounded-full animate-pulse"></span>
                                Pesanan
                            </span>
                        @elseif($isToday)
                            <span class="text-xs text-orange-100 font-medium">Hari Ini</span>
                        @else
                            <span class="text-xs text-gray-400 font-medium">Kosong</span>
                        @endif
                    </a>
                @endfor
            </div>
        </div>
    </div>

    <div id="confirmModal" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center z-50 backdrop-blur-sm">
        <div class="bg-white rounded-2xl shadow-2xl w-96 p-8 text-center transform transition-all scale-100">
            <div class="mx-auto mb-6 flex items-center justify-center bg-red-50 w-20 h-20 rounded-full">
                <svg class="w-10 h-10 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
            </div>
            <h3 class="text-xl font-bold text-gray-800 mb-2">Konfirmasi Penutupan</h3>
            <p class="text-gray-500 font-medium text-base mb-8">
                Apakah Anda yakin ingin menutup slot produksi untuk <span id="modalCakeName" class="font-bold text-gray-800">...</span> pada tanggal <span class="font-bold text-orange-600">{{ $targetDate->format('d F') }}</span>?
            </p>
            <div class="flex justify-center gap-4">
                <button onclick="closeConfirmModal()" class="w-1/2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold py-3 rounded-xl transition">Batal</button>
                <form action="{{ route('admin.slots.update') }}" method="POST" class="w-1/2">
                    @csrf
                    <input type="hidden" name="date" value="{{ $targetDate->format('Y-m-d') }}">
                    <input type="hidden" name="product_id" id="modalProductId">
                    <input type="hidden" name="action" value="close">
                    <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white font-bold py-3 rounded-xl shadow-lg hover:shadow-xl transition">Ya, Tutup</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Script untuk Popup Kalender
        function openCalendar() {
            const modal = document.getElementById('calendarModal');
            modal.classList.remove('hidden');
            // Animasi masuk
            setTimeout(() => {
                modal.firstElementChild.classList.remove('scale-95', 'opacity-0');
                modal.firstElementChild.classList.add('scale-100', 'opacity-100');
            }, 10);
        }
        function closeCalendar() {
            const modal = document.getElementById('calendarModal');
            modal.firstElementChild.classList.remove('scale-100', 'opacity-100');
            modal.firstElementChild.classList.add('scale-95', 'opacity-0');
            setTimeout(() => {
                modal.classList.add('hidden');
            }, 300);
        }

        // Script untuk Popup Konfirmasi Tutup
        function openConfirmModal(id, name) {
            document.getElementById('modalProductId').value = id;
            document.getElementById('modalCakeName').innerText = name;
            document.getElementById('confirmModal').classList.remove('hidden');
        }
        function closeConfirmModal() {
            document.getElementById('confirmModal').classList.add('hidden');
        }
    </script>
@endsection