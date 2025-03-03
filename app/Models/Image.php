<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    use HasFactory;

    protected $table = 'images';

    protected $fillable = ['variant_id', 'image_url'];
    
    public function productVariant()
    {
        return $this->belongsTo(ProductVariant::class, 'variant_id');
    }
    public function product()
    {
        return $this->hasOneThrough(
            Product::class,      
            ProductVariant::class,
            'id',                 
            'id',                
            'variant_id',  
            'product_id'          
        );
    }
    
    public static function getRandomImage()
    {
        $directory = public_path('images/products');

        if (!is_dir($directory)) {
            return null; // Nếu thư mục không tồn tại
        }

        $files = array_diff(scandir($directory), ['.', '..']);
        $images = array_filter($files, function ($file) {
            return preg_match('/\.(jpg|jpeg|png|gif)$/i', $file);
        });

        return !empty($images) ? asset('images/products/' . $images[array_rand($images)]) : null;
    }
}

