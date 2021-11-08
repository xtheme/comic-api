<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BookResource extends JsonResource
{
    protected $has_favorite = null;

    public function favorite($value)
    {
        $this->has_favorite = $value;

        return $this;
    }

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     *
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
            'has_favorite' => $this->when(!is_null($this->has_favorite), $this->has_favorite),
            'created_at' => optional($this->created_at)->format('Y-m-d'),
        ];
    }
}
