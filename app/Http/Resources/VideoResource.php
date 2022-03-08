<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VideoResource extends JsonResource
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
     * @param  Request  $request
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'author' => $this->author,
            'cover' => $this->cover,
            'tagged_tags' => $this->tagged_tags,
            'view_counts' => shortenNumber($this->view_counts),
            'has_favorite' => $this->when(!is_null($this->has_favorite), $this->has_favorite),
            'created_at' => optional($this->created_at)->format('Y-m-d'),
        ];
    }
}
