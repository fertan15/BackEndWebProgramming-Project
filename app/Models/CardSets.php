<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CardSets extends Model
{
    use HasFactory;

    protected $table = 'card_sets';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'name',
        'release_date',
        'description',
        'image_url',
        'total_cards',
    ];

    protected $casts = [
        'release_date' => 'date',
        'total_cards' => 'integer',
    ];

    public function cards()
    {
        return $this->hasMany(Cards::class, 'card_set_id');
    }
}
