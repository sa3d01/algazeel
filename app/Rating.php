<?php

namespace App;

use App\Traits\ModelBaseFunctions;
use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
    use ModelBaseFunctions;

    protected $fillable = ['order_id','rate'];

    public function order(){
        return $this->belongsTo(Order::class);
    }
}
