<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();
        if ($user->role !== 'admin') {
            return redirect()->route('admin')->with('error', 'Bạn không có quyền truy cập trang admin');
        }

        return view('admin.dashboard'); 
    }
}