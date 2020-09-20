<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserCollection;
use App\Http\Resources\UserResource;
use App\User;
use App\userType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Tymon\JWTAuth\Exceptions\UserNotDefinedException;

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
            $rules['mobile'] = 'required|regex:/(05)[0-9]{8}/|max:10|unique:users,mobile,' . $id;
            $rules['email'] = 'required|email|max:50|unique:users,email,' . $id;
            $rules['name'] = 'required|max:30';
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
        $name=$request['name'];
        $users=User::where('name','like','%'.$name.'%')->get();
        if ($request['user_type_id'] && $request['user_type_id']!='')
            $users=User::where('name','like','%'.$name.'%')->where('user_type_id',$request['user_type_id'])->get();
        $data= new UserCollection($users);
        return $this->sendResponse($data);
    }
    public function users_list($user_type_id,Request $request){
        $users=User::where('user_type_id',$user_type_id)->get();
        $data= new UserCollection($users);
        return $this->sendResponse($data);
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
            $data= new UserResource($user);
            return $this->sendResponse($data)->withHeaders(['apiToken'=>$token,'tokenType'=>'bearer']);
        }else{
            return $this->sendError('يوجد مشكلة بالبيانات');
        }
    }
    public function logout(Request $request){
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
            $request->only('password'),
            [
                'password' => 'required|min:8',
            ],
            $this->validation_messages());
        if ($validator->fails()) {
            return $this->sendError($validator->errors()->first());
        }
        $user = auth()->user();
        $token = auth()->login($user);
        $user->update(['password'=>$request['password']]);
        $data= new UserResource($user);
        return $this->sendResponse($data)->withHeaders(['apiToken'=>$token,'tokenType'=>'bearer']);
    }
    public function upload_attachment(Request $request){
        $validator = Validator::make(
            [
                'attachment' => 'required|max:10000|mimes:doc,docx,pdf',
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
