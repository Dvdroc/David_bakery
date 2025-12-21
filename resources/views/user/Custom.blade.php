<x-app-layout>
<div class="relative flex size-full min-h-screen flex-col bg-[#fcf8f9] group/design-root overflow-x-hidden" style='font-family: Epilogue, "Noto Sans", sans-serif;'>
    <div class="layout-container flex h-full grow flex-col">
        <main class="flex-grow flex items-center justify-center pt-36 px-4 pb-16">
            <section class="w-full max-w-2xl bg-white rounded-2xl shadow-xl p-10">
              <div class="text-center mb-10">
                  <h2 class="text-4xl font-bold text-pink-600">Pesan Custom Cake</h2>
                  <p class="text-gray-500 mt-2">Pesanan dalam jumlah besar (Min. 20 Pcs)</p>
              </div>

              @if ($errors->any())
                  <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
                      <ul>
                          @foreach ($errors->all() as $error)
                              <li>{{ $error }}</li>
                          @endforeach
                      </ul>
                  </div>
              @endif

              <form action="{{ route('cart.custom.add') }}" method="POST" class="space-y-6">
                @csrf

                <div class="bg-gray-50 p-4 rounded-xl border border-gray-200 space-y-4">
                    <h3 class="font-bold text-gray-700 border-b pb-2">Informasi Pemesan</h3>
                    
                    <div>
                        <label class="block text-sm font-semibold text-gray-600 mb-1">Nama</label>
                        <input type="text" value="{{ Auth::user()->name }}" class="w-full border bg-gray-100 text-gray-500 rounded-lg p-3 cursor-not-allowed" readonly />
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-600 mb-1">Nomor HP</label>
                            <input type="text" value="{{ Auth::user()->phone ?? Auth::user()->no_hp ?? '-' }}" class="w-full border bg-gray-100 text-gray-500 rounded-lg p-3 cursor-not-allowed" readonly />
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-600 mb-1">Email</label>
                            <input type="text" value="{{ Auth::user()->email }}" class="w-full border bg-gray-100 text-gray-500 rounded-lg p-3 cursor-not-allowed" readonly />
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-600 mb-1">Alamat</label>
                        <textarea class="w-full border bg-gray-100 text-gray-500 rounded-lg p-3 cursor-not-allowed" rows="2" readonly>{{ Auth::user()->address ?? Auth::user()->alamat ?? '-' }}</textarea>
                    </div>
                </div>

                <div class="space-y-6">
                    <div>
                        <label for="product_id" class="block font-bold text-gray-700 mb-2">Pilih Jenis Kue</label>
                        <select name="product_id" id="product_id" class="w-full border rounded-lg p-4 text-lg focus:ring-2 focus:ring-pink-400" required>
                            <option value="">-- Pilih Kue --</option>
                            @foreach($products as $product)
                                <option value="{{ $product->id }}">
                                    {{ $product->name }} - Rp {{ number_format($product->price, 0, ',', '.') }} /pcs
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="quantity" class="block font-bold text-gray-700 mb-2">Jumlah Pesanan (Min. 20)</label>
                        <input type="number" name="quantity" id="quantity" min="20" value="20" class="w-full border rounded-lg p-4 text-lg focus:ring-2 focus:ring-pink-400" required />
                        <p class="text-sm text-gray-500 mt-1">*Minimal pemesanan untuk custom adalah 20 pcs.</p>
                    </div>
                    <div id="box-info" class="text-pink-600 text-sm font-semibold mt-2 hidden">
                      Estimasi: Akan menggunakan <span id="box-count">0</span> Kerdus.
                    </div>

                    <div>
                        <label for="packaging" class="block font-bold text-gray-700 mb-2">Jenis Kemasan (Kerdus)</label>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <label class="relative flex items-center justify-between p-4 border rounded-xl cursor-pointer hover:bg-pink-50 has-[:checked]:ring-2 has-[:checked]:ring-pink-500 has-[:checked]:bg-pink-50">
                                <div class="flex items-center gap-3">
                                    <input type="radio" name="packaging" value="biasa" class="w-5 h-5 text-pink-600 border-gray-300 focus:ring-pink-500" required checked>
                                    <div>
                                        <div class="font-semibold text-gray-800">Kerdus Biasa</div>
                                        <div class="text-sm text-gray-500">+ Rp 10.000 /pcs</div>
                                    </div>
                                </div>
                            </label>

                            <label class="relative flex items-center justify-between p-4 border rounded-xl cursor-pointer hover:bg-pink-50 has-[:checked]:ring-2 has-[:checked]:ring-pink-500 has-[:checked]:bg-pink-50">
                                <div class="flex items-center gap-3">
                                    <input type="radio" name="packaging" value="mika" class="w-5 h-5 text-pink-600 border-gray-300 focus:ring-pink-500">
                                    <div>
                                        <div class="font-semibold text-gray-800">Kerdus Mika</div>
                                        <div class="text-sm text-gray-500">+ Rp 15.000 /pcs</div>
                                    </div>
                                </div>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="text-center pt-4">
                  <button type="submit" class="w-full bg-[#ee2b5c] hover:bg-red-700 text-white font-bold py-4 px-8 text-lg rounded-xl shadow-md transition duration-300 flex items-center justify-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    Masukkan Keranjang
                  </button>
                </div>

              </form>
            </section>
          </main>
    </div>
</div>
<script>
    const quantityInput = document.getElementById('quantity');
    const boxInfo = document.getElementById('box-info');
    const boxCountSpan = document.getElementById('box-count');

    quantityInput.addEventListener('input', function() {
        const qty = parseInt(this.value) || 0;
        if (qty >= 20) {
            const boxes = Math.ceil(qty / 20);
            boxCountSpan.innerText = boxes;
            boxInfo.classList.remove('hidden');
        } else {
            boxInfo.classList.add('hidden');
        }
    });
</script>
</x-app-layout>