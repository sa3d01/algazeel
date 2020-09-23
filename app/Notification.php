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

    protected $fillable = ['sender_id','receiver_id','order_id','title','note','read','admin_notify_type','receivers','type','more_details'];
    protected $casts = [
        'more_details' => 'json',
        'receivers' => 'array',
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
    public function nameForShow($admin_notify_type){
        if ($admin_notify_type=='user'){
            return 'اشعارات العمﻻء' ;
        }elseif ($admin_notify_type=='provider'){
            return 'اشعارات مقدمى الخدمات' ;
        }elseif ($admin_notify_type=='all'){
            return 'اشعارات كل مستخدمى التطبيق' ;
        }else{
            return 'اشعارات موجهة' ;
        }
    }
}
