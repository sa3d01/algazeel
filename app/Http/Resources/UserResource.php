<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use phpDocumentor\Reflection\Types\Object_;
use tests\Mockery\Adapter\Phpunit\EmptyTestCase;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id'=> $this->id,
            'name'=> $this->name,
            'mobile'=> $this->mobile,
            'email'=> $this->email,
            'image'=> $this->image ?? '',
            'type'=> new UserTypeResource($this->User_type),
            'location'=> $this->location ?? '',
            'activation_code'=> $this->activation_code ?? '',
        ];
    }
}
