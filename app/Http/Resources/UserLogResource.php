<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserLogResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        if ($this->type == 'book') {
            $item = new BookResource($this->book);
        } else {
            $item = new VideoResource($this->video);
        }

        return [
            'record_id' => $this->id,
            'recorded_at' => $this->created_at->format('Y-m-d H:i:s'),
            'item' => $item,
        ];
    }
}
