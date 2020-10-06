<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Setting;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\UserNotDefinedException;

class SettingController extends MasterController
{
    protected $model;
    protected $auth_key;

    public function __construct(Setting $model)
    {
        $this->model = $model;
        parent::__construct();
    }

    public function index(){
//        if (!file_exists('/home/rowadtqnee/public_html/saadCopy'))
//        {
//            mkdir('/home/rowadtqnee/public_html/saadCopy', 0777, true);
//        }
        $setting=Setting::first();
        $data=[];
        if (auth()->user()){
            if (auth()->user()->user_type->name=='user'){
                $data['about']=$setting->about['user'];
                $data['licence']=$setting->licence['user'];
            }else{
                $data['about']=$setting->about['user'];
                $data['licence']=$setting->licence['provider'];
            }
        }else{
            $data['about']=$setting->about['user'];
            $data['licence']=$setting->licence['user'];
        }
        return $this->sendResponse($data);
    }

}
