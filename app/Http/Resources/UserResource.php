<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
          'user_id'=>$this->id,
          'name'=>$this->name,
          'email'=>$this->email,
          'nick_name'=>$this->nick_name,
          'phone'=>$this->phone,
          'date_of_birth'=>$this->date_of_birth,
          'image_url'=>config('constants.WEBSITE_URL') . '/public/img/users/profile/' . $this->image,
          'bio'=>$this->bio
        ];
    }
}