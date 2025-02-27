<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    use HasFactory;

    protected $fillable = ['product_variant_id', 'image_url'];
    
    public function productVariant()
    {
        return $this->belongsTo(ProductVariant::class, 'product_variant_id');
    }
}

