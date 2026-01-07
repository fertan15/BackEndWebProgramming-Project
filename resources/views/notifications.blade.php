@extends('layout.main')

@section('main_contents')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card" style="border-radius: 15px; border: none; box-shadow: 0 2px 10px rgba(0,0,0,0.08);">
                    <div class="card-body" style="padding: 30px;">
                        
                        <!-- Header -->
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <div>
                                <h3 class="mb-1" style="font-weight: 700; color: #1a1a1a;">Notifications</h3>
                                @auth
                                    <p class="text-muted mb-0">You have {{ $unreadCount }} unread notification{{ $unreadCount != 1 ? 's' : '' }}</p>
                                @else
                                    <p class="text-muted mb-0">System announcements and updates</p>
                                @endauth
                            </div>
                            @auth
                                <div class="d-flex gap-2">
                                    @if(session('is_admin'))
                                        <a href="{{ route('notifications.create') }}" class="btn btn-sm btn-success" style="border-radius: 8px; padding: 8px 20px;">
                                            <i class="lni lni-plus"></i> Create Notification
                                        </a>
                                    @endif
                                    @if($unreadCount > 0)
                                        <form action="{{ route('notifications.markAllRead') }}" method="POST" style="display: inline;">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-outline-primary" style="border-radius: 8px; padding: 8px 20px;">
                                                <i class="lni lni-checkmark"></i> Mark all as read
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            @endauth
                        </div>

                        <!-- Success Message -->
                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert" style="border-radius: 10px; border-left: 4px solid #10b981;">
                                <i class="lni lni-checkmark-circle"></i> {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        <!-- Notifications List -->
                        @if($notifications->count() > 0)
                            <div class="notifications-list">
                                @foreach($notifications as $notification)
                                    <div class="notification-item {{ !$notification->is_read ? 'unread' : '' }}" 
                                         style="padding: 20px; margin-bottom: 12px; border-radius: 12px; background: {{ !$notification->is_read ? '#f0f9ff' : '#fff' }}; border: 1px solid {{ !$notification->is_read ? '#e0f2fe' : '#e5e7eb' }}; transition: all 0.2s;">
                                        
                                        <div class="d-flex align-items-start">
                                            <!-- Icon -->
                                            <div class="notification-icon" style="width: 48px; height: 48px; border-radius: 50%; background: {{ $notification->color }}20; display: flex; align-items: center; justify-content: center; margin-right: 16px; flex-shrink: 0;">
                                                <i class="lni {{ $notification->icon }}" style="font-size: 22px; color: {{ $notification->color }};"></i>
                                            </div>

                                            <!-- Content -->
                                            <div class="notification-content" style="flex: 1; min-width: 0;">
                                                <div class="d-flex justify-content-between align-items-start mb-1">
                                                    <h5 class="mb-1" style="font-weight: 600; font-size: 16px; color: #1a1a1a;">
                                                        {{ $notification->title }}
                                                        @if(!$notification->is_read)
                                                            <span class="badge bg-primary" style="font-size: 10px; padding: 3px 8px; border-radius: 6px; margin-left: 8px;">New</span>
                                                        @endif
                                                    </h5>
                                                    <span class="text-muted" style="font-size: 13px; white-space: nowrap;">
                                                        {{ $notification->created_at->diffForHumans() }}
                                                    </span>
                                                </div>
                                                
                                                <p class="mb-2" style="color: #6b7280; font-size: 14px; line-height: 1.6;">
                                                    {{ $notification->message }}
                                                </p>

                                                <!-- Actions -->
                                                @auth
                                                    <div class="d-flex gap-2 mt-2">
                                                        @if(!$notification->is_read)
                                                            <form action="{{ route('notifications.markRead', $notification->id) }}" method="POST" style="display: inline;">
                                                                @csrf
                                                                <button type="submit" class="btn btn-sm" style="background: #e0f2fe; color: #0284c7; border: none; border-radius: 6px; padding: 5px 12px; font-size: 13px; font-weight: 500;">
                                                                    <i class="lni lni-checkmark"></i> Mark as read
                                                                </button>
                                                            </form>
                                                        @endif

                                                        @if(session('is_admin'))
                                                            <form action="{{ route('notifications.destroy', $notification->id) }}" method="POST" style="display: inline;">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn btn-sm" style="background: #fee2e2; color: #dc2626; border: none; border-radius: 6px; padding: 5px 12px; font-size: 13px; font-weight: 500;" onclick="return confirm('Delete this notification?')">
                                                                    <i class="lni lni-trash-can"></i> Delete
                                                                </button>
                                                            </form>
                                                        @endif
                                                    </div>
                                                @endauth
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <!-- Pagination -->
                            <div class="d-flex justify-content-center mt-4">
                                {{ $notifications->links() }}
                            </div>
                        @else
                            <!-- Empty State -->
                            <div class="text-center py-5">
                                <div class="mb-4">
                                    <i class="lni lni-inbox" style="font-size: 80px; color: #d1d5db;"></i>
                                </div>
                                <h4 style="color: #6b7280; font-weight: 600;">No notifications yet</h4>
                                <p class="text-muted">You'll see your notifications here when you receive them</p>
                            </div>
                        @endif

                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .notification-item:hover {
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
            transform: translateY(-2px);
        }
        
        .notification-item.unread {
            border-left: 4px solid #3b82f6 !important;
        }
        
        .btn:hover {
            opacity: 0.9;
            transform: translateY(-1px);
        }
        
        .pagination {
            gap: 5px;
        }
        
        .pagination .page-link {
            border-radius: 8px;
            border: 1px solid #e5e7eb;
            color: #6b7280;
            padding: 8px 12px;
        }
        
        .pagination .page-item.active .page-link {
            background-color: #3b82f6;
            border-color: #3b82f6;
        }
    </style>
@endsection
