<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;

class RecapController extends Controller
{
    public function index()
    {
        // Ambil pesanan yang sudah selesai (completed)
        $completedOrders = Order::with(['user', 'orderItems'])
            ->where('status', 'completed') 
            ->latest()
            ->get();

        // Panggil file view
        return view('admin.recap.index', compact('completedOrders'));
    }
}