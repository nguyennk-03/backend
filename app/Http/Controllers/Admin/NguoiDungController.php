<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;

class NguoiDungController extends Controller
{
    // app/Http/Controllers/Admin/UserController.php
    public function index(Request $request)
    {
        $query = User::query();
        if ($request->role) {
            $query->where('role', $request->role);
        }
        if ($request->sort_by) {
            switch ($request->sort_by) {
                case 'created_at_desc':
                    $query->orderBy('created_at', 'desc');
                    break;
                case 'created_at_asc':
                    $query->orderBy('created_at', 'asc');
                    break;
                case 'name_asc':
                    $query->orderBy('name', 'asc');
                    break;
                case 'name_desc':
                    $query->orderBy('name', 'desc');
                    break;
            }
        }
        $users = $query->paginate(10);
        return view('admin.users.index', compact('users'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'nullable|string|max:15',
            'role' => 'required|in:user,admin',
            'address' => 'nullable|string|max:255',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Đồng bộ với tên trường trong form
            'password' => 'required|string|min:8',
        ]);

        try {
            $validatedData = $request->only(['name', 'email', 'phone', 'role', 'address']);
            $validatedData['password'] = Hash::make($request->password); // Mã hóa mật khẩu

            if ($request->hasFile('avatar')) {
                $file = $request->file('avatar');

                if (!$file->isValid()) {
                    throw new \Exception('File ảnh không hợp lệ: ' . $file->getErrorMessage());
                }

                $publicPath = public_path('images/users/new');
                if (!file_exists($publicPath)) {
                    mkdir($publicPath, 0755, true);
                }

                $fileName = uniqid() . '.' . $file->getClientOriginalExtension();
                $file->move($publicPath, $fileName);
                $validatedData['avatar'] = 'images/users/new/' . $fileName; // Lưu đường dẫn ảnh vào trường avatar
            }

            User::create($validatedData);

            return redirect()->route('nguoi-dung.index')->with('success', 'Thêm người dùng thành công!');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Có lỗi xảy ra: ' . $e->getMessage()])->withInput();
        }
    }


    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'phone' => 'nullable|string|max:15',
            'role' => 'required|in:user,admin',
            'address' => 'nullable|string|max:255',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        try {
            $user = User::findOrFail($id);
            $data = $request->only(['name', 'email', 'phone', 'role', 'address']);

            if ($request->hasFile('avatar')) { 
                $file = $request->file('avatar');

                if (!$file->isValid()) {
                    throw new \Exception('File ảnh không hợp lệ: ' . $file->getErrorMessage());
                }

                if (!empty($user->avatar) && file_exists(public_path($user->avatar))) {
                    unlink(public_path($user->avatar));
                }

                $fileName = uniqid() . '.' . $file->getClientOriginalExtension();
                $publicPath = public_path('images/users/new'); 

                if (!file_exists($publicPath)) {
                    mkdir($publicPath, 0755, true);
                }

                $file->move($publicPath, $fileName);
                $data['avatar'] = 'images/users/new/' . $fileName; 
            } else {
                $data['avatar'] = $user->avatar; 
            }

            $user->update($data);

            return redirect()->route('nguoi-dung.index')->with('success', 'Cập nhật người dùng thành công!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withErrors(['error' => 'Có lỗi xảy ra: ' . $e->getMessage()])
                ->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            $user = User::findOrFail($id);

            if ($user->avatar && Storage::disk('public')->exists('avatars/' . $user->avatar)) {
                Storage::disk('public')->delete('avatars/' . $user->avatar);
            }

            $user->delete();

            return redirect()->route('nguoi-dung.index')->with('success', 'Xóa người dùng thành công!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Có lỗi xảy ra khi xóa người dùng: ' . $e->getMessage());
        }
    }
}