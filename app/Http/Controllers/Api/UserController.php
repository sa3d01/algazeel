<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\UserCollection;
use App\Http\Resources\UserResource;
use App\User;
use App\userType;
use App\Wallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class UserController extends MasterController
{
    protected $model;

    public function __construct(User $model)
    {
        $this->model = $model;
        parent::__construct();
    }
    public function validation_rules($method, $id = null)
    {
        if ($method == 2) {
            $rules['mobile'] = 'nullable|regex:/(05)[0-9]{8}/|max:10|unique:users,mobile,' . $id;
            $rules['email'] = 'nullable|email|max:50|unique:users,email,' . $id;
            $rules['name'] = 'nullable|max:30';
            $rules['device'] = 'required';
        } else {
            $rules = [
                'mobile' => 'required|unique:users|max:10|regex:/(05)[0-9]{8}/',
                'email' => 'required|unique:users|email|max:50',
                'name' => 'required|max:30',
                'password' => 'required|min:8',
                'device' => 'required',
            ];
        }
        return $rules;
    }
    public function validation_messages()
    {
        return array(
            'unique' => ' مسجل بالفعل :attribute هذا الـ',
            'required' => ':attribute يجب ادخال الـ',
            'max' =>' يجب أﻻ تزيد قيمته عن :max عناصر :attribute',
            'min' =>' يجب أﻻ تقل قيمته عن :min عناصر :attribute',
            'email'=>'يرجى التأكد من صحة البريد الالكترونى',
            'regex'=>'تأكد من أن رقم الجوال يبدأ ب05 , ويحتوى على عشرة أرقام'
        );
    }
    public function types(){
        $user_types=userType::where('status',1)->get();
        $data=[];
        foreach ($user_types as $type){
            $arr['id']=$type->id;
            $arr['name']=$type->name;
            $data[]=$arr;
        }
        return $this->sendResponse($data);
    }
    public function search(Request $request){
//        $name = str_replace(' ', '', $request['name']);
        $name = $request['name'];
        $users=User::where('name','like','%'.$name.'%')->get();
        if ($request['user_type_id'] && $request['user_type_id']!='')
            $users=User::where('name','like','%'.$name.'%')->where('user_type_id',$request['user_type_id'])->get();
        $data= new UserCollection($users);
        return $this->sendResponse($data);
    }
    public function users_list($user_type_id,Request $request){
        //ToDo take top rated
        $top_providers=User::where('user_type_id',$user_type_id)->take(5)->get();
        $data['top_providers']= new UserCollection($top_providers);
        $providers=User::where('user_type_id',$user_type_id)->simplepaginate(10);
        $data['providers']['data']= new UserCollection($providers);
        $data['providers']['current_page']= collect($providers)['current_page'];
        $data['providers']['first_page_url']= collect($providers)['first_page_url'];
        $data['providers']['from']= collect($providers)['from'];
        $data['providers']['next_page_url']= collect($providers)['next_page_url'];
        $data['providers']['path']= collect($providers)['path'];
        $data['providers']['per_page']= collect($providers)['per_page'];
        $data['providers']['prev_page_url']= collect($providers)['prev_page_url'];
        $data['providers']['to']= collect($providers)['to'];
        $response = [
            'status' => 200,
            'data' => $data,
        ];
        return response()->json($response, 200);
    }
    function send_code($mobile,$activation_code){
        //Mail::to($email)->send(new ConfirmCode($activation_code));
    }
    public function register(Request $request){
        $validator = Validator::make($request->all(),$this->validation_rules(1),$this->validation_messages());
        if ($validator->fails()) {
            return $this->sendError($validator->errors()->first());
        }
        $activation_code = rand(1111, 9999);
        $this->send_code($request['mobile'],$activation_code);
        $all = $request->all();
        $all['activation_code'] = $activation_code;
        $user = User::create($all);
        $token = auth()->login($user);
        $data= new UserResource($user);
        return $this->sendResponse($data)->withHeaders(['apiToken'=>$token,'tokenType'=>'bearer']);
    }
    public function login(Request $request){
        $cred=$request->only(['mobile','password']);
        $token=auth()->attempt($cred);
        if ($token){
            $user=auth()->user();
            $user->update([
                'device'=>[
                    'id'=>$request->device['id'],
                    'type'=>$request->device['type'],
                ]
            ]);
            $data= new UserResource($user);
            return $this->sendResponse($data)->withHeaders(['apiToken'=>$token,'tokenType'=>'bearer']);
        }else{
            return $this->sendError('يوجد مشكلة بالبيانات');
        }
    }
    public function logout(Request $request){
        $user=auth()->user();
        $user->update([
            'device'=>[
                'id'=>null,
                'type'=>null,
            ]
        ]);
        auth()->logout();
        return $this->sendResponse('');
    }
    public function send_activation_code(Request $request){
        $validator = Validator::make($request->only('mobile'),['mobile' => 'required|max:10|regex:/(05)[0-9]{8}/'],$this->validation_messages());
        if ($validator->fails()) {
            return $this->sendError($validator->errors()->first());
        }
        $exist_user=User::whereMobile($request['mobile'])->first();
        if ($exist_user){
            $activation_code = rand(1111, 9999);
            $this->send_code($request['mobile'],$activation_code);
            $exist_user->update(['activation_code'=>$activation_code]);
            return $this->sendResponse(['activation_code'=>$activation_code]);
        }else{
            return $this->sendError('هذا الجوال غير مسجل');
        }
    }
    public function activate(Request $request){
        $validator = Validator::make(
            $request->only('mobile','activation_code'),
            [
                'mobile' => 'required|max:10|regex:/(05)[0-9]{8}/',
                'activation_code'=>'required|numeric'
            ],
            $this->validation_messages());
        if ($validator->fails()) {
            return $this->sendError($validator->errors()->first());
        }
        $exist_user=User::whereMobile($request['mobile'])->first();
        if ($exist_user && $exist_user->activation_code===$request['activation_code']){
            $data= new UserResource($exist_user);
            $exist_user->update(['activation_code'=>null]);
            $token = auth()->login($exist_user);
            return $this->sendResponse($data)->withHeaders(['apiToken'=>$token,'tokenType'=>'bearer']);
        }elseif ($exist_user->activation_code!=$request['activation_code']) {
            return $this->sendError('كود التفعيل غير صحيح');
        }else{
            return $this->sendError('هذا الجوال غير مسجل');
        }
    }
    public function update_password(Request $request){
        $validator = Validator::make(
            $request->only('password','old_password'),
            [
                'old_password' => 'required',
                'password' => 'required|min:8',
            ],
            $this->validation_messages());
        if ($validator->fails()) {
            return $this->sendError($validator->errors()->first());
        }
        $user = auth()->user();
        $token=auth()->attempt(['mobile'=>$user->mobile,'password'=>$request['old_password']]);
        if ($token){
            $user->update(['password'=>$request['password']]);
            $data= new UserResource($user);
            return $this->sendResponse($data)->withHeaders(['apiToken'=>$token,'tokenType'=>'bearer']);
        }else{
            return $this->sendError('كلمة المرور القديمة غير صحيحة');
        }
     }
     public function forget_password(Request $request){
        $validator = Validator::make(
            $request->only('password','mobile'),
            [
                'mobile' => 'required',
                'password' => 'required|min:8',
            ],
            $this->validation_messages());
        if ($validator->fails()) {
            return $this->sendError($validator->errors()->first());
        }
        $user=User::whereMobile($request['mobile'])->first();
         if ($user){
             $user->update(['password'=>$request['password']]);
             $token = auth()->login($user);
             $data= new UserResource($user);
             return $this->sendResponse($data)->withHeaders(['apiToken'=>$token,'tokenType'=>'bearer']);
        }else{
            return $this->sendError('يوجد مشكلة بالبيانات');
        }
     }
    public function upload_attachment(Request $request){
        $validator = Validator::make(
            [
                'attachment' => 'required|max:10000|mimes:doc,docx,pdf,mp3',
            ],
            $this->validation_messages());
        if ($validator->fails()) {
            return $this->sendError($validator->errors()->first());
        }
        $user = auth()->user();
        $dest='media/files/attachment/';
        $attachment=request('attachment');
        $fileName=null;
        if (is_file($attachment)) {
            $fileName = Str::random(10) . '.' . $attachment->getClientOriginalExtension();
            $attachment->move($dest, $fileName);
        }else{
            return $this->sendError('ﻻ يمكن تحديد نوع الملف المرفق !');
        }
        $attachment_type=$request->input('type','pdf');
        $attachments=$user->more_details['attachments']??[];
        $date=date_create();
        $attachments[]=[
            'id'=>date_timestamp_get($date),
            'file_name'=>$attachment->getClientOriginalName(),
            'attachment'=>$fileName,
            'type'=>$attachment_type,
        ];
        $user->update(
            [
                'more_details'=>[
                    'attachments'=>$attachments,
                ],
            ]
        );
        $data= new UserResource($user);
        return $this->sendResponse($data);
    }
    public function array_remove_object($attachments,$attachment_id){
        return array_filter($attachments, function($attachment) use($attachment_id) {
            return $attachment['id'] != $attachment_id;
        });
    }
    public function remove_attachment(Request $request){
        $validator = Validator::make(
            [
                'id' => 'required',
            ],
            $this->validation_messages());
        if ($validator->fails()) {
            return $this->sendError($validator->errors()->first());
        }
        $user = auth()->user();
        $attachment_id=$request['id'];
        $attachments=[];
        if (array_key_exists('attachments',(array)$user->more_details)){
            $attachments=$user->more_details['attachments'];
        }
        $attachments=array_values($this->array_remove_object($attachments,$attachment_id));
        $user->update(
            [
                'more_details'=>[
                    'attachments'=>$attachments,
                ],
            ]
        );
        $data= new UserResource($user);
        return $this->sendResponse($data);
    }
    public function profile(){
        $user = auth()->user();
        $token = auth()->login($user);
        $data= new UserResource($user);
        return $this->sendResponse($data)->withHeaders(['apiToken'=>$token,'tokenType'=>'bearer']);
    }
    public function show($id){
        $user = User::find($id);
        $data= new UserResource($user);
        return $this->sendResponse($data);
    }
    public function wallet($id,Request $request){
        $user = auth()->user();
        if ($id != auth()->user()->id){
            return $this->sendError('يوجد مشكلة بالبيانات');
        }
        $provider_orders=$user->provider_orders->pluck('id');
        $wallet_orders=Wallet::whereIn('order_id',$provider_orders)->latest()->get();
        $data['wallet']=$user->wallet;
        foreach ($wallet_orders as $wallet_order){
            $arr['order_id']=$wallet_order->order_id;
            $arr['app_ratio']=$wallet_order->app_ratio;
            $arr['order_price']=$wallet_order->order->price;
            $data['orders'][]=$arr;
        }
        return $this->sendResponse($data);
    }
    public function update($id,Request $request){
        $validator = Validator::make($request->all(),$this->validation_rules(2,$id),$this->validation_messages());
        if ($validator->fails()) {
            return $this->sendError($validator->errors()->first());
        }
        $user = auth()->user();
        if ($user->id != $id){
            return $this->sendError('ﻻ يمكنك التعديل بملف شخص اخر',403);
        }
        $user->update($request->all());
        $data= new UserResource($user);
        $token = auth()->login($user);
        return $this->sendResponse($data)->withHeaders(['apiToken'=>$token,'tokenType'=>'bearer']);
    }
}
