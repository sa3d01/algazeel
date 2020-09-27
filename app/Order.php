<?php

namespace App;

use App\Traits\ModelBaseFunctions;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use ModelBaseFunctions;

    private $route='order';
    private $images_link='media/images/drop_down/';

    protected $fillable = ['status','type_id','cancel_reason','note','provider_id','user_id','price','paid','more_details'];

    protected $casts = [
        'more_details' => 'json',
    ];

    public function type(){
        return $this->belongsTo(DropDown::class,'type_id','id');
    }
    public function notifications(){
        return $this->hasMany(Notification::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function provider()
    {
        return $this->belongsTo(User::class,'provider_id','id');
    }
    public function ratings(){
        return $this->hasMany(Rating::class,'order_id','id');
    }
    public function getRateIcon()
    {
        if (count($this->ratings)){
            $rate=(double)$this->ratings->avg('rate');
            return "<p><i class='fa fa-star' aria-hidden='hidden'></i>$rate</p>";
        }else{
            return "<p>لم يتم التقييم بعد</p>";
        }
    }
}
