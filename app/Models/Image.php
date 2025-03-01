<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    use HasFactory;

    protected $table = 'images';

    protected $fillable = ['product_variant_id', 'image_url'];
    
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
    public function getImageUrlAttribute($value)
    {
        return asset('images/' . $value); // Trả về đường dẫn đầy đủ
    }
    public static function getRandomImage()
    {
        $directory = public_path('images/giay'); // Đường dẫn đến thư mục chứa hình ảnh
        $files = array_diff(scandir($directory), array('..', '.')); // Lấy danh sách tệp tin

        // Lọc ra những tệp tin hình ảnh
        $images = array_filter($files, function ($file) {
            return preg_match('/\.(jpg|jpeg|png|gif)$/i', $file);
        });

        // Nếu có hình ảnh, chọn ngẫu nhiên một hình ảnh
        if (!empty($images)) {
            return 'images/giay/' . $images[array_rand($images)];
        }

        // Trả về null hoặc một đường dẫn mặc định nếu không có hình ảnh
        return null;
    }
}

