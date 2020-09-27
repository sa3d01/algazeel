<?php

namespace App\Http\Controllers\Api;

use App\Chat;
use App\Http\Resources\OrderCollection;
use App\Http\Resources\OrderResource;
use App\Http\Resources\UserResource;
use App\Notification;
use App\Order;
use App\User;
use Edujugon\PushNotification\PushNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ChatController extends MasterController
{
    public function __construct(Chat $model)
    {
        $this->model = $model;
        parent::__construct();
    }
    public function validation_rules($method, $id = null)
    {
        return [
            'order_id' => 'required',
            'type' => 'required',
            'message' => 'required',
        ];
    }
    public function validation_messages()
    {
        return array(
            'required' => ':attribute يجب ادخال الـ',
        );
    }
    public function index(){
        $data=[];

        $order_ids=(array)auth()->user()->more_details['chat_orders'];
        $orders=Order::all();
        foreach ($orders as $order){
            $last_msg=Chat::where('order_id',$order->id)->latest()->first();
            $arr['id']=(int)$order->id;
            $arr['user']=[
                'id'=>$order->user_id,
                'name'=>$order->user->name,
                'image'=>$order->user->image,
            ];
            $arr['provider']=[
                'id'=>$order->provider_id,
                'name'=>$order->provider->name,
                'image'=>$order->provider->image,
            ];
           $arr['last_msg']=$last_msg->msg??"";
           $arr['last_msg_time']=$last_msg ? $last_msg->published_from() :"";
            $data[]=$arr;
        }
        return $this->sendResponse($data);
    }
    public function show($id){
        $data=[];
        $chat=Chat::where('order_id',$id)->latest()->get();
        foreach ($chat as $message){
            $arr['id']=$message->id??0;
            $arr['sender']=[
                'id'=>$message->sender_id,
                'name'=>$message->sender->name,
                'image'=>$message->sender->image,
            ];
            $arr['msg']=$message->msg??"";
            $arr['type']=$message->type??"";
            $arr['time']=$message->published_from();
            $data[]=$arr;
        }
        return $this->sendResponse($data);
    }
    public function destroy($id){
        $order=Order::find($id);
        if (auth()->user()->user_type->name=='user'){

        }else{
            $orders=Order::where(['provider_id'=>auth()->user()->id,'paid'=>1])->get();
        }

    }
    public function upload_attachment($attachment){
        $dest='media/files/chat/';
        if (is_file($attachment)) {
            $fileName = Str::random(10) . '.' . $attachment->getClientOriginalExtension();
            $attachment->move($dest, $fileName);
        }else{
            return $this->sendError('ﻻ يمكن تحديد نوع الملف المرفق !');
        }
       return $fileName;
    }

    public function store(Request $request){
        $validator = Validator::make($request->all(),$this->validation_rules(1),$this->validation_messages());
        if ($validator->fails()) {
            return $this->sendError($validator->errors()->first());
        }

        $order=Order::find($request['order_id']);
        $all=$request->all();
        $all['sender_id']=auth()->user()->id;
        if (auth()->user()->user_type->name=='user'){
            $all['receiver_id']=$order->provider_id;
        }else{
            $all['receiver_id']=$order->user_id;
        }
        if($request['type']!='text'){
            $all['msg']=$this->upload_attachment($request['message']);
        }else{
            $all['msg']=$request['message'];
        }
        $message=$this->model->create($all);
        $title = 'رسالة جديدة';
        $note = ' قام ' . auth()->user()->name .' بارسال رسالة على طلبك '. $message->order_id . ' اضغط لعرض التفاصيل ';
        $this->chat_notify($order,auth()->user(),User::find($all['receiver_id']),$title,$note);
        return $this->sendResponse('تم الارسال');
    }
    public function chat_notify($order,$sender,$receiver,$title,$note){
        $receiver->device['type'] =='IOS'? $fcm_notification=array('title'=>$title, 'sound' => 'default') : $fcm_notification=null;
        $push = new PushNotification('fcm');
        $msg = [
            'notification' => $fcm_notification,
            'data' => [
                'title' => $title,
                'body' => $note,
                'status' => 'chat',
                'type'=>'chat',
                'order'=>OrderResource::make($order)
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
        $notification->note=$note;
        $notification->save();
    }
}
