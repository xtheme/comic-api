<?php

namespace App\Repositories;

use App\Models\Book;
use App\Repositories\Contracts\BookRepositoryInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class BookRepository extends Repository implements BookRepositoryInterface
{
    protected $cache_ttl = 60; // 缓存秒数

    /**
     * @return string
     */
    public function model(): string
    {
        return Book::class;
    }

    /**
     * @param  Request  $request
     *
     * @return Builder
     */
    public function filter(Request $request): Builder
    {
        $id = $request->get('id') ?? '';
        $title = $request->get('title') ?? '';
        $tag = $request->get('tag') ?? '';
        $review = $request->get('review') ?? '';
        $status = $request->get('status') ?? '';
        $charge = $request->get('charge') ?? '';

        $order = $request->get('order') ?? 'id';
        $sort = $request->get('sort') ?? 'desc';

        return $this->model::with(['latest_chapter'])->withCount(['chapters', 'visit_histories', 'favorite_histories'])->when($id, function (Builder $query, $id) {
            return $query->where('id', $id);
        })->when($title, function (Builder $query, $title) {
            return $query->where('title', 'like', '%' . $title . '%');
        })->when($tag, function (Builder $query, $tag) {
            /** @noinspection PhpUndefinedMethodInspection */
            return $query->withAllTags($tag);
        })->when($review, function (Builder $query, $review) {
            return $query->where('review', $review);
        })->when($status, function (Builder $query, $status) {
            return $query->where('status', $status);
        })->when($charge, function (Builder $query, $charge) {
            // $charge = $charge - 1;
            if (!$charge) {
                return $query->having('charge_chapters_count', 0);
            } else {
                return $query->having('charge_chapters_count', '>=', 1);
            }
        })->when($sort, function (Builder $query, $sort) use ($order) {
            if ($sort == 'desc') {
                return $query->orderByDesc($order);
            } else {
                return $query->orderBy($order);
            }
        });
    }

    /**
     * @param  array  $input
     *
     * @return Model|null
     */
    public function create(array $input = []): ?Model
    {
        $model = $this->model::create($input);

        $model->attachTags($input['tag']);

        return $model;
    }

    /**
     * @param $id
     * @param  array  $input
     *
     * @return bool
     */
    public function update($id, array $input = []): bool
    {
        $model = $this->model::findOrFail($id);

        $model->fill($input);

        $model->syncTags($input['tag']);

        return $model->save();
    }
}
