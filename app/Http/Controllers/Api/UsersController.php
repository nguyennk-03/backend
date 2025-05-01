<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UsersController extends Controller
{
    // Lấy danh sách người dùng có filter
    public function index(Request $request)
    {
        $authUser = Auth::user(); 
        if (!$authUser) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $query = User::query();

        if ($authUser->role !== 'admin') {
            $query->where('id', $authUser->id);
        }

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        if ($request->filled('email')) {
            $query->where('email', 'LIKE', '%' . $request->email . '%');
        }

        if ($request->filled('name')) {
            $query->where('name', 'LIKE', '%' . $request->name . '%');
        }

        if ($request->filled('address')) {
            $query->where('address', 'LIKE', '%' . $request->address . '%');
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('is_locked')) {
            $query->where('is_locked', $request->is_locked);
        }

        if ($request->filled(['start_date', 'end_date'])) {
            $query->whereBetween('created_at', [$request->start_date, $request->end_date]);
        }

        $users = $query->orderBy('created_at', 'desc')->get()->makeHidden(['password']);

        return response()->json($users);
    }

    // Chi tiết 1 user
    public function show($id)
    {
        $user = User::findOrFail($id);
        return response()->json($user->makeHidden(['password']));
    }

    // Thêm user mới
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'avatar' => 'nullable|string',
            'role' => ['required', Rule::in(['admin', 'user'])],
            'status' => 'nullable|boolean',
            'is_locked' => 'nullable|boolean',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'address' => $validated['address'] ?? null,
            'avatar' => $validated['avatar'] ?? null,
            'role' => $validated['role'],
            'status' => $validated['status'] ?? 1,
            'is_locked' => $validated['is_locked'] ?? 0,
            'password' => Hash::make($validated['password']),
        ]);

        return response()->json($user->makeHidden(['password']), 201);
    }

    // Cập nhật thông tin user
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'email' => ['sometimes', 'email', Rule::unique('users', 'email')->ignore($id)],
            'phone' => 'sometimes|string|max:20|nullable',
            'address' => 'sometimes|string|nullable',
            'avatar' => 'sometimes|string|nullable',
            'role' => ['sometimes', Rule::in(['admin', 'user'])],
            'status' => 'sometimes|boolean',
            'is_locked' => 'sometimes|boolean',
            'current_password' => 'required_with:new_password|string|min:6', // Kiểm tra mật khẩu cũ
            'new_password' => 'sometimes|string|min:6|confirmed', // Kiểm tra mật khẩu mới và xác nhận
        ]);

        // Kiểm tra mật khẩu cũ
        if ($request->filled('current_password')) {
            if (!Hash::check($request->current_password, $user->password)) {
                return response()->json([
                    'message' => 'Mật khẩu cũ không đúng.',
                ], 400);
            }

            // Kiểm tra mật khẩu mới không trùng với mật khẩu cũ
            if ($request->current_password === $request->new_password) {
                return response()->json([
                    'message' => 'Mật khẩu mới không được trùng với mật khẩu cũ.',
                ], 400);
            }

            // Cập nhật mật khẩu mới
            $validated['password'] = Hash::make($request->new_password);
        }

        // Cập nhật thông tin người dùng
        $user->update($validated);

        // Trả về dữ liệu người dùng đã cập nhật, ẩn mật khẩu
        return response()->json($user->makeHidden(['password']));
    }

    // Xóa user
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return response()->json(['message' => 'Người dùng đã được xóa.'], 200);
    }
}
