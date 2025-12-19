@extends('admin.layout')

@section('content')
    <div class="flex justify-between items-end mb-8">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Kelola Menu</h2>
            <p class="text-sm text-gray-500">Atur daftar kue yang tersedia di toko.</p>
        </div>
        <a href="{{ route('admin.products.create') }}" class="bg-orange-600 hover:bg-orange-700 text-white px-5 py-2.5 rounded-xl shadow-sm font-bold text-sm flex items-center gap-2 transition transform hover:scale-105">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Tambah Menu
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-xl relative mb-6 flex items-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            {{ session('success') }}
        </div>
    @endif

    <div class="space-y-4">
        @forelse($products as $product)
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 flex flex-col md:flex-row gap-6 items-start hover:shadow-md transition">
            
            <div class="w-full md:w-48 h-32 flex-shrink-0 bg-gray-100 rounded-xl overflow-hidden relative">
                @if($product->image)
                    <img src="{{ asset('storage/' . $product->image) }}" class="w-full h-full object-cover">
                @else
                    <div class="w-full h-full flex items-center justify-center text-gray-400 text-xs">No Image</div>
                @endif
            </div>

            <div class="flex-1 py-1">
                <div class="flex justify-between items-start">
                    <div>
                        <h3 class="text-lg font-bold text-gray-900 mb-1">{{ $product->name }}</h3>
                        <p class="text-sm text-gray-500 mb-3 line-clamp-2">{{ $product->description ?? 'Tidak ada deskripsi.' }}</p>
                    </div>
                    <div class="text-right">
                        <span class="block text-lg font-bold text-orange-600">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                        <span class="text-xs text-gray-400">per pcs</span>
                    </div>
                </div>
                
                <div class="flex items-center gap-3 mt-2">
                    <span class="px-3 py-1 rounded-lg text-xs font-semibold bg-blue-50 text-blue-700 border border-blue-100">
                        Kuota: {{ $product->daily_quota }}
                    </span>
                    <span class="px-3 py-1 rounded-lg text-xs font-semibold bg-gray-100 text-gray-600 border border-gray-200">
                        Estimasi: {{ $product->production_time_estimate ?? 0 }} menit
                    </span>
                </div>
            </div>

            <div class="flex flex-col gap-2 w-full md:w-auto self-center">
                <a href="{{ route('admin.products.edit', $product->id) }}" 
                   class="px-4 py-2 bg-yellow-50 text-yellow-700 hover:bg-yellow-100 rounded-lg text-sm font-bold transition text-center border border-yellow-200">
                    Edit
                </a>
                <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" onsubmit="return confirm('Yakin hapus kue ini?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="w-full px-4 py-2 bg-red-50 text-red-600 hover:bg-red-100 rounded-lg text-sm font-bold transition border border-red-200">
                        Hapus
                    </button>
                </form>
            </div>
        </div>
        @empty
        <div class="text-center py-16 bg-white rounded-2xl border border-dashed border-gray-300">
            <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
            </div>
            <h3 class="text-lg font-medium text-gray-900">Belum ada menu</h3>
            <p class="text-gray-500 text-sm mt-1">Silakan tambahkan menu kue pertama Anda.</p>
        </div>
        @endforelse
    </div>
@endsection