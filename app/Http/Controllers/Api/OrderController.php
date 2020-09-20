<?php

namespace App\Http\Controllers\Api;

use App\Contact;
use App\DropDown;
use App\Http\Controllers\Controller;
use App\Http\Resources\OrderCollection;
use App\Http\Resources\OrderResource;
use App\Http\Resources\UserResource;
use App\Order;
use App\Setting;
use App\User;
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
        return OrderResource::make(Order::find($id));
    }
    public function status_list($status)
    {
        return $this->sendResponse(new OrderCollection($this->model->where('status',$status)->get()));
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
        if ($order->paid != 0)
            return $this->sendError('ﻻ يمكن القيام بهذه العملية حاليا');
        $order->update([
            'paid'=>1
        ]);
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
        $title='قام المستخدم باتمام الطلب رقم '.$order->id;
        $this->notify($order,$request->user(),$order->provider,$title);
        return $this->sendResponse('تمت العملية بنجاح');
    }



}
