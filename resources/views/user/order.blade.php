<x-app-layout>
    <div class="relative flex size-full min-h-screen flex-col bg-[#fcf8f9] group/design-root overflow-x-hidden pt-24" style='font-family: Epilogue, "Noto Sans", sans-serif;'>
        <div class="layout-container flex h-full grow flex-col">
            <div class="max-w-4xl mx-auto rounded-xl shadow-lg p-4 mt-10 border bg-white">
                
                {{-- 1. BAGIAN NOTIFIKASI SUKSES --}}
                @if(session('success'))
                    <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                        <span class="block sm:inline">{{ session('success') }}</span>
                    </div>
                @endif

                <div class="flex flex-col md:flex-row">
                    <div class="md:w-3/4 mb-4 md:mb-0">
                        <img src="{{ asset('storage/'.$product->image) }}" alt="{{ $product->name }}" class="rounded-lg w-full h-auto object-cover">
                    </div>
            
                    <div class="md:w-1/2 md:pl-4">
                        <h2 class="text-xl font-bold text-gray-800 mb-1">{{ $product->name }}</h2>
                        <p class="text-lg text-gray-600 mb-4">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
            
                        <div class="space-y-2 mb-4">
                            <button onclick="openCalendar()" class="w-full bg-[#ee2b5c] hover:bg-red-700 text-white py-2 rounded-lg font-bold text-center block transition duration-300">
                                Beli Langsung
                            </button>

                            {{-- 2. BAGIAN TOMBOL KERANJANG (DIBUNGKUS FORM) --}}
                            <form action="{{ route('cart.add', $product->id) }}" method="POST" class="w-full">
                                @csrf
                                <input type="hidden" name="quantity" value="1">
                                
                                <button type="submit" class="w-full border border-[#ee2b5c] text-[#ee2b5c] hover:bg-[#ee2b5c] hover:text-white py-2 rounded flex items-center justify-center gap-2 transition duration-300 font-bold">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                                    </svg>
                                    + Keranjang
                                </button>
                            </form>
                        </div>

                        <div class="mt-4">
                            <div class="flex items-center text-sm text-gray-700 font-semibold mb-2">
                                <span class="text-2xl font-bold text-black">{{ number_format($avgRating, 1) }} ⭐</span>
                                <span class="ml-2 text-gray-500">({{ $totalReviews }} Ulasan)</span>
                            </div>
                            <div class="space-y-1">
                                <div class="flex items-center">
                                    <span class="w-4 text-sm">5</span>
                                    <div class="w-full h-2 bg-gray-200 rounded mx-2">
                                        <div class="h-full bg-yellow-400 rounded" style="width: {{ $percentage5 }}%"></div>
                                    </div>
                                    <span class="text-xs text-gray-500">{{ round($percentage5) }}%</span>
                                </div>
                                </div>
                        </div>

                        <div class="mt-6 bg-white rounded-lg p-4 shadow-inner border-t">
                            <h3 class="text-md font-semibold text-gray-800 mb-4">Ulasan Pelanggan</h3>
                            
                            <div class="space-y-4 max-h-60 overflow-y-auto pr-2">
                                @forelse($product->reviews as $review)
                                    <div class="border-b border-gray-100 pb-3">
                                        <div class="flex justify-between items-start">
                                            <div>
                                                <p class="text-sm font-bold text-gray-800">{{ $review->user->name }}</p>
                                                <div class="flex text-yellow-400 text-xs">
                                                    @for($i=1; $i<=5; $i++)
                                                        <span>{{ $i <= $review->rating ? '★' : '☆' }}</span>
                                                    @endfor
                                                </div>
                                            </div>
                                            <span class="text-[10px] text-gray-400">{{ $review->updated_at->diffForHumans() }}</span>
                                        </div>
                                        <p class="text-sm text-gray-600 mt-1 italic">"{{ $review->review }}"</p>
                                    </div>
                                @empty
                                    <p class="text-sm text-gray-500 text-center py-4">Belum ada ulasan untuk produk ini.</p>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- MODAL CALENDAR (Tidak berubah) --}}
    <div id="calendarModal" class="fixed inset-0 bg-black bg-opacity-60 hidden flex items-center justify-center z-50 backdrop-blur-sm transition-opacity duration-300">
        <div class="bg-white rounded-3xl shadow-2xl p-6 w-full max-w-3xl relative transform transition-all scale-95 opacity-0">
            <div class="flex justify-between items-center mb-4">
                <button onclick="prevMonth()" class="px-3 py-1 bg-gray-100 rounded hover:bg-gray-200">Prev</button>
                <h3 id="calendarMonth" class="text-xl font-bold text-gray-800">Bulan Tahun</h3>
                <div class="flex gap-2">
                    <button onclick="nextMonth()" class="px-3 py-1 bg-gray-100 rounded hover:bg-gray-200">Next</button>
                    <button onclick="closeCalendar()" class="bg-gray-100 hover:bg-gray-200 p-2 rounded-full transition">
                        <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </div>

            <div class="grid grid-cols-7 gap-2 text-center mb-4">
                <div class="py-1 font-bold text-gray-400 text-xs">MINGGU</div>
                <div class="py-1 font-bold text-gray-400 text-xs">SENIN</div>
                <div class="py-1 font-bold text-gray-400 text-xs">SELASA</div>
                <div class="py-1 font-bold text-gray-400 text-xs">RABU</div>
                <div class="py-1 font-bold text-gray-400 text-xs">KAMIS</div>
                <div class="py-1 font-bold text-gray-400 text-xs">JUMAT</div>
                <div class="py-1 font-bold text-gray-400 text-xs">SABTU</div>
            </div>

            <div id="calendarDays" class="grid grid-cols-7 gap-2"></div>
        </div>
    </div>

    <script>  
    let currentDate = new Date();
    const calendarModal = document.getElementById('calendarModal');
    const calendarDays = document.getElementById('calendarDays');
    const calendarMonthLabel = document.getElementById('calendarMonth');
    const slots = @json($slots);
    const minDate = new Date("{{ $minDate->format('Y-m-d') }}");
    const maxDate = new Date("{{ $maxDate->format('Y-m-d') }}");

    function openCalendar() {
        calendarModal.classList.remove('hidden');
        setTimeout(() => {
            calendarModal.firstElementChild.classList.remove('scale-95', 'opacity-0');
            calendarModal.firstElementChild.classList.add('scale-100', 'opacity-100');
        }, 10);
        renderCalendar(currentDate);
    }

    function closeCalendar() {
        calendarModal.firstElementChild.classList.remove('scale-100', 'opacity-100');
        calendarModal.firstElementChild.classList.add('scale-95', 'opacity-0');
        setTimeout(() => { calendarModal.classList.add('hidden'); }, 300);
    }

    function prevMonth() {
        currentDate.setMonth(currentDate.getMonth() - 1);
        renderCalendar(currentDate);
    }

    function nextMonth() {
        currentDate.setMonth(currentDate.getMonth() + 1);
        renderCalendar(currentDate);
    }

    function renderCalendar(date) {
        const year = date.getFullYear();
        const month = date.getMonth();
        const daysInMonth = new Date(year, month + 1, 0).getDate();
        calendarMonthLabel.textContent = date.toLocaleString('default', { month: 'long', year: 'numeric' });

        let html = '';
        const firstDay = new Date(year, month, 1).getDay();
        for(let i=0; i<firstDay; i++) html += '<div></div>';

        for(let day=1; day<=daysInMonth; day++){
            const dateObj = new Date(year, month, day);
            const dateStr = `${year}-${String(month+1).padStart(2,'0')}-${String(day).padStart(2,'0')}`; 

            const slotInfo = slots[dateStr] ?? { remaining: 0, is_closed: false };
            const isOutOfRange = dateObj < minDate || dateObj > maxDate;
            const isClosedOrFull = slotInfo.is_closed || slotInfo.remaining <= 0;

            html += `
                <div onclick="${(isOutOfRange || isClosedOrFull) ? 'return;' : `selectDate('${dateStr}')`}" 
                    class="cursor-pointer flex flex-col items-center justify-center rounded-lg p-2 transition
                    ${isOutOfRange || isClosedOrFull ? 'bg-gray-300 text-gray-400' : 'bg-gray-50 hover:bg-white text-gray-700'}">
                    <span class="text-lg font-bold">${day}</span>
                    <span class="text-xs">
                        ${isOutOfRange ? 'Minimal 7 hari' : isClosedOrFull ? 'Penuh/ditutup' : slotInfo.remaining + ' slot'}
                    </span>
                </div>
            `;
        }

        calendarDays.innerHTML = html;
    }

    function selectDate(dateStr){
        const slotInfo = slots[dateStr] ?? { remaining: 0, is_closed: false };
        if(slotInfo.is_closed || slotInfo.remaining <= 0){
            alert('Tanggal ini penuh atau ditutup.');
            return;
        }
        window.location.href = "{{ url('/pesanan') }}/" + {{ $product->id }} + "?pickup_date=" + dateStr;
    }
    </script>
</x-app-layout>