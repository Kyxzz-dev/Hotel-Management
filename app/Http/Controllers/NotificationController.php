<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function markSeen(Request $request)
    {
        $user = Auth::user();
        $user->notification_seen_at = now();
        $user->save();

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'seen_at' => optional($user->notification_seen_at)->toDateTimeString(),
            ]);
        }

        return back();
    }
}
