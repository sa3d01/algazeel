<?php

namespace App\Http\Controllers\Api;

use App\Contact;
use App\DropDown;
use App\Http\Controllers\Controller;
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

}
