<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\UserNotDefinedException;

class UserController extends MasterController
{
    protected $model;
    protected $auth_key;

    public function __construct(User $model)
    {
        $this->model = $model;
        $this->auth_key = 'mobile';
        parent::__construct();
    }
    public function validation_rules($method, $id = null)
    {
        if ($method == 2) {
            $rules['mobile'] = 'required|regex:/(05)[0-9]{8}/|max:10|unique:users,mobile,' . $id;
            $rules['email'] = 'required|email|max:50|unique:users,email,' . $id;
            $rules['name'] = 'required|max:30';
            $rules['password'] = 'required|min:8';
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
    function send_code($mobile,$activation_code){
        //Mail::to($email)->send(new ConfirmCode($activation_code));
    }

    function user(){
        try {
            $user=auth()->userOrFail();
        }catch (UserNotDefinedException $e){
            return $this->sendError('انتهت جلستك يرجى تسجيل الدخول',401);
        }
        return $user;
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
            return $this->sendResponse(['activation_code'=>$data])->withHeaders(['apiToken'=>$token,'tokenType'=>'bearer']);
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
        $user=$this->user();
        $token = auth()->login($user);
        $user->update(['password'=>$request['password']]);
        $data= new UserResource($user);
        return $this->sendResponse(['activation_code'=>$data])->withHeaders(['apiToken'=>$token,'tokenType'=>'bearer']);
    }
    public function profile(){
        try {
            $user=auth()->userOrFail();
            $token = auth()->login($user);
        }catch (UserNotDefinedException $e){
            return $this->sendError('انتهت جلستك يرجى تسجيل الدخول',401);
        }
        $data= new UserResource($user);
        return $this->sendResponse($data)->withHeaders(['apiToken'=>$token,'tokenType'=>'bearer']);
    }
    public function update($id,Request $request){
        try {
            $user=auth()->userOrFail();
        }catch (UserNotDefinedException $e){
            return $this->sendError('انتهت جلستك يرجى تسجيل الدخول',401);
        }
        if ($user->id != $id){
            return $this->sendError('ﻻ يمكنك التعديل بملف شخص اخر',403);
        }
        $user->update($request->all());
        $data= new UserResource($user);
        return $this->sendResponse($data);
    }

}
