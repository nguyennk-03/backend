<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Discount extends Model {
    use HasFactory;

    protected $fillable = ['code', 'discount_type', 'value', 'start_date', 'end_date'];

    public function products() {
        return $this->belongsToMany(Product::class, 'product_discounts');
    }
}
