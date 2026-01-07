<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\Users;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * Display all notifications.
     * - Guests: Only see system-wide notifications (user_id IS NULL)
     * - Admins: See all notifications
     * - Regular users: See both personal and system-wide notifications
     */
    public function index()
    {
        if (Auth::check()) {
            // Check if user is admin
            if (session('is_admin')) {
                // Admin: show ALL notifications
                $notifications = Notification::orderBy('created_at', 'desc')
                    ->paginate(20);

                $unreadCount = Notification::unread()->count();
            } else {
                // Regular user: show personal + system-wide notifications
                $notifications = Notification::where(function($query) {
                        $query->where('user_id', Auth::id())
                              ->orWhereNull('user_id');
                    })
                    ->orderBy('created_at', 'desc')
                    ->paginate(20);

                $unreadCount = Notification::where(function($query) {
                        $query->where('user_id', Auth::id())
                              ->orWhereNull('user_id');
                    })
                    ->unread()
                    ->count();
            }
        } else {
            // Guest: only show system-wide notifications
            $notifications = Notification::whereNull('user_id')
                ->orderBy('created_at', 'desc')
                ->paginate(20);

            $unreadCount = Notification::whereNull('user_id')
                ->unread()
                ->count();
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
        $notification = Notification::where(function($query) {
                $query->where('user_id', Auth::id())
                      ->orWhereNull('user_id');
            })
            ->findOrFail($id);

        $notification->markAsRead();

        return redirect()->route('notifications.index')->with('success', 'Notification marked as read');
    }

    /**
     * Mark all notifications as read.
     */
    public function markAllAsRead()
    {
        Notification::where(function($query) {
                $query->where('user_id', Auth::id())
                      ->orWhereNull('user_id');
            })
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
            'user_id' => 'nullable|exists:users,user_id',
        ]);

        // If system-wide notification, set user_id to null
        if ($validated['notification_type'] === 'system') {
            $validated['user_id'] = null;
        }

        // Remove notification_type from data as it's not a database column
        unset($validated['notification_type']);

        Notification::create($validated);

        return redirect()->route('notifications.index')->with('success', 'Notification created successfully');
    }

    /**
     * Delete a notification.
     */
    public function destroy($id)
    {
        $notification = Notification::where(function($query) {
                $query->where('user_id', Auth::id())
                      ->orWhereNull('user_id');
            })
            ->findOrFail($id);

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
