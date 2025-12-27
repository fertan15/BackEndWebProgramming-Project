<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Orders extends Model
{
    use HasFactory;

    protected $table = 'orders';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'buyer_id',
        'order_date',
        'order_status',
        'shipping_address',
        'tracking_number',
        'payment_status',
        'paid_at',
        'subtotal',
        'shipping_cost',
        'platform_fee',
        'tax_amount',
        'total_amount',
    ];

    protected $casts = [
        'order_date' => 'datetime',
        'paid_at' => 'datetime',
        'subtotal' => 'decimal:2',
        'shipping_cost' => 'decimal:2',
        'platform_fee' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
    ];

    public function buyer()
    {
        return $this->belongsTo(Users::class, 'buyer_id');
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItems::class, 'order_id');
    }

    public function walletTransactions()
    {
        return $this->hasMany(WalletTransactions::class, 'reference_order_id');
    }
}
