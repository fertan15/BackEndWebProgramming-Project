<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WalletTransactions extends Model
{
    use HasFactory;

    protected $table = 'wallet_transactions';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'reference_order_item_id',
        'amount',
        'transaction_type',
        'description',
        'created_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'created_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(Users::class, 'user_id');
    }

    public function orderItem()
    {
        return $this->belongsTo(OrderItems::class, 'reference_order_item_id');
    }
}
