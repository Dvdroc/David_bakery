<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;

class HomeController extends Controller
{
    public function index()
    {
        // Ambil semua produk yang aktif
        $products = Product::where('is_active', true)->get();
        
        // Ambil produk terlaris berdasarkan jumlah quantity terjual
        $bestSellers = Product::where('is_active', true)
            ->withSum('orderItems', 'quantity') // Menghitung total quantity dari relasi orderItems
            ->orderByDesc('order_items_sum_quantity') // Urutkan dari yang terbanyak
            ->take(4)
            ->get();

        return view('user.home', compact('products', 'bestSellers'));
    }

    public function show($id)
    {
        $product = Product::findOrFail($id);
        return view('user.order', compact('product'));
    }
}