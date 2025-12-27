<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Messages extends Model
{
    use HasFactory;

    protected $table = 'messages';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'chat_id',
        'sender_id',
        'content',
        'sent_at',
    ];

    protected $casts = [
        'sent_at' => 'datetime',
    ];

    public function chat()
    {
        return $this->belongsTo(Chats::class, 'chat_id');
    }

    public function sender()
    {
        return $this->belongsTo(Users::class, 'sender_id');
    }
}
