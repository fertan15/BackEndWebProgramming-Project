<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Listings extends Model
{
    use HasFactory;

    protected $table = 'listings';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'card_id',
        'seller_id',
        'price',
        'condition_text',
        'description',
        'quantity',
        'is_active',
        'status',
        'created_at',
        'user_collection_id',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'quantity' => 'integer',
        'is_active' => 'boolean',
        'created_at' => 'datetime',
    ];

    public function card()
    {
        return $this->belongsTo(Cards::class, 'card_id');
    }

    public function seller()
    {
        return $this->belongsTo(Users::class, 'seller_id');
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItems::class, 'listing_id');
    }
}
