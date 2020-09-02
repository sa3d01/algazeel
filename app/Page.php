<?php

namespace App;

use App\Traits\ModelBaseFunctions;
use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    use ModelBaseFunctions;

    private $route='page';
    private $images_link='media/images/page/';

    protected $fillable = ['status','class','title','note','image','more_details'];
    protected $casts = [
        'more_details' => 'json',
        'title' => 'json',
        'note' => 'json',
    ];
}
