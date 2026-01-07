<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Notification extends Model
{
    protected $table = 'notifications';

    protected $fillable = [
        'user_id',
        'type',
        'title',
        'message',
        'action_url',
        'is_read',
        'read_at',
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'read_at' => 'datetime',
        'created_at' => 'datetime',
    ];

    /**
     * Get the user that owns the notification.
     * Returns null for system-wide notifications.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(Users::class, 'user_id');
    }

    /**
     * Check if this is a system-wide notification.
     */
    public function isSystemWide(): bool
    {
        return $this->user_id === null;
    }

    /**
     * Mark notification as read.
     */
    public function markAsRead(): void
    {
        if (!$this->is_read) {
            $this->update([
                'is_read' => true,
                'read_at' => now(),
            ]);
        }
    }

    /**
     * Scope to get only unread notifications.
     */
    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    /**
     * Get icon based on notification type.
     */
    public function getIconAttribute(): string
    {
        return match($this->type) {
            'order' => 'lni-cart',
            'message' => 'lni-envelope',
            'listing' => 'lni-package',
            'system' => 'lni-cog',
            'wishlist' => 'lni-heart',
            default => 'lni-bell',
        };
    }

    /**
     * Get color based on notification type.
     */
    public function getColorAttribute(): string
    {
        return match($this->type) {
            'order' => '#4ECDC4',
            'message' => '#45B7D1',
            'listing' => '#F7DC6F',
            'system' => '#BB8FCE',
            'wishlist' => '#FF6B6B',
            default => '#98D8C8',
        };
    }
}
