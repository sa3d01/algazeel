<?php

namespace App;

use App\Traits\ModelBaseFunctions;
use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    use ModelBaseFunctions;

    private $route='chat';
    private $images_link='media/images/chat/';

    protected $fillable = ['sender_id','receiver_id','order_id','type','msg'];


    public function order(){
        return $this->belongsTo(Order::class);
    }
    public function sender(){
        return $this->belongsTo(User::class,'sender_id','id');
    }
    public function receiver(){
        return $this->belongsTo(User::class,'receiver_id','id');
    }
}
