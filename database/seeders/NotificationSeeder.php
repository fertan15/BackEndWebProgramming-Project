<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Notification;
use App\Models\Users;

class NotificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get the first user (or you can specify a user ID)
        $user = Users::first();

        if (!$user) {
            $this->command->info('No users found. Please create a user first.');
            return;
        }

        // Sample notifications
        $notifications = [
            [
                'user_id' => $user->id,
                'type' => 'order',
                'title' => 'Order Placed Successfully',
                'message' => 'Your order #12345 has been placed successfully and is being processed.',
                'action_url' => '/orders/12345',
                'is_read' => false,
            ],
            [
                'user_id' => $user->id,
                'type' => 'message',
                'title' => 'New Message from John Doe',
                'message' => 'You have received a new message: "Hi, is this card still available?"',
                'action_url' => '/chat',
                'is_read' => false,
            ],
            [
                'user_id' => $user->id,
                'type' => 'listing',
                'title' => 'New Card Listed in Your Watchlist',
                'message' => 'A Pikachu card matching your search criteria has been listed for $25.00',
                'action_url' => '/cards/123',
                'is_read' => true,
                'read_at' => now()->subDays(1),
            ],
            [
                'user_id' => $user->id,
                'type' => 'wishlist',
                'title' => 'Wishlist Item Price Drop',
                'message' => 'Great news! A card in your wishlist has dropped in price by 20%',
                'action_url' => '/wishlist',
                'is_read' => false,
            ],
            [
                'user_id' => $user->id,
                'type' => 'system',
                'title' => 'Welcome to PocketRader!',
                'message' => 'Thank you for joining PocketRader. Start browsing cards and building your collection today!',
                'action_url' => '/home',
                'is_read' => true,
                'read_at' => now()->subDays(7),
            ],
        ];

        foreach ($notifications as $notification) {
            Notification::create($notification);
        }

        $this->command->info('Sample notifications created successfully!');
    }
}
