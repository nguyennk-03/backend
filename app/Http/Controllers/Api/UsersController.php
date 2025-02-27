<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UsersController extends Controller
{
    public function index()
    {
        return response()->json(User::all());
    }

    public function show($id)
    {
        return response()->json(User::findOrFail($id));
    }

    public function store(Request $request)
    {
        $request->validate([
        'full_name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email',
        'phone' => 'required|string|max:20',
        'role' => 'required|in:admin,user',
        'password' => 'required|string|min:6|confirmed',
    ]);

    // Tạo user mới
    User::create([
        'full_name' => $request->full_name,
        'email' => $request->email,
        'phone' => $request->phone,
        'role' => $request->role,
        'password' => Hash::make($request->password), 
    ]);

    return redirect()->route('admin.users.index')->with('success', 'Người dùng đã được tạo!');
}

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $user->update($request->all());
        return response()->json($user);
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return response()->json(null, 204);
    }
}
