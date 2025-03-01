<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $table = 'categories';
    
    protected $fillable = ['name', 'slug','image_url', 'parent_id'];

    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function allChildren()
    {
        return $this->children()->with('allChildren');
    }

    public function scopeFindBySlug($query, $slug)
    {
        return $query->where('slug', $slug)->first();
    }

    public static function getRandomImage()
    {
        $directory = public_path('images/cate'); // Đường dẫn đến thư mục chứa hình ảnh
        $files = array_diff(scandir($directory), array('..', '.')); // Lấy danh sách tệp tin

        // Lọc ra những tệp tin hình ảnh
        $images = array_filter($files, function ($file) {
            return preg_match('/\.(jpg|jpeg|png|gif)$/i', $file);
        });

        // Nếu có hình ảnh, chọn ngẫu nhiên một hình ảnh
        if (!empty($images)) {
            return 'images/cate/' . $images[array_rand($images)];
        }

        // Trả về null hoặc một đường dẫn mặc định nếu không có hình ảnh
        return null;
    }
}
