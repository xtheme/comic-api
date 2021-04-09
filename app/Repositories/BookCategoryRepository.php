<?php

namespace App\Repositories;

use App\Models\BookCategory;
use App\Repositories\Contracts\BookCategoryRepositoryInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class BookCategoryRepository extends Repository implements BookCategoryRepositoryInterface
{
    protected $cache_ttl = 60; // 缓存秒数

    /**
     * @return string
     */
    public function model(): string
    {
        return BookCategory::class;
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
            return $query->where('name', 'like', '%' . $keyword . '%')->orWhere('keywords', 'like', '%' . $keyword . '%')->orWhere('desc', 'like', '%' . $keyword . '%');
        })->orderByDesc('orders');
    }

    public function editable($id, $field, $value)
    {
        $this->model::where('id', $id)->update([$field => $value]);
    }
}
