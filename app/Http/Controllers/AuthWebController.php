<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;
use App\Models\User;
use Illuminate\Auth\Events\Registered;

class AuthWebController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->only('index');
        $this->middleware('guest')->only(['formLogin', 'formRegister', 'showForgotPasswordForm']);
        $this->middleware('auth:sanctum')->only('logout');
    }
    public function index()
    {
        return view('user.dashboard', ['user' => Auth::user()]);
    }

    /**
     * Show the login form or redirect if already authenticated.
     *
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function formRegister()
    {
        return Auth::guard('web')->check()
            ? redirect($this->getRedirectUrl(Auth::user()))
            : view('auth.register');
    }
    /**
     * Handle registration request.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function handleRegister(Request $request)
    {
        $key = 'register-attempt:' . $request->ip();
        if (RateLimiter::tooManyAttempts($key, 5)) {
            return back()->withErrors(['email' => 'Quá nhiều lần thử, vui lòng đợi 1 phút.']);
        }

        try {
            $data = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|unique:users,email',
                'password' => 'required|string|min:6|confirmed',
            ]);

            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
                'role' => 'user', // Default to 'user'
            ]);

            event(new Registered($user));

            Auth::guard('web')->login($user);
            $request->session()->regenerate();
            RateLimiter::clear($key);

            return redirect($this->getRedirectUrl($user));
        } catch (ValidationException $e) {
            RateLimiter::hit($key, 60);
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            RateLimiter::hit($key, 60);
            return back()->withErrors(['email' => 'Đăng ký thất bại do lỗi hệ thống.'])->withInput();
        }
    }
    public function formLogin()
    {
        return Auth::guard('web')->check()
            ? redirect($this->getRedirectUrl(Auth::user()))
            : view('auth.login');
    }

    /**
     * Handle login request.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function handleLogin(Request $request)
    {
        $key = 'login-attempt:' . $request->ip();
        if (RateLimiter::tooManyAttempts($key, 5)) {
            return back()->withErrors(['email' => 'Quá nhiều lần thử, vui lòng đợi 1 phút.']);
        }

        $data = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::guard('web')->attempt($data, $request->filled('remember'))) {
            $request->session()->regenerate();
            RateLimiter::clear($key);
            return redirect($this->getRedirectUrl(Auth::user()));
        }

        RateLimiter::hit($key, 60);
        return back()->withErrors(['email' => 'Thông tin đăng nhập không chính xác.']);
    }

    /**
     * Log the user out.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout(Request $request)
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('dang-nhap')->with('status', 'Đăng xuất thành công!');
    }

    /**
     * Show the forgot password form.
     *
     * @return \Illuminate\View\View
     */
    public function showForgotPasswordForm()
    {
        return view('auth.forgot-password');
    }

    /**
     * Send a password reset link to the given email.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function sendResetLink(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $status = Password::sendResetLink($request->only('email'));

        return $status === Password::RESET_LINK_SENT
            ? back()->with(['status' => __($status)])
            : back()->withErrors(['email' => __($status)]);
    }

    /**
     * Show the password reset form.
     *
     * @param string $token
     * @return \Illuminate\View\View
     */
    public function showResetForm($token)
    {
        return view('auth.reset-password', ['token' => $token]);
    }

    /**
     * Handle password reset request.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|confirmed|min:8',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill(['password' => bcrypt($password)])->save();
            }
        );

        return $status === Password::PASSWORD_RESET
            ? redirect()->route('login')->with('status', __($status))
            : back()->withErrors(['email' => [__($status)]]);
    }

    /**
     * Get the redirect URL based on user role.
     *
     * @param \App\Models\User $user
     * @return string
     */
    protected function getRedirectUrl(User $user)
    {
        // Adjust this based on your role-checking logic (e.g., is_admin column or roles)
        return $user->is_admin ? route('admin') : route('bang-dieu-khien');
    }
}