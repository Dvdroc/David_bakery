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
        
        // Hitung Total: Harga Kue + Harga Kerdus (Setiap 20 kue = 1 Kerdus)
        $total = $cartItems->sum(function($item) {
            $productTotal = $item->product->price * $item->quantity;
            
            // Logika Pembatas Kerdus
            if ($item->packaging_type) {
                $boxCount = ceil($item->quantity / 20); // Pembulatan ke atas (misal 21 jadi 2)
                $packagingTotal = $boxCount * $item->packaging_price;
                return $productTotal + $packagingTotal;
            }

            return $productTotal;
        });

        return view('user.cart', compact('cartItems', 'total'));
    }

    // Menambah item biasa (Home/All Cakes)
    public function addToCart(Request $request, $productId)
    {
        $product = Product::findOrFail($productId);
        
        // Cek item biasa (bukan custom)
        $cartItem = CartItem::where('user_id', Auth::id())
                            ->where('product_id', $productId)
                            ->whereNull('packaging_type') 
                            ->first();

        if ($cartItem) {
            $cartItem->quantity += $request->input('quantity', 1);
            $cartItem->save();
        } else {
            CartItem::create([
                'user_id' => Auth::id(),
                'product_id' => $productId,
                'quantity' => $request->input('quantity', 1)
            ]);
        }

        return redirect()->route('cart.index')->with('success', 'Produk berhasil masuk keranjang!');
    }

    // Menambah Custom Cake
    public function addCustomToCart(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:20', 
            'packaging' => 'required|in:biasa,mika',
        ]);

        // Harga per 1 Kerdus
        $packagingPrice = ($request->packaging === 'mika') ? 15000 : 10000;

        // Cek apakah item serupa sudah ada
        $cartItem = CartItem::where('user_id', Auth::id())
                            ->where('product_id', $request->product_id)
                            ->where('packaging_type', $request->packaging)
                            ->first();

        if ($cartItem) {
            $cartItem->quantity += $request->quantity;
            $cartItem->save();
        } else {
            CartItem::create([
                'user_id' => Auth::id(),
                'product_id' => $request->product_id,
                'quantity' => $request->quantity,
                'packaging_type' => $request->packaging,
                'packaging_price' => $packagingPrice,
            ]);
        }

        return redirect()->route('cart.index')->with('success', 'Custom Cake berhasil masuk keranjang!');
    }

    public function checkout()
    {
        $cartItems = CartItem::with('product')->where('user_id', Auth::id())->get();
        
        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Keranjang Anda kosong.');
        }

        // Hitung total (termasuk logika kerdus jika ada)
        $total = $cartItems->sum(function($item) {
            $productTotal = $item->product->price * $item->quantity;
            if ($item->packaging_type) {
                $boxCount = ceil($item->quantity / 20);
                $packagingTotal = $boxCount * $item->packaging_price;
                return $productTotal + $packagingTotal;
            }
            return $productTotal;
        });

        $minDate = Carbon::today()->addDays(7);
        $maxDate = Carbon::today()->addDays(30);

        // --- TAMBAHAN: Ambil Data Slot untuk JS ---
        // Ambil semua slot yang ada di range tanggal tersebut
        $slots = ProductionSlot::whereBetween('date', [$minDate->format('Y-m-d'), $maxDate->format('Y-m-d')])->get();
        
        // Format menjadi array ['YYYY-MM-DD' => sisa_kuota]
        $slotData = [];
        foreach($slots as $slot) {
            $available = $slot->quota - $slot->used_quota;
            $slotData[$slot->date] = $available < 0 ? 0 : $available;
        }
        // ------------------------------------------

        return view('user.checkout', compact('cartItems', 'total', 'minDate', 'maxDate', 'slotData'));
    }

    public function processCheckout(Request $request)
    {
        $request->validate([
            'pickup_date' => 'required|date|after_or_equal:today',
            'pickup_time' => 'required',
            'delivery_type' => 'required|in:pickup,delivery',
            'delivery_address' => 'nullable|required_if:delivery_type,delivery|string',
        ]);

        $cartItems = CartItem::where('user_id', Auth::id())->get();
        if ($cartItems->isEmpty()) {
            return back()->with('error', 'Keranjang kosong.');
        }

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
        $totalPrice = $cartItems->sum(function($item) {
            $prod = $item->product->price * $item->quantity;
            $pack = 0;
            if ($item->packaging_type) {
                $pack = ceil($item->quantity / 20) * $item->packaging_price;
            }
            return $prod + $pack;
        });

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
                // Hitung subtotal per item
                $boxCount = $item->packaging_type ? ceil($item->quantity / 20) : 0;
                $linePackagingCost = $boxCount * ($item->packaging_price ?? 0);
                $lineProductCost = $item->product->price * $item->quantity;
                $subtotal = $lineProductCost + $linePackagingCost;
                
                $effectivePricePerUnit = $subtotal / $item->quantity;

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'packaging_type' => $item->packaging_type, 
                    'packaging_price' => $item->packaging_price,
                    'price_per_unit' => $effectivePricePerUnit,
                    'subtotal' => $subtotal,
                ]);
            }

            // 3. Kosongkan Keranjang
            CartItem::where('user_id', Auth::id())->delete();
        });

        return redirect()->route('user.list-pesanan')->with('success', 'Checkout berhasil! Pesanan Anda sedang diproses.');
    }

    public function update(Request $request, $id)
    {
        $request->validate(['quantity' => 'required|integer|min:1']);
        $cartItem = CartItem::where('user_id', Auth::id())->where('id', $id)->firstOrFail();
        
        if ($cartItem->packaging_type && $request->quantity < 20) {
             return back()->with('error', 'Pesanan custom cake minimal 20 pcs.');
        }

        $cartItem->update(['quantity' => $request->quantity]);
        return redirect()->route('cart.index')->with('success', 'Jumlah berhasil diubah');
    }

    public function destroy($id)
    {
        CartItem::where('user_id', Auth::id())->where('id', $id)->delete();
        return redirect()->route('cart.index')->with('success', 'Dihapus dari keranjang');
    }
}