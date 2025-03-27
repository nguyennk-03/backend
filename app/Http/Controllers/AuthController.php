<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Response;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use App\Mail\ResetPasswordLink;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
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
            ], [
                'name.required' => 'Tên là bắt buộc.',
                'email.required' => 'Địa chỉ email là bắt buộc.',
                'email.email' => 'Địa chỉ email không hợp lệ.',
                'email.unique' => 'Địa chỉ email này đã được sử dụng.',
                'password.required' => 'Mật khẩu là bắt buộc.',
                'password.min' => 'Mật khẩu phải có ít nhất 6 ký tự.',
                'password.confirmed' => 'Xác nhận mật khẩu không khớp.',
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
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Đăng ký thất bại!',
                'errors' => $e->errors(),
            ], 400);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Đăng ký thất bại!',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function login(Request $request)
    {
        try {
            $data = $request->validate([
                'email' => 'required|email',
                'password' => 'required',
            ], [
                'email.required' => 'Địa chỉ email là bắt buộc.',
                'email.email' => 'Địa chỉ email không hợp lệ.',
                'password.required' => 'Mật khẩu là bắt buộc.',
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
                'redirect' => $this->getRedirectUrl($user),
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
        return response()->json(['message' => 'Đăng xuất thành công!'], 200);
    }


    public function sendResetLinkApi(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|email|exists:users,email',
            ], [
                'email.required' => 'Vui lòng nhập địa chỉ email.',
                'email.email' => 'Địa chỉ email không hợp lệ.',
                'email.exists' => 'Địa chỉ email này không tồn tại trong hệ thống.',
            ]);

            $email = $request->email;
            $user = User::where('email', $email)->first();

            if (!$user) {
                return response()->json(['message' => 'Không tìm thấy người dùng.'], 404);
            }

            $token = Password::broker()->createToken($user);
            $frontendUrl = config('app.frontend_url');
            $url = "{$frontendUrl}/password/reset/{$token}?email=" . urlencode($email);

            Mail::to($email)->send(new ResetPasswordLink($url));

            return response()->json(['message' => 'Vào email của bạn để xác nhận yêu cầu đổi mật khẩu!'], 200);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Xác thực thất bại.',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Không thể gửi liên kết đặt lại mật khẩu do lỗi hệ thống.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function resetPasswordApi(Request $request)
    {
        try {
            $request->validate([
                'token' => 'required|string',
                'email' => 'required|email|exists:users,email',
                'password' => 'required|string|min:8|confirmed',
            ], [
                'token.required' => 'Token là bắt buộc.',
                'email.required' => 'Vui lòng nhập địa chỉ email.',
                'email.email' => 'Địa chỉ email không hợp lệ.',
                'email.exists' => 'Địa chỉ email này không tồn tại trong hệ thống.',
                'password.required' => 'Vui lòng nhập mật khẩu.',
                'password.min' => 'Mật khẩu phải có ít nhất 8 ký tự.',
                'password.confirmed' => 'Xác nhận mật khẩu không khớp.',
            ]);

            $tokenData = DB::table('password_resets')
                ->where('email', $request->email)
                ->first();

            if (!$tokenData || !Hash::check($request->token, $tokenData->token)) {
                return response()->json(['message' => 'Token đặt lại mật khẩu không hợp lệ hoặc đã hết hạn.'], 400);
            }

            $user = User::where('email', $request->email)->first();
            if (!$user) {
                return response()->json(['message' => 'Người dùng không tồn tại.'], 404);
            }

            $user->password = Hash::make($request->password);
            $user->setRememberToken(Str::random(60));
            $user->save();

            event(new PasswordReset($user));

            DB::table('password_resets')->where('email', $request->email)->delete();

            return response()->json(['message' => 'Đặt lại mật khẩu thành công!'], 200);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Xác thực thất bại.',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Không thể đặt lại mật khẩu do lỗi hệ thống.',
                'error' => $e->getMessage(),
            ], 500);
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
        ], [
            'email.required' => 'Địa chỉ email là bắt buộc.',
            'email.email' => 'Địa chỉ email không hợp lệ.',
            'password.required' => 'Mật khẩu là bắt buộc.',
        ]);

        if (Auth::guard('web')->attempt($data, $request->filled('remember'))) {
            $request->session()->regenerate();
            RateLimiter::clear($key);
            return redirect($this->getRedirectUrl(Auth::user()));
        }

        RateLimiter::hit($key, 60);
        return back()->withErrors(['email' => 'Thông tin đăng nhập không chính xác.']);
    }

    public function formRegister()
    {
        return Auth::guard('web')->check()
            ? redirect($this->getRedirectUrl(Auth::user()))
            : view('auth.register');
    }

    public function handleRegister(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'terms' => 'accepted',
        ], [
            'name.required' => 'Tên là bắt buộc.',
            'email.required' => 'Địa chỉ email là bắt buộc.',
            'email.email' => 'Địa chỉ email không hợp lệ.',
            'email.unique' => 'Địa chỉ email này đã được sử dụng.',
            'password.required' => 'Mật khẩu là bắt buộc.',
            'password.min' => 'Mật khẩu phải có ít nhất 8 ký tự.',
            'password.confirmed' => 'Xác nhận mật khẩu không khớp.',
            'terms.accepted' => 'Bạn phải đồng ý với Điều khoản và Điều kiện.',
        ]);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => 'user',
        ]);

        event(new Registered($user));
        Auth::guard('web')->login($user);
        return redirect('user/bang-dieu-khien')->with('success', 'Đăng ký thành công!');
    }

    public function redirectToGoogleWeb()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallbackWeb()
    {
        try {
            $socialUser = Socialite::driver('google')->user();
            $user = $this->createOrUpdateUserFromSocial('google', $socialUser);
            Auth::guard('web')->login($user);
            return redirect($this->getRedirectUrl($user));
        } catch (\Exception $e) {
            return redirect()->route('dang-nhap')->withErrors(['email' => 'Đăng nhập Google thất bại!']);
        }
    }

    public function showForgotPasswordForm()
    {
        return view('auth.passwords.email');
    }

    public function sendResetLink(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ], [
            'email.required' => 'Vui lòng nhập địa chỉ email.',
            'email.email' => 'Địa chỉ email không hợp lệ.',
            'email.exists' => 'Địa chỉ email này không tồn tại trong hệ thống.',
        ]);

        $status = Password::sendResetLink($request->only('email'));

        return $status === Password::RESET_LINK_SENT
            ? back()->with(['status' => 'Liên kết đặt lại đã được gửi đến email của bạn!'])
            : back()->withErrors(['email' => 'Không tìm thấy email này.']);
    }

    public function showResetForm($token)
    {
        return view('auth.passwords.reset', ['token' => $token]);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email|exists:users,email',
            'password' => 'required|min:8|confirmed',
        ], [
            'token.required' => 'Token là bắt buộc.',
            'email.required' => 'Vui lòng nhập địa chỉ email.',
            'email.email' => 'Địa chỉ email không hợp lệ.',
            'email.exists' => 'Địa chỉ email này không tồn tại trong hệ thống.',
            'password.required' => 'Vui lòng nhập mật khẩu.',
            'password.min' => 'Mật khẩu phải có ít nhất 8 ký tự.',
            'password.confirmed' => 'Xác nhận mật khẩu không khớp.',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                    'remember_token' => Str::random(60),
                ])->save();

                event(new PasswordReset($user));
            }
        );

        return $status === Password::PASSWORD_RESET
            ? redirect()->route('dang-nhap')->with('status', 'Mật khẩu đã được đặt lại thành công!')
            : back()->withErrors(['email' => 'Không thể đặt lại mật khẩu.']);
    }

    public function logoutWeb(Request $request)
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('trang-chu');
    }

    // --- Helper Methods ---
    protected function createOrUpdateUserFromSocial($provider, $socialUser, $role = 'user')
    {
        return User::updateOrCreate(
            ["{$provider}_id" => $socialUser->getId()],
            [
                'name' => $socialUser->getName() ?? $socialUser->getEmail(),
                'email' => $socialUser->getEmail(),
                'password' => Hash::make(Str::random(16)),
                'role' => $role,
                "{$provider}_token" => $socialUser->token,
            ]
        );
    }

    protected function getRedirectUrl(User $user)
    {
        return $user->role === 'admin' ? 'admin/bang-dieu-khien' : 'user/bang-dieu-khien';
    }
}