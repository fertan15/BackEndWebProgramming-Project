<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wishlists extends Model
{
    use HasFactory;

    protected $table = 'wishlists';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'card_id',
        'added_at',
    ];

    protected $casts = [
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
