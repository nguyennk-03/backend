<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    // Hiển thị form đăng nhập
    public function formLogin()
    {
        return view('auth.login');
    }

    // Xử lý đăng nhập
    public function handleLogin(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        if (Auth::attempt($credentials)) {
            return redirect()->route('dashboard')->with('success', 'Đăng nhập thành công!');
        }

        return back()->withErrors(['email' => 'Thông tin đăng nhập không chính xác.'])->withInput();
    }

    // Hiển thị form đăng ký
    public function formRegister()
    {
        return view('auth.register');
    }

    // Xử lý đăng ký
    public function handleRegister(Request $request)
    {
        $data = request()->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed',
        ]);
        $user = User::query()->create($data);

        Auth::login($user);

        request()->session()->regenerate();
        // return response()->json($request->all());
        return redirect()->route('user.dashboard');
    }

    public function dashboard(Request $request)
    {
       
        return view('user.dashboard');
    }
    // Xử lý đăng xuất
    public function logout()
    {
        Auth::logout();
        return redirect()->route('login')->with('success', 'Đăng xuất thành công!');
    }
}
