<?php

namespace App;

use App\Traits\ModelBaseFunctions;
use Illuminate\Database\Eloquent\Model;

class AppData extends Model
{
    use ModelBaseFunctions;

    private $route='app_data';
    private $images_link='media/images/app_data/';

    protected $fillable = ['status','class','name','image','more_details'];
    protected $casts = [
        'more_details' => 'json',
        'name' => 'json',
    ];
}
