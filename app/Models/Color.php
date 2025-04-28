<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Color extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'hex_code',
        'is_active', 
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // Nếu bạn có quan hệ với ProductVariant
    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
