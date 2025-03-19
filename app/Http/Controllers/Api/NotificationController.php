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
        $notifications = Notification::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json(['status' => 200, 'notifications' => $notifications]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'link' => 'nullable|string|max:255',
        ]);

        $notification = Notification::create([
            'user_id' => Auth::id(),
            'title' => $request->title,
            'message' => $request->message,
            'link' => $request->link,
            'status' => 'unread',
        ]);

        return response()->json([
            'status' => 201,
            'message' => 'Thông báo đã được tạo.',
            'notification' => $notification,
        ], 201);
    }

    public function show($id)
    {
        $notification = Notification::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        return response()->json(['status' => 200, 'notification' => $notification]);
    }

    public function update(Request $request, $id)
    {
        $notification = Notification::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $notification->update(['status' => 'read']);

        return response()->json([
            'status' => 200,
            'message' => 'Thông báo đã được cập nhật.',
            'notification' => $notification,
        ]);
    }

    public function destroy($id)
    {
        $notification = Notification::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $notification->delete();

        return response()->json([
            'status' => 200,
            'message' => 'Thông báo đã được xóa.',
        ]);
    }
}
