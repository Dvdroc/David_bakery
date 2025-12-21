<x-app-layout>
    <div class="py-12 bg-[#fcf8f9]">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <h2 class="font-bold text-2xl text-gray-800 leading-tight mb-6">Checkout Pesanan</h2>

            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
                    {{ session('error') }}
                </div>
            @endif

            <form action="{{ route('cart.checkout.process') }}" method="POST" class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @csrf
                
                <div class="md:col-span-2 space-y-6">
                    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                        <h3 class="text-lg font-semibold mb-4 border-b pb-2">Informasi Pengambilan/Pengiriman</h3>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Pengambilan (H+7 s.d H+30)</label>
                            <input type="date" name="pickup_date" id="pickup_date" required 
                                min="{{ $minDate->format('Y-m-d') }}" 
                                max="{{ $maxDate->format('Y-m-d') }}"
                                class="w-full rounded-lg border-gray-300 focus:border-[#ee2b5c] focus:ring-[#ee2b5c]">
                            
                            <p id="slot-info" class="text-sm mt-2 hidden">
                                Kuota Tersedia: <span id="slot-count" class="font-bold">checking...</span> porsi
                            </p>
                            <p id="slot-error" class="text-sm text-red-600 mt-2 hidden font-bold">
                                Maaf, kuota untuk tanggal ini sudah penuh.
                            </p>
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Jam Pengambilan</label>
                            <input type="time" name="pickup_time" required 
                                class="w-full rounded-lg border-gray-300 focus:border-[#ee2b5c] focus:ring-[#ee2b5c]">
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Metode</label>
                            <select name="delivery_type" id="delivery_type" class="w-full rounded-lg border-gray-300 focus:border-[#ee2b5c] focus:ring-[#ee2b5c]" onchange="toggleAddress()">
                                <option value="pickup">Ambil Sendiri (Pickup)</option>
                                <option value="delivery">Diantar (Delivery)</option>
                            </select>
                        </div>

                        <div id="address_field" class="hidden mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Alamat Pengiriman</label>
                            <textarea name="delivery_address" rows="3" 
                                class="w-full rounded-lg border-gray-300 focus:border-[#ee2b5c] focus:ring-[#ee2b5c]" placeholder="Masukkan alamat lengkap..."></textarea>
                        </div>
                    </div>
                </div>

                <div class="md:col-span-1">
                    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 sticky top-24">
                        <h3 class="text-lg font-semibold mb-4 border-b pb-2">Ringkasan</h3>
                        
                        <div class="space-y-3 mb-4 max-h-60 overflow-y-auto">
                            @foreach($cartItems as $item)
                                <div class="flex flex-col text-sm border-b border-dashed pb-2">
                                    <div class="flex justify-between font-medium">
                                        <span>{{ $item->product->name }} (x{{ $item->quantity }})</span>
                                        <span>Rp {{ number_format($item->product->price * $item->quantity, 0, ',', '.') }}</span>
                                    </div>
                                    @if($item->packaging_type)
                                        <div class="flex justify-between text-xs text-gray-500 mt-1 pl-2">
                                            <span>+ {{ ceil($item->quantity / 20) }} Kerdus {{ ucfirst($item->packaging_type) }}</span>
                                            <span>Rp {{ number_format(ceil($item->quantity / 20) * $item->packaging_price, 0, ',', '.') }}</span>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>

                        <div class="border-t pt-4 mt-4">
                            <div class="flex justify-between items-center text-lg font-bold">
                                <span>Total</span>
                                <span class="text-[#ee2b5c]">Rp {{ number_format($total, 0, ',', '.') }}</span>
                            </div>
                        </div>

                        <button type="submit" id="submit-btn" class="w-full mt-6 bg-[#ee2b5c] hover:bg-[#d61f4b] text-white font-bold py-3 rounded-full shadow transition duration-300 disabled:opacity-50 disabled:cursor-not-allowed">
                            Konfirmasi Pesanan
                        </button>
                        
                        <a href="{{ route('cart.index') }}" class="block text-center mt-4 text-sm text-gray-500 hover:text-gray-700">
                            Kembali ke Keranjang
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Data Slot dari Controller (PHP ke JS)
        const slotData = @json($slotData ?? []);
        const defaultQuota = 200; // Kuota default jika tanggal belum ada di database

        const dateInput = document.getElementById('pickup_date');
        const slotInfo = document.getElementById('slot-info');
        const slotCountSpan = document.getElementById('slot-count');
        const slotError = document.getElementById('slot-error');
        const submitBtn = document.getElementById('submit-btn');

        // Event saat tanggal berubah
        dateInput.addEventListener('change', function() {
            const selectedDate = this.value;
            
            // Reset UI
            slotInfo.classList.add('hidden');
            slotError.classList.add('hidden');
            submitBtn.disabled = false;
            submitBtn.classList.remove('bg-gray-400', 'cursor-not-allowed');
            submitBtn.classList.add('bg-[#ee2b5c]', 'hover:bg-[#d61f4b]');

            if (!selectedDate) return;

            // Cek ketersediaan
            let available = defaultQuota;

            // Jika tanggal ada di data slot (artinya sudah pernah ada pesanan/diatur admin)
            if (slotData.hasOwnProperty(selectedDate)) {
                available = slotData[selectedDate];
            }

            // Tampilkan Info
            if (available > 0) {
                slotInfo.classList.remove('hidden');
                slotCountSpan.innerText = available;
                slotCountSpan.className = available < 50 ? "font-bold text-orange-500" : "font-bold text-green-600";
            } else {
                // Jika Kuota Habis (0)
                slotError.classList.remove('hidden');
                submitBtn.disabled = true;
                submitBtn.classList.remove('bg-[#ee2b5c]', 'hover:bg-[#d61f4b]');
                submitBtn.classList.add('bg-gray-400', 'cursor-not-allowed');
            }
        });

        function toggleAddress() {
            const type = document.getElementById('delivery_type').value;
            const addressField = document.getElementById('address_field');
            if (type === 'delivery') {
                addressField.classList.remove('hidden');
                addressField.querySelector('textarea').required = true;
            } else {
                addressField.classList.add('hidden');
                addressField.querySelector('textarea').required = false;
            }
        }
    </script>
</x-app-layout>