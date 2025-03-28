<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model {
    use HasFactory;

    protected $table = 'order_items';

    protected $fillable = ['order_id', 'variant_id', 'quantity', 'price'];

    protected $casts = [
        'quantity' => 'integer',
        'price' => 'decimal:2',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id', 'id');
    }

    public function variant()
    {
        return $this->belongsTo(ProductVariant::class, 'variant_id', 'id');
    }
    
    public function getTotalPriceAttribute()
    {
        return $this->quantity * $this->price;
    }
}
