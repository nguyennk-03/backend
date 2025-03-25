<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Casts\Attribute;

class News extends Model {
    use HasFactory;

    protected $table = 'news'; 
    protected $fillable = [
        'title',
        'slug',
        'content',
        'image',
        'category_id',
        'brand_id',
        'author',
        'views',
        'status'
    ];

    public function category(): BelongsTo {
        return $this->belongsTo(Category::class);
    }

    public function brand(): BelongsTo {
        return $this->belongsTo(Brand::class);
    }

    public function author(): BelongsTo {
        return $this->belongsTo(User::class);
    }

    protected function imageUrl(): Attribute {
        return Attribute::get(fn ($value) => $value ? asset('storage/news/' . $value) : null);
    }
}
