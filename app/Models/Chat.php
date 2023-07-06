<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{   
    protected $table = 'chats';
    use HasFactory;
    protected $fillable = ['sender', 'chat_message','sender_id','receiver_id','message_status','message_type'];
}
