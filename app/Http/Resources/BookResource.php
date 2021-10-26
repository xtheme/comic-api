<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BookResource extends JsonResource
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
            'id' => $this->id,
            'title' => $this->title,
            'author' => $this->author,
            'cover' => $this->horizontal_cover,
            'tagged_tags' => $this->tagged_tags,
            'view_counts' => shortenNumber($this->view_counts),
            'created_at' => optional($this->created_at)->format('Y-m-d'),
        ];
    }
}
