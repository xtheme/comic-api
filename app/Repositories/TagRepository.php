<?php

namespace App\Repositories;

use App\Models\Tag;
use App\Repositories\Contracts\TagRepositoryInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class TagRepository extends Repository implements TagRepositoryInterface
{
    protected $cache_ttl = 60; // 缓存秒数

    /**
     * @return string
     */
    public function model(): string
    {
        return Tag::class;
    }

    /**
     * @param  Request  $request
     *
     * @return Builder
     */
    public function filter(Request $request): Builder
    {
        $keyword = $request->get('keyword') ?? '';
        $suggest = $request->get('suggest') ?? '';
        $tagged_book = $request->get('tagged_book') ?? '';
        $tagged_video = $request->get('tagged_video') ?? '';

        return $this->model::with(['tagged_book', 'tagged_video'])->withCount(['tagged_book', 'tagged_video'])->when($keyword, function (Builder $query, $keyword) {
            return $query->where('name', 'like', '%' . $keyword . '%')
                ->orWhere('description', 'like', '%' . $keyword . '%');
        })->when($suggest, function (Builder $query, $suggest) {
            return $query->where('suggest', $suggest);
        })->when($tagged_book, function (Builder $query) {
            return $query->having('tagged_book_count', '>', 0);
        })->when($tagged_video, function (Builder $query) {
            return $query->having('tagged_video_count', '>', 0);
        })->orderByDesc('priority');
    }

    public function suggest(): ?Collection
    {
        return $this->model::where('suggest', 1)->orderByDesc('priority')->get();
    }
}
