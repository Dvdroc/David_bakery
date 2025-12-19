<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductionSlot extends Model
{
    protected $fillable = [
        'date',
        'quota',
        'used_quota',
        'is_closed',
    ];
    // Misal di OrderController@approve
    public function approve(Order $order)
    {
        $slot = ProductionSlot::firstOrCreate(
            ['date' => $order->pickup_date],
            ['quota' => 200, 'used_quota' => 0, 'is_closed' => false]
        );

        if($slot->is_closed || $slot->quota - $slot->used_quota < $order->orderItems->sum('quantity')){
            return back()->with('error','Slot tanggal ini tidak mencukupi.');
        }

        // Update slot
        $slot->used_quota += $order->orderItems->sum('quantity');
        if($slot->used_quota >= $slot->quota){
            $slot->is_closed = true;
        }
        $slot->save();

        // Update status pesanan
        $order->status = 'processing';
        $order->save();

        return back()->with('success','Pesanan berhasil diterima dan slot diupdate.');
    }

}
