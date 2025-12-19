@extends('admin.layout')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="flex items-center mb-6">
        <a href="{{ route('admin.products.index') }}" class="mr-4 bg-gray-200 p-2 rounded-full text-gray-600 hover:bg-gray-300 transition">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
        </a>
        <h2 class="text-2xl font-bold text-gray-800">Tambah Menu Baru</h2>
    </div>

    <div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-100">
        <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Nama Kue</label>
                    <input type="text" name="name" class="w-full border border-gray-300 rounded-xl p-3 focus:ring-2 focus:ring-orange-200 focus:border-orange-400 outline-none transition" placeholder="Contoh: Bikang Mawar" required>
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Harga (Rp)</label>
                    <input type="number" name="price" class="w-full border border-gray-300 rounded-xl p-3 focus:ring-2 focus:ring-orange-200 focus:border-orange-400 outline-none transition" placeholder="2500" required>
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Kuota Harian (Pcs)</label>
                    <input type="number" name="daily_quota" value="200" class="w-full border border-gray-300 rounded-xl p-3 focus:ring-2 focus:ring-orange-200 focus:border-orange-400 outline-none transition">
                    <p class="text-xs text-gray-400 mt-1">Batas maksimal produksi per hari.</p>
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Estimasi Waktu (Menit)</label>
                    <input type="number" name="production_time_estimate" value="60" class="w-full border border-gray-300 rounded-xl p-3 focus:ring-2 focus:ring-orange-200 focus:border-orange-400 outline-none transition">
                </div>
            </div>

            <div class="mb-6">
                <label class="block text-sm font-bold text-gray-700 mb-2">Deskripsi Produk</label>
                <textarea name="description" rows="3" class="w-full border border-gray-300 rounded-xl p-3 focus:ring-2 focus:ring-orange-200 focus:border-orange-400 outline-none transition" placeholder="Jelaskan rasa dan tekstur kue..."></textarea>
            </div>

            <div class="mb-8">
                <label class="block text-sm font-bold text-gray-700 mb-2">Foto Produk</label>
                <div class="border-2 border-dashed border-gray-300 rounded-xl p-6 text-center hover:bg-gray-50 transition cursor-pointer relative">
                    <input type="file" name="image" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" onchange="previewImage(event)">
                    <div id="preview-container">
                        <svg class="w-12 h-12 text-gray-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        <p class="text-sm text-gray-500">Klik atau seret foto ke sini</p>
                        <p class="text-xs text-gray-400 mt-1">JPG, PNG, GIF (Max 2MB)</p>
                    </div>
                    <img id="preview-img" class="hidden max-h-48 mx-auto rounded-lg shadow-md mt-2">
                </div>
            </div>

            <button type="submit" class="w-full bg-orange-600 hover:bg-orange-700 text-white font-bold py-4 rounded-xl shadow-lg transition transform hover:scale-[1.02]">
                Simpan Menu Baru
            </button>
        </form>
    </div>
</div>

<script>
    function previewImage(event) {
        const reader = new FileReader();
        reader.onload = function(){
            const output = document.getElementById('preview-img');
            const container = document.getElementById('preview-container');
            output.src = reader.result;
            output.classList.remove('hidden');
            container.classList.add('hidden');
        };
        reader.readAsDataURL(event.target.files[0]);
    }
</script>
@endsection