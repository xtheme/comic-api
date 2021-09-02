<?php

namespace App\Repositories;

use App\Models\Movie;
use App\Repositories\Contracts\MovieRepositoryInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class MovieRepository extends Repository implements MovieRepositoryInterface
{
    protected $cache_ttl = 60; // 缓存秒数

    /**
     * @return string
     */
    public function model(): string
    {
        return Movie::class;
    }

    public function find($id): ?Model
    {
        return $this->model->findOrFail($id);
    }

    /**
     * 查询DB
     *
     * @param  Request  $request
     *
     * @return Builder
     */
    public function filter(Request $request): Builder
    {
        $status = $request->input('status') ?? '';
        $order = $request->input('order') ?? 'created_at';
        $sort = $request->input('sort') ?? 'desc';
        $limit = $request->input('limit') ?? 10;

        return $this->model::when($status, function (Builder $query, $status) {
            return $query->where('status', $status);
        })->when($sort, function (Builder $query, $sort) use ($order) {
            if ($sort == 'desc') {
                return $query->orderByDesc($order);
            } else {
                return $query->orderBy($order);
            }
        })->take($limit);
    }

    public function format(Model $video): array
    {
        $data =  [
            'id' => $video->id,
            'title' => $video->title,
            'number' => $video->number,
            'producer' => $video->producer,
            'actor' => $video->actor,
            'views' => shortenNumber($video->views),
            'country' => $video->country,
            'subtitle' => $video->subtitle,
            'url' => $video->hls_url,
            'thumb' => $video->thumb,
            'tags' => $video->tags,
            'updated_at' => $video->updated_at->diffForHumans(),
        ];;

        return $data;
    }

    public function collectFormat(Collection $collect): array
    {
        $data = $collect->map(function($video) {
            return $this->format($video);
        })->toArray();

        return $data;
    }
}
