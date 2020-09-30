<?php

namespace App\Observers;
use App\Http\Resources\OrderResource;
use App\Notification;
use App\Order;
use Edujugon\PushNotification\PushNotification;

class OrderObserver
{
    public function created(Order $order)
    {
        $this->notify($order,request()->user(),$order->provider,$order->id.'# يوجد طلب جديد رقم ');
    }

    public function deleting(Order $order)
    {
        $order->notifications()->delete();
        $order->chats()->delete();
    }

    public function notify($order,$sender,$receiver,$title){
        $receiver->device['type'] =='IOS'? $fcm_notification=array('title'=>$title, 'sound' => 'default') : $fcm_notification=null;
        $push = new PushNotification('fcm');
        $msg = [
            'notification' => $fcm_notification,
            'data' => [
                'title' => $title,
                'body' => $title,
                'status' => $order->status,
                'type'=>'order',
                'id'=>$order->id,
                'order'=>new OrderResource($order)
            ],
            'priority' => 'high',
        ];
        $push->setMessage($msg)
            ->setDevicesToken($receiver->device['id'])
            ->send();
        $notification=new Notification();
        $notification->type='app';
        $notification->sender_id=$sender->id;
        $notification->receiver_id=$receiver->id;
        $notification->order_id=$order->id;
        $notification->title=$title;
        $notification->note=$title;
        $notification->save();
    }
}
