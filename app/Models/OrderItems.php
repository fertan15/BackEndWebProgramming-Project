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
        'order_id',
        'listing_id',
        'quantity',
        'price_at_purchase',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'price_at_purchase' => 'decimal:2',
    ];

    public function order()
    {
        return $this->belongsTo(Orders::class, 'order_id');
    }

    public function listing()
    {
        return $this->belongsTo(Listings::class, 'listing_id');
    }
}
