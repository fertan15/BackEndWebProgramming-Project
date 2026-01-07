<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\Users;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * Display notifications.
     * - Guests: no notifications (system-wide are delivered as per-user rows)
     * - Authenticated (including admins): only their own notifications
     */
    public function index()
    {
        if (Auth::check()) {
            $notifications = Notification::where('user_id', Auth::id())
                ->orderBy('created_at', 'desc')
                ->paginate(20);

            $unreadCount = Notification::where('user_id', Auth::id())
                ->unread()
                ->count();
        } else {
            $notifications = Notification::whereRaw('1 = 0')->paginate(20);
            $unreadCount = 0;
        }

        return view('notifications', [
            'notifications' => $notifications,
            'unreadCount' => $unreadCount,
        ]);
    }

    /**
     * Mark a notification as read.
     */
    public function markAsRead($id)
    {
        $notification = Notification::where('user_id', Auth::id())
            ->findOrFail($id);

        $notification->markAsRead();

        return redirect()->route('notifications.index')->with('success', 'Notification marked as read');
    }

    /**
     * Mark all notifications as read.
     */
    public function markAllAsRead()
    {
        Notification::where('user_id', Auth::id())
            ->unread()
            ->update([
                'is_read' => true,
                'read_at' => now(),
            ]);

        return redirect()->route('notifications.index')->with('success', 'All notifications marked as read');
    }

    /**
     * Show the form for creating a new notification (admin only).
     */
    public function create()
    {
        // Check if user is admin
        if (!session('is_admin')) {
            abort(403, 'Unauthorized action.');
        }

        $users = Users::orderBy('name')->get();

        return view('create_notification', compact('users'));
    }

    /**
     * Store a newly created notification (admin only).
     */
    public function store(Request $request)
    {
        // Check if user is admin
        if (!session('is_admin')) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'type' => 'required|in:order,message,listing,system,wishlist',
            'notification_type' => 'required|in:system,specific',
            'user_id' => 'nullable|exists:users,id',
        ]);

        if ($validated['notification_type'] === 'system') {
            // Fan out to every user so each has their own row
            $userIds = Users::pluck('id');
            foreach ($userIds as $uid) {
                Notification::create([
                    'user_id' => $uid,
                    'type' => $validated['type'],
                    'title' => $validated['title'],
                    'message' => $validated['message'],
                    'action_url' => null,
                    'is_read' => false,
                ]);
            }
        } else {
            // Specific user
            Notification::create([
                'user_id' => $validated['user_id'],
                'type' => $validated['type'],
                'title' => $validated['title'],
                'message' => $validated['message'],
                'action_url' => null,
                'is_read' => false,
            ]);
        }

        return redirect()->route('notifications.index')->with('success', 'Notification created successfully');
    }

    /**
     * Delete a notification.
     */
    public function destroy($id)
    {
        $notification = Notification::where('id', $id)
            ->where(function($q) {
                $q->where('user_id', Auth::id())
                  ->orWhere(function($q2) {
                      $q2->whereNull('user_id')->whereRaw('1 = 0'); // system-wide rows no longer used
                  });
            })
            ->first();

        // If not owner, allow admin to delete
        if (!$notification && session('is_admin')) {
            $notification = Notification::find($id);
        }

        if (!$notification) {
            abort(404);
        }

        $notification->delete();

        return redirect()->route('notifications.index')->with('success', 'Notification deleted');
    }

    /**
     * Get unread notification count (for AJAX).
     */
    public function unreadCount()
    {
        $count = Notification::where(function($query) {
                $query->where('user_id', Auth::id())
                      ->orWhereNull('user_id');
            })
            ->unread()
            ->count();

        return response()->json(['count' => $count]);
    }
}
