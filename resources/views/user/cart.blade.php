<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <h2 class="font-semibold text-2xl text-gray-800 leading-tight mb-6">Keranjang Belanja</h2>

            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                @if($cartItems->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="text-sm font-medium text-gray-700 border-b border-gray-200">
                                    <th class="py-4 px-4">Produk</th>
                                    <th class="py-4 px-4">Harga</th>
                                    <th class="py-4 px-4">Jumlah</th>
                                    <th class="py-4 px-4">Subtotal</th>
                                    <th class="py-4 px-4">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($cartItems as $item)
                                <tr class="border-b border-gray-100 hover:bg-gray-50">
                                    <td class="py-4 px-4 flex items-center gap-4">
                                        <div class="w-16 h-16 rounded overflow-hidden flex-shrink-0">
                                            <img src="{{ asset('storage/' . $item->product->image) }}" class="w-full h-full object-cover">
                                        </div>
                                        <span class="font-medium text-gray-900">{{ $item->product->name }}</span>
                                    </td>
                                    <td class="py-4 px-4 text-gray-600">
                                        Rp {{ number_format($item->product->price, 0, ',', '.') }}
                                    </td>
                                    <td class="py-4 px-4">
                                        <form action="{{ route('cart.update', $item->id) }}" method="POST" class="flex items-center">
                                            @csrf
                                            @method('PATCH')
                                            <input type="number" name="quantity" value="{{ $item->quantity }}" min="20" max="200"
                                                class="w-16 text-center border-gray-300 rounded-md shadow-sm focus:border-[#ee2b5c] focus:ring focus:ring-[#ee2b5c] focus:ring-opacity-50 text-sm"
                                                onchange="this.form.submit()">
                                        </form>
                                    </td>
                                    <td class="py-4 px-4 font-bold text-[#ee2b5c]">
                                        Rp {{ number_format($item->product->price * $item->quantity, 0, ',', '.') }}
                                    </td>
                                    <td class="py-4 px-4">
                                        <form action="{{ route('cart.destroy', $item->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-500 hover:text-red-700 text-sm font-medium">
                                                Hapus
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-8 flex flex-col sm:flex-row justify-between items-center gap-4">
                        <a href="{{ route('dashboard') }}#all-cakes" class="text-gray-600 hover:text-[#ee2b5c] text-sm font-medium">
                            &larr; Lanjut Belanja
                        </a>
                        <div class="flex flex-col items-end gap-4">
                            <div class="text-xl font-bold text-gray-800">
                                Total: <span class="text-[#ee2b5c]">Rp {{ number_format($total, 0, ',', '.') }}</span>
                            </div>
                            <a href="{{ route('cart.checkout') }}" 
                               class="bg-[#ee2b5c] hover:bg-[#d61f4b] text-white font-bold py-3 px-8 rounded-full shadow-lg transition duration-300">
                                Checkout Sekarang
                            </a>
                        </div>
                    </div>
                @else
                    <div class="text-center py-12">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto text-gray-300 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        <p class="text-gray-500 text-lg mb-6">Keranjang belanjamu masih kosong.</p>
                        <a href="{{ route('dashboard') }}#all-cakes" class="bg-[#ee2b5c] text-white px-6 py-2 rounded-full font-bold hover:bg-[#d61f4b] transition">
                            Mulai Belanja
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>