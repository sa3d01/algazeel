<?php

namespace App\Http\Controllers\Admin;


use App\Setting;
use Illuminate\Http\Request;

class HomeController extends MasterController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index(){
        return view('dashboard.index');
    }
    public function setting(){
        $row = Setting::first();
        return View('dashboard.settings', [
            'row' => $row,
        ]);
    }
    public function update_setting(Request $request){
        Setting::updateOrCreate(['id'=>1],$request->all());
        return redirect()->back()->with('updated', 'تم التعديل بنجاح');
    }

}
