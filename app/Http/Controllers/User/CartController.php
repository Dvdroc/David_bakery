<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\ProductionSlot;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    // Menampilkan isi keranjang
    public function index()
    {
        $cartItems = CartItem::with('product')->where('user_id', Auth::id())->get();
        
        // Hitung total harga
        $total = $cartItems->sum(function($item) {
            return $item->product->price * $item->quantity;
        });

        return view('user.cart', compact('cartItems', 'total'));
    }

    // Menambah item ke keranjang
    public function addToCart(Request $request, $productId)
    {
        $product = Product::findOrFail($productId);
        
        // Cek apakah produk sudah ada di keranjang user
        $cartItem = CartItem::where('user_id', Auth::id())
                            ->where('product_id', $productId)
                            ->first();

        if ($cartItem) {
            // Jika ada, tambahkan quantity
            $cartItem->quantity += $request->input('quantity', 1);
            $cartItem->save();
        } else {
            // Jika belum, buat baru
            CartItem::create([
                'user_id' => Auth::id(),
                'product_id' => $productId,
                'quantity' => $request->input('quantity', 1)
            ]);
        }

        return redirect()->route('cart.index')->with('success', 'Produk berhasil masuk keranjang!');
    }

    public function checkout()
    {
        $cartItems = CartItem::with('product')->where('user_id', Auth::id())->get();
        
        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Keranjang Anda kosong.');
        }

        $total = $cartItems->sum(function($item) {
            return $item->product->price * $item->quantity;
        });

        // Hitung tanggal minimal (H+7) dan maksimal (H+30)
        $minDate = Carbon::today()->addDays(7);
        $maxDate = Carbon::today()->addDays(30);

        return view('user.checkout', compact('cartItems', 'total', 'minDate', 'maxDate'));
    }

    // Proses Checkout (Simpan Pesanan)
    public function processCheckout(Request $request)
    {
        $request->validate([
            'pickup_date' => 'required|date|after_or_equal:today',
            'pickup_time' => 'required',
            'delivery_type' => 'required|in:pickup,delivery',
            'delivery_address' => 'nullable|required_if:delivery_type,delivery|string',
        ]);

        // Ambil item keranjang
        $cartItems = CartItem::where('user_id', Auth::id())->get();
        if ($cartItems->isEmpty()) {
            return back()->with('error', 'Keranjang kosong.');
        }

        // Hitung total quantity untuk cek slot
        $totalQuantity = $cartItems->sum('quantity');

        // Cek Slot Produksi
        $slot = ProductionSlot::firstOrCreate(
            ['date' => $request->pickup_date],
            ['quota' => 200, 'used_quota' => 0, 'is_closed' => false]
        );

        if($slot->is_closed || ($slot->quota - $slot->used_quota) < $totalQuantity){
            return back()->with('error', 'Maaf, kuota produksi untuk tanggal ini tidak mencukupi atau sudah ditutup.');
        }

        // Hitung Total Harga
        $totalPrice = $cartItems->sum(fn($item) => $item->product->price * $item->quantity);

        // Gunakan Transaction agar data aman
        DB::transaction(function () use ($request, $cartItems, $totalPrice) {
            // 1. Buat Order
            $order = Order::create([
                'user_id' => Auth::id(),
                'pickup_date' => $request->pickup_date,
                'pickup_time' => $request->pickup_time,
                'delivery_type' => $request->delivery_type,
                'delivery_address' => $request->delivery_address,
                'status' => 'pending',
                'total_price' => $totalPrice,
            ]);

            // 2. Pindahkan item dari Cart ke OrderItem
            foreach ($cartItems as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'price_per_unit' => $item->product->price,
                    'subtotal' => $item->product->price * $item->quantity,
                ]);
            }

            // 3. Kosongkan Keranjang
            CartItem::where('user_id', Auth::id())->delete();
        });

        return redirect()->route('user.list-pesanan')->with('success', 'Checkout berhasil! Pesanan Anda sedang diproses.');
    }
    // Update quantity di halaman keranjang
    public function update(Request $request, $id)
    {
        $request->validate([
            'quantity' => 'required|integer|min:20'
        ]);

        $cartItem = CartItem::where('user_id', Auth::id())->where('id', $id)->firstOrFail();
        $cartItem->update(['quantity' => $request->quantity]);

        return redirect()->route('cart.index')->with('success', 'Jumlah berhasil diubah');
    }

    // Hapus item dari keranjang
    public function destroy($id)
    {
        $cartItem = CartItem::where('user_id', Auth::id())->where('id', $id)->firstOrFail();
        $cartItem->delete();

        return redirect()->route('cart.index')->with('success', 'Produk dihapus dari keranjang');
    }
}