<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Notifi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class NotifiController extends Controller
{
    public function index()
    {
        $Notifis = Notifi::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json(['status' => 200, 'Notifis' => $Notifis]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'link' => 'nullable|string|max:255',
        ]);

        $Notifi = Notifi::create([
            'user_id' => Auth::id(),
            'title' => $request->title,
            'message' => $request->message,
            'link' => $request->link,
            'status' => 'unread',
        ]);

        return response()->json([
            'status' => 201,
            'message' => 'Thông báo đã được tạo.',
            'Notifi' => $Notifi,
        ], 201);
    }

    public function show($id)
    {
        $Notifi = Notifi::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        return response()->json(['status' => 200, 'Notifi' => $Notifi]);
    }

    public function update(Request $request, $id)
    {
        $Notifi = Notifi::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $Notifi->update(['status' => 'read']);

        return response()->json([
            'status' => 200,
            'message' => 'Thông báo đã được cập nhật.',
            'Notifi' => $Notifi,
        ]);
    }

    public function destroy($id)
    {
        $Notifi = Notifi::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $Notifi->delete();

        return response()->json([
            'status' => 200,
            'message' => 'Thông báo đã được xóa.',
        ]);
    }
}
