<?php

namespace App;

use App\Traits\ModelBaseFunctions;
use Illuminate\Database\Eloquent\Model;

class Wallet extends Model
{
    use ModelBaseFunctions;

    protected $fillable = ['order_id','app_ratio','provider_ratio'];

    public function order(){
        return $this->belongsTo(Order::class);
    }
}
