<?php

namespace App\Http\Controllers\Admin;

use App\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends MasterController
{
    public function __construct(Admin $model)
    {
        $this->model = $model;
        parent::__construct();
    }
    public function validation_func($method, $id = null)
    {
        if ($method == 1)
            return ['name' => 'required', 'email' => 'email|max:255|unique:admins', 'image' => 'mimes:png,jpg,jpeg', 'password' => 'required|min:6'];
        return ['name' => 'required', 'email' => 'email|max:255|unique:admins,email,' . $id, 'image' => 'mimes:png,jpg,jpeg'];
    }
    public function validation_msg()
    {
        return array(
            'required' => 'يجب ملئ جميع الحقول',
            'email.unique' => 'هذا البريد مسجل من مقبل',
            'image.mimes' => 'يوجد مشكلة بالصورة',
        );
    }
    public function show($id)
    {
        $row = Admin::find($id);
        return View('dashboard.show.show', [
            'row' => $row,
            'type'=>'admin',
            'action'=>'admin.update',
            'title'=>'الملف الشخصى',
            'edit_fields'=>['الاسم' => 'name', 'البريد الإلكترونى' => 'email'],
            'status'=>true,
            'password'=>true,
            'image'=>true,
        ]);
    }

}
