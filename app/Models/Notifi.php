<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notifi extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'title', 'message', 'link', 'status'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
