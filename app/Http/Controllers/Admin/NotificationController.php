<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order; // Panggil Model Order
use Carbon\Carbon;

class NotificationController extends Controller
{
    public function index()
    {
        // 1. Ambil data pesanan dari Database yang statusnya 'pending' (Menunggu Konfirmasi)
        // Kita kelompokkan berdasarkan jenis kue (misal diambil dari relasi orderItems -> product -> name)
        // TAPI untuk tahap awal agar mudah, kita ambil raw data dulu.
        
        $pendingOrders = Order::with(['user', 'orderItems.product']) // Eager load relasi biar cepat
                              ->where('status', 'pending')
                              ->orderBy('created_at', 'desc')
                              ->get();

        // 2. Kelompokkan data agar sesuai desain (Bikang, Kukang/Lainnya)
        // Di sini kita kelompokkan berdasarkan Nama Produk Pertama di keranjang
        $groupedOrders = $pendingOrders->groupBy(function($order) {
            return $order->orderItems->first()->product->name ?? 'Lain-lain';
        });

        return view('admin.notifications', compact('groupedOrders'));
    }
}