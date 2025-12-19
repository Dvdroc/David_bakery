@extends('admin.layout')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="flex items-center mb-6">
        <a href="{{ route('admin.products.index') }}" class="mr-4 bg-gray-200 p-2 rounded-full text-gray-600 hover:bg-gray-300 transition">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
        </a>
        <h2 class="text-2xl font-bold text-gray-800">Edit Menu: {{ $product->name }}</h2>
    </div>

    <div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-100">
        <form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Nama Kue</label>
                    <input type="text" name="name" value="{{ $product->name }}" class="w-full border border-gray-300 rounded-xl p-3 focus:ring-2 focus:ring-orange-200 outline-none" required>
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Harga (Rp)</label>
                    <input type="number" name="price" value="{{ $product->price }}" class="w-full border border-gray-300 rounded-xl p-3 focus:ring-2 focus:ring-orange-200 outline-none" required>
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Kuota Harian</label>
                    <input type="number" name="daily_quota" value="{{ $product->daily_quota }}" class="w-full border border-gray-300 rounded-xl p-3 focus:ring-2 focus:ring-orange-200 outline-none">
                </div>
            </div>

            <div class="mb-6">
                <label class="block text-sm font-bold text-gray-700 mb-2">Deskripsi</label>
                <textarea name="description" rows="3" class="w-full border border-gray-300 rounded-xl p-3 focus:ring-2 focus:ring-orange-200 outline-none">{{ $product->description }}</textarea>
            </div>

            <div class="mb-8">
                <label class="block text-sm font-bold text-gray-700 mb-2">Foto Produk (Biarkan kosong jika tidak diganti)</label>
                <div class="flex items-center gap-4">
                    @if($product->image)
                        <img src="{{ asset('storage/' . $product->image) }}" class="w-24 h-24 rounded-lg object-cover shadow-sm border">
                    @endif
                    <input type="file" name="image" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-orange-50 file:text-orange-700 hover:file:bg-orange-100">
                </div>
            </div>

            <button type="submit" class="w-full bg-[#8B5E3C] hover:bg-[#6d4c32] text-white font-bold py-4 rounded-xl shadow-lg transition">
                Simpan Perubahan
            </button>
        </form>
    </div>
</div>
@endsection