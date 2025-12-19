<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductClosure extends Model
{
    protected $fillable = ['product_id', 'date'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}