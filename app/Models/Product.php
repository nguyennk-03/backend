<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Product extends Model
{
    use HasFactory;

    protected $table = 'products';

    protected $fillable = [
        'name',
        'slug',
        'price',
        'category_id',
        'brand_id',
        'image',
        'description'
    ];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($product) {
            $product->slug = Str::slug($product->name);
        });
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }
    public function images()
    {
        return $this->hasManyThrough(Image::class, ProductVariant::class, 'product_id', 'variant_id');
    }

    public function variants()
    {
        return $this->hasMany(ProductVariant::class, 'product_id');
    }
    public function getTotalStockAttribute()
    {
        return $this->variants->sum('stock');
    }
    public function setNameAttribute($value)
    {
        $this->attributes['name'] = $value;
        $this->attributes['slug'] = $this->generateUniqueSlug($value);
    }

    // Hàm tạo slug duy nhất
    protected function generateUniqueSlug($name)
    {
        $slug = Str::slug($name);
        $originalSlug = $slug;
        $count = 1;

        // Kiểm tra xem slug đã tồn tại chưa, nếu có thì thêm hậu tố
        while (static::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $count++;
        }

        return $slug;
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function discounts()
    {
        return $this->belongsToMany(Discount::class, 'product_discounts');
    }
    public static function getRandomImage()
    {
        $directory = public_path('images/products'); // Đường dẫn đến thư mục chứa hình ảnh
        $files = array_diff(scandir($directory), array('..', '.')); // Lấy danh sách tệp tin

        // Lọc ra những tệp tin hình ảnh
        $images = array_filter($files, function ($file) {
            return preg_match('/\.(jpg|jpeg|png|gif)$/i', $file);
        });

        // Nếu có hình ảnh, chọn ngẫu nhiên một hình ảnh
        if (!empty($images)) {
            return 'images/products/' . $images[array_rand($images)];
        }

        // Trả về null hoặc một đường dẫn mặc định nếu không có hình ảnh
        return null;
    }
}
