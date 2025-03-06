<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * Danh sách các route sẽ bị loại trừ khỏi kiểm tra CSRF.
     *
     * @var array<int, string>
     */
    protected $except = [
        'api/*',
    ];
}
