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
}
