<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    // 1. TAMPILKAN DAFTAR KUE
    public function index()
    {
        // Ambil data terbaru paling atas
        $products = Product::latest()->get();
        return view('admin.products.index', compact('products'));
    }

    // 2. HALAMAN TAMBAH KUE
    public function create()
    {
        return view('admin.products.create');
    }

    // 3. PROSES SIMPAN KUE (CREATE)
    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'description' => 'nullable|string',
            'daily_quota' => 'required|integer|min:1', // Kuota harian default
            'production_time_estimate' => 'nullable|integer', // Estimasi waktu (menit)
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Wajib gambar, maks 2MB
        ]);

        $data = $request->all();

        // Upload Gambar
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('products', 'public');
            $data['image'] = $path;
        }

        Product::create($data);

        return redirect()->route('admin.products.index')->with('success', 'Menu kue berhasil ditambahkan!');
    }

    // 4. HALAMAN EDIT KUE
    public function edit(Product $product)
    {
        return view('admin.products.edit', compact('product'));
    }

    // 5. PROSES UPDATE KUE
    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'description' => 'nullable|string',
            'daily_quota' => 'required|integer',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->all();

        // Cek jika ada upload gambar baru
        if ($request->hasFile('image')) {
            // Hapus gambar lama jika ada
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            // Simpan gambar baru
            $path = $request->file('image')->store('products', 'public');
            $data['image'] = $path;
        }

        $product->update($data);

        return redirect()->route('admin.products.index')->with('success', 'Menu kue berhasil diperbarui!');
    }

    // 6. HAPUS KUE
    public function destroy(Product $product)
    {
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }
        
        $product->delete();
        return redirect()->route('admin.products.index')->with('success', 'Menu kue berhasil dihapus.');
    }
    
}