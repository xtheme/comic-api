<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BookChapterResource extends JsonResource
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
            'book_id' => $this->book_id,
            'chapter_id' => $this->id,
            'episode' => $this->episode,
            'title' => $this->title,
            'price' => $this->price,
            'purchased' => $this->purchased,
            'view_counts' => shortenNumber($this->view_counts),
            'created_at' => $this->created_at->format('Y-m-d'),
        ];
    }
}
