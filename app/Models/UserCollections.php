<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserCollections extends Model
{
    use HasFactory;

    protected $table = 'user_collections';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'card_id',
        'condition_text',
        'is_for_trade',
        'added_at',
        'is_listed',
    ];

    protected $casts = [
        'is_for_trade' => 'boolean',
        'added_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(Users::class, 'user_id');
    }

    public function card()
    {
        return $this->belongsTo(Cards::class, 'card_id');
    }
}
