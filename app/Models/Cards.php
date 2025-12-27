<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cards extends Model
{
    use HasFactory;

    protected $table = 'cards';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'card_set_id',
        'name',
        'card_type',
        'image_url',
        'rarity',
        'edition',
        'estimated_market_price',
    ];

    protected $casts = [
        'estimated_market_price' => 'decimal:2',
    ];

    public function cardSet()
    {
        return $this->belongsTo(CardSets::class, 'card_set_id');
    }

    public function listings()
    {
        return $this->hasMany(Listings::class, 'card_id');
    }

    public function wishlists()
    {
        return $this->hasMany(Wishlists::class, 'card_id');
    }

    public function userCollections()
    {
        return $this->hasMany(UserCollections::class, 'card_id');
    }
}
