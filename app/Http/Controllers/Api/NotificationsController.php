<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class NotificationsController extends Controller
{
    public function index(Request $request, $paginate = 10)
    {
        $student = $request->user('student');
        $notifications = $student->notifications()->paginate($paginate);

        return response()->json([
            'status' => true,
            'message' => 'Notifications fetched successfully',
            'data' => $notifications,

        ]);
    }

    public function read(Request $request)
    {
        $student = $request->user('student');
        $notification = $student->notifications()->find($request->id);

        if (! $notification) {
            return response()->json([
                'status' => false,
                'message' => 'Notification not found',
            ]);
        }

        $notification->markAsRead();

        return response()->json([
            'status' => true,
            'message' => 'Notification marked as read',
        ]);
    }
}
