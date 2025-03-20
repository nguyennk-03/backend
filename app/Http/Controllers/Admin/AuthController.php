<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;

class AuthController extends Controller
{
    // Hiển thị trang bảng điều khiển
    public function dashboard()
    {
        $user = Auth::guard('web')->user();

        // Kiểm tra vai trò của người dùng
        if ($user->role === 'user') {
            return view('user.dashboard', compact('user')); // Nếu là user, hiển thị user/dashboard.blade.php
        } elseif ($user->role === 'admin') {
            return view('admin.dashboard', compact('user')); // Nếu là admin, hiển thị admin/dashboard.blade.php
        }

        // Trường hợp không xác định được vai trò
        return redirect()->route('dang-nhap')->withErrors(['role' => 'Vai trò không hợp lệ.']);
    }

    // Hiển thị form đăng nhập
    public function formLogin()
    {
        if (Auth::guard('web')->check()) {
            return redirect()->route('bang-dieu-khien');
        }
        return view('auth.login');
    }

    // Xử lý đăng nhập
    public function handleLogin(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::guard('web')->attempt($request->only('email', 'password'), $request->filled('remember'))) {
            $request->session()->regenerate();
            return redirect()->route('bang-dieu-khien');
        }

        return back()->withErrors(['email' => 'Thông tin đăng nhập không chính xác.'])->onlyInput('email');
    }

    // Hiển thị form đăng ký
    public function formRegister()
    {
        if (Auth::guard('web')->check()) {
            return redirect()->route('bang-dieu-khien');
        }
        return view('auth.register'); // Khớp với auth/register.blade.php
    }

    // Xử lý đăng ký
    public function handleRegister(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'terms' => 'accepted',
        ], [
            'terms.accepted' => 'Bạn phải đồng ý với Điều khoản và Điều kiện.',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        Auth::guard('web')->login($user);

        return redirect()->route('bang-dieu-khien')->with('success', 'Đăng ký thành công!');
    }

    // Hiển thị form quên mật khẩu
    public function showForgotPasswordForm()
    {
        return view('auth.passwords.email'); // Khớp với auth/passwords/email.blade.php
    }

    // Gửi email đặt lại mật khẩu
    public function sendResetLink(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $status = Password::sendResetLink($request->only('email'));

        return $status === Password::RESET_LINK_SENT
            ? back()->with(['status' => __($status)])
            : back()->withErrors(['email' => __($status)]);
    }

    // Hiển thị form đặt lại mật khẩu
    public function showResetForm($token)
    {
        return view('auth.passwords.reset', ['token' => $token]); // Khớp với auth/passwords/reset.blade.php
    }

    // Xử lý đặt lại mật khẩu
    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->setRememberToken(Str::random(60));

                $user->save();
                event(new PasswordReset($user));
            }
        );

        return $status === Password::PASSWORD_RESET
            ? redirect()->route('dang-nhap')->with('status', __($status))
            : back()->withErrors(['email' => [__($status)]]);
    }

    // Xử lý đăng xuất
    public function logout(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('dang-nhap');
    }
}