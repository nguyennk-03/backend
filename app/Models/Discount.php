<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Discount extends Model {
    use HasFactory;

    protected $table = 'discounts';

    protected $fillable = [
        'code',
        'discount_type',
        'value',
        'start_date',
        'end_date',
    ];

    protected $casts = [
        'value' => 'decimal:2',
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function products() {
        return $this->belongsToMany(Product::class, 'product_discounts');
    }

    public function isValid()
    {
        return now()->between($this->start_date, $this->end_date);
    }
}
