<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function index()
    {
        $user = Auth::guard('web')->user();

        if (!$user) {
            return redirect()->route('dang-nhap')->withErrors(['role' => 'Bạn cần đăng nhập!']);
        }

        if ($user->role !== 'admin') {
            return redirect()->route('dang-nhap')->withErrors(['role' => 'Bạn không có quyền truy cập!']);
        }

        return view('admin.dashboard', compact('user'));
    }
}
