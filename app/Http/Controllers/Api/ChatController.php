<?php

namespace App\Http\Controllers\Api;

use App\Chat;
use App\Http\Resources\ChatCollection;
use App\Http\Resources\ChatResource;
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
        $orders=Order::whereIn('id',$order_ids)->latest()->get();
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
    function chat($id){
        $data=[];
        $chat=Chat::where('order_id',$id)->latest()->simplepaginate(10);
        $data['chat']= new ChatCollection($chat);
        $data['current_page']= collect($chat)['current_page'];
        $data['first_page_url']= collect($chat)['first_page_url'];
        $data['from']= collect($chat)['from'];
        $data['next_page_url']= collect($chat)['next_page_url'];
        $data['path']= collect($chat)['path'];
        $data['per_page']= collect($chat)['per_page'];
        $data['prev_page_url']= collect($chat)['prev_page_url'];
        $data['to']= collect($chat)['to'];
        return $data;
    }
    public function show($id){
        $data=$this->chat($id);
        return $this->sendResponse($data);
    }
    public function destroy($id){
        $order_ids=(array)auth()->user()->more_details['chat_orders'];
        $order_ids = array_diff($order_ids, array($id));
        auth()->user()->update(
            [
                'more_details'=>[
                    'chat_orders'=>$order_ids,
                ],
            ]
        );
        return $this->sendResponse('تمت العملية بنجاح');
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
        $this->chat_notify($order,$message,User::find($all['receiver_id']),$title,$note);
        return $this->sendResponse(ChatResource::make($message));
    }
    public function chat_notify($order,$message,$receiver,$title,$note){
        $receiver->device['type'] =='IOS'? $fcm_notification=array('title'=>$title, 'sound' => 'default') : $fcm_notification=null;
        $push = new PushNotification('fcm');
        $msg = [
            'notification' => $fcm_notification,
            'data' => [
                'title' => $title,
                'body' => $note,
                'status' => 'chat',
                'type'=>'chat',
                'order'=>OrderResource::make($order),
                'id'=>$order->id,
                'message'=>ChatResource::make($message)
            ],
            'priority' => 'high',
        ];
        $push->setMessage($msg)
            ->setDevicesToken($receiver->device['id'])
            ->send();
    }
}
