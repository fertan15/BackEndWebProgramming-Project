<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chats extends Model
{
    use HasFactory;

    protected $table = 'chats';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'user1_id',
        'user2_id',
        'created_at',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    public function user1()
    {
        return $this->belongsTo(Users::class, 'user1_id');
    }

    public function user2()
    {
        return $this->belongsTo(Users::class, 'user2_id');
    }

    public function messages()
    {
        return $this->hasMany(Messages::class, 'chat_id');
    }
}
