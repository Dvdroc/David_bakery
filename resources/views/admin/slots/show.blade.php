@extends('admin.layout')

@section('content')
    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center">
            <a href="{{ route('admin.slots.index') }}" class="mr-3 text-gray-500 hover:text-orange-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            </a>
            <div>
                <h2 class="text-2xl font-bold text-gray-800">Pesanan {{ $targetDate->translatedFormat('l, d F Y') }}</h2>
            </div>
        </div>
        
        <a href="{{ route('admin.slots.index') }}" class="bg-orange-100 text-orange-600 px-4 py-2 rounded-lg text-sm font-bold hover:bg-orange-200">
            📅 Lihat Kalender
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <table class="w-full text-left">
            <thead class="bg-gray-50 text-gray-500 text-xs uppercase border-b">
                <tr>
                    <th class="px-6 py-4">Menu Kue</th>
                    <th class="px-6 py-4 text-center">Max Pesanan</th>
                    <th class="px-6 py-4 text-center">Terjual</th>
                    <th class="px-6 py-4 text-center">Sisa Slot</th>
                    <th class="px-6 py-4 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach($products as $product)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 font-medium text-gray-800 flex items-center gap-3">
                        <div class="w-10 h-10 bg-gray-200 rounded overflow-hidden">
                            @if($product->image) <img src="{{ asset('storage/' . $product->image) }}" class="w-full h-full object-cover"> @endif
                        </div>
                        {{ $product->name }}
                    </td>
                    <td class="px-6 py-4 text-center text-gray-500">{{ $product->daily_quota }}</td>
                    <td class="px-6 py-4 text-center text-gray-500">{{ $product->sold_today }}</td>
                    <td class="px-6 py-4 text-center font-bold {{ $product->remaining_quota == 0 ? 'text-red-600' : 'text-green-600' }}">
                        {{ $product->remaining_quota }}
                    </td>
                    <td class="px-6 py-4 text-right">
                        @if($product->is_closed_manual)
                            <form action="{{ route('admin.slots.update') }}" method="POST">
                                @csrf
                                <input type="hidden" name="date" value="{{ $targetDate->format('Y-m-d') }}">
                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                <input type="hidden" name="action" value="open">
                                <button type="submit" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg text-sm font-bold shadow-sm">
                                    Buka Slot
                                </button>
                            </form>
                        @else
                            <button onclick="openModal('{{ $product->id }}', '{{ $product->name }}')" 
                                    class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg text-sm font-bold shadow-sm">
                                Tutup Slot
                            </button>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div id="slotModal" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center z-50">
        <div class="bg-white rounded-xl shadow-lg w-96 p-6 text-center">
            
            <div class="mx-auto mb-4 flex items-center justify-center">
                <svg class="w-16 h-16 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
            </div>

            <p class="text-gray-800 font-medium text-lg mb-6">
                apakah anda ingin menutup slot pesanan <span id="modalCakeName" class="font-bold text-red-600">...</span> pada tanggal <span class="font-bold">{{ $targetDate->format('d') }}</span>?
            </p>

            <div class="flex justify-center gap-4">
                <button onclick="closeModal()" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-6 rounded-lg">
                    Tidak
                </button>
                
                <form action="{{ route('admin.slots.update') }}" method="POST">
                    @csrf
                    <input type="hidden" name="date" value="{{ $targetDate->format('Y-m-d') }}">
                    <input type="hidden" name="product_id" id="modalProductId">
                    <input type="hidden" name="action" value="close">
                    <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-6 rounded-lg">
                        Iya
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script>
        function openModal(id, name) {
            document.getElementById('modalProductId').value = id;
            document.getElementById('modalCakeName').innerText = name;
            document.getElementById('slotModal').classList.remove('hidden');
        }

        function closeModal() {
            document.getElementById('slotModal').classList.add('hidden');
        }
    </script>
@endsection