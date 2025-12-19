<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;

class HomeController extends Controller
{
    public function index()
    {
        // Ambil semua produk   yang aktif
        $products = Product::where('is_active', true)->get();
        
        // Ambil produk terlaris (misal: ambil 4 produk acak dulu sebagai contoh)
        $bestSellers = Product::where('is_active', true)->inRandomOrder()->take(4)->get();

        return view('user.home', compact('products', 'bestSellers'));
    }

    public function show($id)
    {
        $product = Product::findOrFail($id);
        return view('user.order', compact('product'));
    }
}