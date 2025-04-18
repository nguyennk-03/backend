<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use App\Mail\ResetPasswordLink;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->only('index');
        $this->middleware('guest')->only(['formLogin', 'formRegister', 'showForgotPasswordForm']);
        $this->middleware('auth:sanctum')->only('logout');
    }

    // --- API Methods ---

        public function register(Request $request)
    {
        try {
            $data = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|unique:users,email',
                'password' => 'required|string|min:6|confirmed',
                'role' => 'sometimes|string|in:user,admin',
            ]);

            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
                'role' => $data['role'] ?? 'user',
            ]);

            $token = $user->createToken('authToken', [$user->role])->plainTextToken;

            return response()->json([
                'message' => 'Đăng ký thành công!',
                'user' => $user,
                'token' => $token,
            ], Response::HTTP_CREATED);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Đăng ký thất bại!',
                'errors' => $e->errors(),
            ], Response::HTTP_BAD_REQUEST);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Đăng ký thất bại!',
                'error' => $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function login(Request $request)
    {
        try {
            $data = $request->validate([
                'email' => 'required|email',
                'password' => 'required',
            ]);

            $user = User::where('email', $data['email'])->first();

            if (!$user || !Hash::check($data['password'], $user->password)) {
                throw ValidationException::withMessages([
                    'email' => ['Thông tin đăng nhập không chính xác.'],
                ]);
            }

            $token = $user->createToken('authToken', [$user->role])->plainTextToken;

            return response()->json([
                'message' => 'Đăng nhập thành công!',
                'user' => $user,
                'token' => $token,
                // Optional: redirect path cho frontend xử lý
                'redirect' => $user->role === 'admin' ? '/admin/dashboard' : '/home',
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Đăng nhập thất bại!',
                'errors' => $e->errors(),
            ], Response::HTTP_BAD_REQUEST);
        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'Đã xảy ra lỗi trong quá trình đăng nhập!',
                'error' => $e->getMessage(),
            ], Response::HTTP_UNAUTHORIZED);
        }
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json([
            'message' => 'Đăng xuất thành công!',
        ], Response::HTTP_OK);
    }

    public function sendResetLinkApi(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|email|exists:users,email',
            ]);

            $email = $request->email;
            $user = User::where('email', $email)->first();

            $token = Password::broker()->createToken($user);

            $frontendUrl = config('app.frontend_url', 'http://localhost:3000');
            $url = "{$frontendUrl}/password/reset/{$token}?email=" . urlencode($email);

            Mail::to($email)->send(new ResetPasswordLink($url));

            return response()->json([
                'message' => 'Vào email của bạn để xác nhận yêu cầu đổi mật khẩu!',
            ], Response::HTTP_OK);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Xác thực thất bại.',
                'errors' => $e->errors(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Không thể gửi liên kết đặt lại mật khẩu do lỗi hệ thống.',
                'error' => $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function resetPasswordApi(Request $request)
    {
        try {
            $request->validate([
                'token' => 'required|string',
                'email' => 'required|email|exists:users,email',
                'password' => 'required|string|min:8|confirmed',
            ]);

            $tokenData = DB::table('password_reset_tokens')
                ->where('email', $request->email)
                ->first();

            if (!$tokenData || !Hash::check($request->token, $tokenData->token)) {
                return response()->json([
                    'message' => 'Token đặt lại mật khẩu không hợp lệ hoặc đã hết hạn.',
                ], Response::HTTP_BAD_REQUEST);
            }

            $user = User::where('email', $request->email)->first();

            $user->password = Hash::make($request->password);
            $user->setRememberToken(Str::random(60));
            $user->save();

            event(new PasswordReset($user));

            DB::table('password_reset_tokens')->where('email', $request->email)->delete();

            return response()->json([
                'message' => 'Đặt lại mật khẩu thành công!',
            ], Response::HTTP_OK);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Xác thực thất bại.',
                'errors' => $e->errors(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Không thể đặt lại mật khẩu do lỗi hệ thống.',
                'error' => $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    // --- Web Methods ---

    public function index()
    {
        return view('user.dashboard', ['user' => Auth::user()]);
    }

    public function formLogin()
    {
        return Auth::guard('web')->check()
            ? redirect($this->getRedirectUrl(Auth::user()))
            : view('auth.login');
    }

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

    public function logoutWeb(Request $request)
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        return redirect()->route('login');
    }

    // --- Helper ---

    public function getRedirectUrl(User $user)
    {
        if ($user->role === 'admin') {
            return route('admin.dashboard');
        }
        return route('user.dashboard');
    }
}
