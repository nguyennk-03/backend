<?php 

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    /**
     * Hiển thị trang đăng nhập
     */
    public function login()
    {
        return view('auth.login');
    }

    /**
     * Xử lý đăng nhập
     */
    public function loginPost(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            return redirect()->route('dashboard')->with('success', 'Đăng nhập thành công!');
        }

        return back()->withErrors(['email' => 'Email hoặc mật khẩu không đúng!']);
    }

    /**
     * Hiển thị trang đăng ký
     */
    public function register()
    {
        return view('auth.register');
    }

    /**
     * Xử lý đăng ký
     */
    public function registerPost(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('auth.login')->with('success', 'Đăng ký thành công, hãy đăng nhập!');
    }

    /**
     * Hiển thị trang dashboard (chỉ cho người đăng nhập)
     */
    public function dashboard()
    {
        if (Auth::check()) {
            return view('dashboard');
        }

        return redirect()->route('auth.login')->withErrors(['error' => 'Bạn cần đăng nhập trước!']);
    }

    /**
     * Xử lý đăng xuất
     */
    public function logout()
    {
        Auth::logout();
        Session::flush();

        return redirect()->route('auth.login')->with('success', 'Bạn đã đăng xuất thành công!');
    }
}
