<?php

namespace App\Repositories;

use App\Models\BookCategory;
use App\Repositories\Contracts\TagRepositoryInterface;
use Conner\Tagging\Model\Tag;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

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

        return $this->model::when($keyword, function (Builder $query, $keyword) {
            return $query->where('name', 'like', '%' . $keyword . '%')->orWhere('slug', 'like', '%' . $keyword . '%')->orWhere('description', 'like', '%' . $keyword . '%');
        })->orderByDesc('priority');
    }
}
