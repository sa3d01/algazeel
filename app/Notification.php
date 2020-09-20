<?php

namespace App;

use App\Traits\ModelBaseFunctions;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{

//لديك رسالة تواصل جديدة من مدير التطبيق
//لديك رسالة تواصل جديدة من #
// قام المستخدم برفض عرضك للطلب رقم #
//قامت الإدارة بتصفية مبلغ # من رصيدك لدى الإدارة " بالضغط عليه ينتقل الى المحفظة

    use ModelBaseFunctions;

    private $route='notification';

    protected $fillable = ['sender_id','receiver_id','order_id','title','note','read','type','more_details'];
    protected $casts = [
        'more_details' => 'json',
    ];

    public function sender(){
        return $this->belongsTo(User::class,'sender_id','id');
    }
    public function receiver(){
        return $this->belongsTo(User::class,'receiver_id','id');
    }
    public function order(){
        return $this->belongsTo(Order::class);
    }
}
