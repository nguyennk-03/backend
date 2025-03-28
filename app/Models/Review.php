<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model {
    use HasFactory;

    protected $table = 'reviews';

    protected $fillable = ['user_id', 'variant_id', 'rating', 'comment'];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function variant_id() {
        return $this->belongsTo(ProductVariant::class,'variant_id');
    }
}

