<?php

namespace App\Http\Controllers\Admin;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends MasterController
{
    public function __construct(User $model)
    {
        $this->model = $model;
        $this->route = 'user';
        parent::__construct();
    }
    public function validation_func($method, $id = null)
    {
        if ($method == 1)
            return ['name' => 'required', 'mobile' => 'required|max:13|unique:users', 'email' => 'email|max:255|unique:users', 'image' => 'mimes:png,jpg,jpeg', 'password' => 'required|min:6'];
        return ['name' => 'required', 'mobile' => 'required|max:13|unique:users,mobile,' . $id, 'email' => 'email|max:255|unique:users,email,' . $id, 'image' => 'mimes:png,jpg,jpeg'];
    }
    public function validation_msg()
    {
        return array(
            'required' => 'يجب ملئ جميع الحقول',
            'email.unique' => 'هذا البريد مسجل من مقبل',
            'mobile.unique' => 'هذا الجوال مسجل من مقبل',
            'image.mimes' => 'يوجد مشكلة بالصورة',
        );
    }
    public function index()
    {
        $rows = $this->model->all();
        return View('dashboard.index.index', [
            'rows' => $rows,
            'type'=>'user',
            'title'=>'قائمة العمﻻء',
            'index_fields'=>['الاسم' => 'name', 'البريد الإلكترونى' => 'email','تاريخ الانضمام'=>'created_at'],
            'status'=>true,
            'image'=>true,
        ]);
    }
    public function create()
    {
        return View('dashboard.create.create', [
            'type'=>'user',
            'action'=>'admin.user.store',
            'title'=>'أضافة عميل',
            'create_fields'=>['الاسم' => 'name', 'البريد الإلكترونى' => 'email', 'الجوال' => 'mobile'],
            'status'=>true,
            'password'=>true,
            'image'=>true,
        ]);
    }
    public function store(Request $request)
    {
        $this->validate($request, $this->validation_func(1),$this->validation_msg());
        $this->model->create($request->all());
        return redirect()->route('admin.user.index')->with('created');
    }
    public function show($id)
    {
        $row = User::findOrFail($id);
        return View('dashboard.show.show', [
            'row' => $row,
            'type'=>'user',
            'action'=>'admin.user.update',
            'title'=>'الملف الشخصى',
            'edit_fields'=>['الاسم' => 'name', 'البريد الإلكترونى' => 'email', 'الجوال' => 'mobile'],
            'status'=>true,
            'password'=>true,
            'image'=>true,
        ]);
    }
    public function activate($id,Request $request){
        $user=$this->model->find($id);
        $history=$user->more_details['history'];
        if($user->status === 1){
            $history[date('Y-m-d')]['block']=[
                'time'=>date('H:i:s'),
                'admin_id'=>Auth::user()->id,
                'reason'=>$request['block_reason'],
            ];
            $user->update(
                [
                    'status'=>0,
                    'more_details'=>[
                        'history'=>$history,
                    ],
                ]
            );
        }else{
            $history[date('Y-m-d')]['approve']=[
                'time'=>date('H:i:s'),
                'admin_id'=>Auth::user()->id,
            ];
            $user->update(
                [
                    'status'=>1,
                    'more_details'=>[
                        'history'=>$history,
                    ],
                ]
            );
        }
        return redirect()->back()->with('updated');
    }
}
