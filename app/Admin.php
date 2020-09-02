<?php

namespace App;
use App\Traits\ModelBaseFunctions;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Admin extends Authenticatable
{
    use Notifiable,ModelBaseFunctions;
    private $route='admin';
    private $images_link='media/images/user/';
    protected $guard = 'admin';
    protected $fillable = ['name','email','mobile','image','password','activation_code','activation_status','status','user_type_id'];
    protected $hidden = ['password', 'remember_token'];
}
