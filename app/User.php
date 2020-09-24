<?php

namespace App;

use App\Traits\ModelBaseFunctions;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Mockery\Exception;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use Notifiable,ModelBaseFunctions;

    private $route='user';
    private $images_link='media/images/user/';

    protected $fillable = ['user_type_id','wallet','name','mobile','email','password','device','activation_code','status','image','note','location','more_details'];
    protected $hidden = ['password', 'remember_token'];
    protected $casts = [
        'email_verified_at' => 'datetime',
        'device' => 'json',
        'location' => 'json',
        'more_details' => 'json',
    ];
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }
    public function getJWTCustomClaims()
    {
        return [];
    }

    //relations

    public function User_type(){
        return $this->belongsTo(userType::class);
    }
    public function get_attachments(){
        $collection=[];
        if (array_key_exists('attachments',(array)$this->more_details)){
            foreach ($this->more_details['attachments'] as $attachment){
                $obj['id']=$attachment['id'];
                $obj['type']=$attachment['type'];
                $obj['file_name']=$attachment['file_name'];
                $obj['attachment']=asset('media/files/attachment/').'/'.$attachment['attachment'];
                $collection[]=$obj;
            }
        }
        return $collection;
    }
    public function rating(){
        return 0;
    }
    public function walletDecrement()
    {
        $action = route('admin.user.wallet_decrement', ['id' => $this->attributes['id']]);
        return "<a style='color: #0a0b0b' id='wallet_decrement' class='btn btn-warning btn-sm' data-href='$action' href='$action'><i class='os-icon os-icon-check-circle'></i><span>تسديد</span></a>";
    }

    public function nameForSelect(){
        return $this->name ;
    }
    public function orders(){
        return $this->hasMany(Order::class);
    }
    public function provider_orders(){
        return $this->hasMany(Order::class,'provider_id','id');
    }
}
