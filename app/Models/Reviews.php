<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reviews extends Model
{
    use HasFactory;

    protected $table = 'reviews';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'reviewer_id',
        'reviewee_id',
        'order_id',
        'rating',
        'comment',
        'created_at',
    ];

    protected $casts = [
        'rating' => 'integer',
        'created_at' => 'datetime',
    ];

    public function reviewer()
    {
        return $this->belongsTo(Users::class, 'reviewer_id');
    }

    public function reviewee()
    {
        return $this->belongsTo(Users::class, 'reviewee_id');
    }

    public function order()
    {
        return $this->belongsTo(Orders::class, 'order_id');
    }
}
