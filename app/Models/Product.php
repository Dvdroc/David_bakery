<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name',
        'description',
        'price',
        'image',
        'production_time_estimate',
        'is_active',
    ];

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
    
    public function closures()
    {
        return $this->hasMany(ProductClosure::class);
    }


    public function isClosedOn($date)
    {
        return $this->closures()->where('date', $date)->exists();
    }

    public function reviews()
    {
        return $this->hasManyThrough(
            Order::class, 
            OrderItem::class, 
            'product_id',
            'id',       
            'id',       
            'order_id'
        )->whereNotNull('rating');
    }
}