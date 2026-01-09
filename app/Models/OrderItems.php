<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItems extends Model
{
    use HasFactory;

    protected $table = 'order_items';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'listing_id',
        'quantity',
        'price_at_purchase',
        'buyer_id'
    ];

    protected $casts = [
        'quantity' => 'integer',
        'price_at_purchase' => 'decimal:2',
        'purchased_at' => 'datetime',
    ];

    public function listing()
    {
        return $this->belongsTo(Listings::class, 'listing_id');
    }

    public function buyer()
    {
        return $this->belongsTo(Users::class, 'buyer_id');
    }
}
