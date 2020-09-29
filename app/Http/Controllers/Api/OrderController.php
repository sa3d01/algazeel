<?php

namespace App\Http\Controllers\Api;

use App\Contact;
use App\DropDown;
use App\Http\Controllers\Controller;
use App\Http\Resources\OrderCollection;
use App\Http\Resources\OrderResource;
use App\Http\Resources\UserResource;
use App\Order;
use App\Rating;
use App\Setting;
use App\User;
use App\Wallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\UserNotDefinedException;

class OrderController extends MasterController
{
    protected $model;

    public function __construct(Order $model)
    {
        $this->model = $model;
        parent::__construct();
    }
    public function validation_rules($method, $id = null)
    {
        return [
            'type_id' => 'required',
            'note' => 'required',
            'provider_id' => 'required',
        ];
    }
    public function validation_messages()
    {
        return array(
            'required' => ':attribute يجب ادخال الـ',
        );
    }
    public function types(){
        $types=DropDown::whereClass('Order')->get();
        $data=[];
        foreach ($types as $type){
            $arr['id']=$type->id;
            $arr['name']=$type->name['ar'];
            $data[]=$arr;
        }
        return $this->sendResponse($data);
    }

    public function store(Request $request)
    {
        return parent::store($request);
    }

    public function show($id)
    {
        if (!Order::find($id))
            return $this->sendError('not found');
        return $this->sendResponse(OrderResource::make(Order::find($id)));
    }
    public function status_list($status)
    {
        if (auth()->user()->user_type->name=='user'){
            $data=new OrderCollection($this->model->where(['status'=>$status,'user_id'=>auth()->user()->id])->get());
        }else{
            $data=new OrderCollection($this->model->where(['status'=>$status,'provider_id'=>auth()->user()->id])->get());
        }
        return $this->sendResponse($data);
    }
    public function send_offer($id,Request $request){
        $order=Order::find($id);
        if ($order->price != 0)
            return $this->sendError('ﻻ يمكن القيام بهذه العملية حاليا');
        $order->update([
            'price'=>$request['price']
        ]);
        $title='تم استقبال العرض الخاص بطلب رقم '.$order->id.' احصل عليه الآن';
        $this->notify($order,$request->user(),$order->user,$title);
        return $this->sendResponse('تم الارسال بنجاح');
    }
    public function accept_offer($id,Request $request){
        $order=Order::find($id);
        if ($order->status != 'new')
            return $this->sendError('ﻻ يمكن القيام بهذه العملية حاليا');
        $order->update([
            'status'=>'in_progress'
        ]);
        $title='تمت موافقة  المستخدم على عرضك للطلب رقم '.$order->id;
        $this->notify($order,$request->user(),$order->provider,$title);
        return $this->sendResponse('تمت العملية بنجاح');
    }
    public function refuse_offer($id,Request $request){
        $order=Order::find($id);
        if ($order->status != 'new')
            return $this->sendError('ﻻ يمكن القيام بهذه العملية حاليا');
        $title='قام المستخدم برفض عرضك للطلب رقم '.$order->id;
        $order->update([
            'status'=>'done',
            'cancel_reason'=>$title
        ]);
        $this->notify($order,$request->user(),$order->provider,$title);
        return $this->sendResponse('تمت العملية بنجاح');
    }
    public function pay($id,Request $request){
        $order=Order::find($id);
//        if ($order->paid != 0)
//            return $this->sendError('ﻻ يمكن القيام بهذه العملية حاليا');
        $order->update([
            'paid'=>1
        ]);
        $user=$order->user;
        $user_chat_orders=[];
        if (array_key_exists('chat_orders',(array)$user->more_details)){
            $user_chat_orders[]=$user->more_details['chat_orders'];
        }
        $user_chat_orders[]=array_push($user_chat_orders,$id);
        $user->update(
            [
                'more_details'=>[
                    'chat_orders'=>$user_chat_orders,
                ],
            ]
        );
        $provider=$order->provider;
        $provider_chat_orders=[];
        if (array_key_exists('chat_orders',(array)$provider->more_details)){
            $provider_chat_orders[]=$provider->more_details['chat_orders'];
        }
        $provider_chat_orders[]=array_push($provider_chat_orders,$id);
        $provider->update(
            [
                'more_details'=>[
                    'chat_orders'=>$provider_chat_orders,
                ],
            ]
        );
        $title='قام المستخدم بدفع قيمة الطلب رقم '.$order->id;
        $this->notify($order,$request->user(),$order->provider,$title);
        return $this->sendResponse('تمت العملية بنجاح');
    }
    public function done($id,Request $request){
        $order=Order::find($id);
        if ($order->status == 'done')
            return $this->sendError('ﻻ يمكن القيام بهذه العملية حاليا');
        $order->update([
            'status'=>'done'
        ]);
        $setting=Setting::first();
        Wallet::create([
            'order_id'=>$id,
            'app_ratio'=>$order->price*$setting->more_details['app_ratio']/100,
            'provider_ratio'=>$order->price-($order->price*$setting->more_details['app_ratio']/100)
        ]);
        $provider=User::find($order->provider_id);
        $provider->update([
           'wallet'=>$provider->wallet+($order->price-($order->price*$setting->more_details['app_ratio']/100))
        ]);
        $title='قام المستخدم باتمام الطلب رقم '.$order->id;
        $this->notify($order,$request->user(),$order->provider,$title);
        return $this->sendResponse('تمت العملية بنجاح');
    }
    public function update($id,Request $request){
        $order=Order::find($id);
        $note=$request['note'];
        if ($order->user_id != auth()->user()->id || $order->status!='new')
            return $this->sendError('ﻻ يمكن القيام بهذه العملية حاليا');
        $order->update([
            'note'=>$note
        ]);
        return $this->sendResponse('تمت العملية بنجاح');
    }
    public function rating($id,Request $request){
        $order=Order::find($id);
        if (!Order::find($id)){
            return $this->sendError('ﻻ يمكن القيام بهذه العملية حاليا');
        }
        $validator = Validator::make($request->only('rate'),['rate'=>'required|numeric'],$this->validation_messages());
        if ($validator->fails()) {
            return $this->sendError($validator->errors()->first());
        }
        $rated=Rating::where('order_id',$id)->first();
        if ($rated){
            return $this->sendError('ﻻ يمكن القيام بهذه العملية حاليا');
        }
        Rating::create([
           'order_id'=>$id,
           'rate'=>$request['rate']
        ]);
        $title='قام المستخدم بتقييم الطلب رقم '.$id;
        $this->notify($order,$request->user(),$order->provider,$title);
        return $this->sendResponse('تمت العملية بنجاح');
    }




}
