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

    protected static function booted()
    {
        // When a card is created, update its set's total_cards to match actual count
        static::created(function (Cards $card) {
            $setId = $card->card_set_id;
            if ($setId) {
                $actual = self::where('card_set_id', $setId)->count();
                CardSets::where('id', $setId)->update(['total_cards' => $actual]);
            }
        });

        // If a card's set changes, resync counts for both old and new sets
        static::updated(function (Cards $card) {
            if ($card->isDirty('card_set_id')) {
                $originalSetId = $card->getOriginal('card_set_id');
                $newSetId = $card->card_set_id;
                if ($originalSetId) {
                    $originalCount = self::where('card_set_id', $originalSetId)->count();
                    CardSets::where('id', $originalSetId)->update(['total_cards' => $originalCount]);
                }
                if ($newSetId) {
                    $newCount = self::where('card_set_id', $newSetId)->count();
                    CardSets::where('id', $newSetId)->update(['total_cards' => $newCount]);
                }
            }
        });

        // Keep counts accurate when a card is deleted
        static::deleted(function (Cards $card) {
            $setId = $card->card_set_id;
            if ($setId) {
                $actual = self::where('card_set_id', $setId)->count();
                CardSets::where('id', $setId)->update(['total_cards' => $actual]);
            }
        });
    }
}
