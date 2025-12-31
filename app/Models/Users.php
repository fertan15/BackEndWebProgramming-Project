<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Auth\Authenticatable as AuthenticatableTrait;

class Users extends Model implements Authenticatable
{
    use HasFactory, AuthenticatableTrait;

    /**
     * Explicit table and primary key (matches SQL dump).
     */
    protected $table = 'users';
    protected $primaryKey = 'id';

    /**
     * The table has only created_at (no updated_at), so disable timestamps.
     */
    public $timestamps = false;

    /**
     * Mass assignable columns based on pocketraderdb.sql
     */
    protected $fillable = [
        'name',
        'username',
        'password_hash',
        'email',
        'phone_number',
        'balance',
        'identity_type',
        'identity_number',
        'identity_image_url',
        'identity_status',
        'otp_code',
        'otp_expires_at',
        'account_status',
        'ban_reason',
        'banned_at',
        'is_admin',
        'last_online',
        'created_at',
    ];

    /**
     * Attribute casting for dates, decimals, and booleans.
     */
    protected $casts = [
        'balance' => 'decimal:2',
        'otp_expires_at' => 'datetime',
        'banned_at' => 'datetime',
        'last_online' => 'datetime',
        'created_at' => 'datetime',
        'is_admin' => 'boolean',
    ];

    /**
     * Hidden attributes for serialization.
     */
    protected $hidden = [
        'password_hash',
    ];

    /**
     * Get the password for the user (Laravel Auth requires this).
     */
    public function getAuthPassword()
    {
        return $this->password_hash;
    }

    // Relationships (based on foreign keys in dump)
    public function listings()
    {
        return $this->hasMany(Listings::class, 'seller_id');
    }

    public function orders()
    {
        return $this->hasMany(Orders::class, 'buyer_id');
    }

    public function walletTransactions()
    {
        return $this->hasMany(WalletTransactions::class, 'user_id');
    }

    public function wishlists()
    {
        return $this->hasMany(Wishlists::class, 'user_id');
    }

    public function userCollections()
    {
        return $this->hasMany(UserCollections::class, 'user_id');
    }

    public function chatsAsUser1()
    {
        return $this->hasMany(Chats::class, 'user1_id');
    }

    public function chatsAsUser2()
    {
        return $this->hasMany(Chats::class, 'user2_id');
    }
}
