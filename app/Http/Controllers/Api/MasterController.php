<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class MasterController extends Controller
{
    protected $model;
    protected $auth_key;
    public function __construct()
    {
        parent::__construct();
    }
    public function sendResponse($result)
    {
        $response = [
            'status' => 200,
            'data' => $result,
        ];
        return response()->json($response, 200);
    }
    public function sendError($error, $code = 400)
    {
        $response = [
            'status' => $code,
            'message' => $error,
        ];
        return response()->json($response, $code);
    }

    public function store(Request $request){
        $validator = Validator::make($request->all(),$this->validation_rules(1),$this->validation_messages());
        if ($validator->fails()) {
            return $this->sendError($validator->errors()->first());
        }
        $data=$request->all();
        $row=$this->model->create($data);
        return $this->sendResponse($row);
    }

}
