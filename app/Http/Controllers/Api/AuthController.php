<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Events\PasswordReset;
use App\Models\User;
use App\Mail\ResetPasswordLink;

class AuthController extends Controller
{
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
                'redirect' => $user->role === 'admin' ? '/admin/bang-dieu-khien' : '/trang-chu',
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

    public function sendResetLink(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|email|exists:users,email',
            ]);

            $token = Password::createToken($user);
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

    public function resetPassword(Request $request)
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
}
