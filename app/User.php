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

    protected $fillable = ['user_type_id','wallet','name','mobile','email','password','device','activation_code','status','image','location','more_details'];
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
}
