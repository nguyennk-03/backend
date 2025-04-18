<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class NotificationController extends Controller
{
    public function index()
    {
        $Notifications = Notification::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json(['status' => 200, 'Notifications' => $Notifications]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'link' => 'nullable|string|max:255',
        ]);

        $Notification = Notification::create([
            'user_id' => Auth::id(),
            'title' => $request->title,
            'message' => $request->message,
            'link' => $request->link,
            'status' => 'unread',
        ]);

        return response()->json([
            'status' => 201,
            'message' => 'Thông báo đã được tạo.',
            'Notification' => $Notification,
        ], 201);
    }

    public function show($id)
    {
        $Notification = Notification::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        return response()->json(['status' => 200, 'Notification' => $Notification]);
    }

    public function update(Request $request, $id)
    {
        $Notification = Notification::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $Notification->update(['status' => 'read']);

        return response()->json([
            'status' => 200,
            'message' => 'Thông báo đã được cập nhật.',
            'Notification' => $Notification,
        ]);
    }

    public function destroy($id)
    {
        $Notification = Notification::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $Notification->delete();

        return response()->json([
            'status' => 200,
            'message' => 'Thông báo đã được xóa.',
        ]);
    }
}
