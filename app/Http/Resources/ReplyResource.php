<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ReplyResource extends JsonResource
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
          'reply_id'=>$this->id,
          'comment_id'=>$this->comment->id,
          'user'=>$this->user,
          'reply'=>$this->reply,
          'created_before'=>$this->created_at->diffForHumans()
        ];
    }
}