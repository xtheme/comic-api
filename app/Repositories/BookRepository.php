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
        $id = $request->get('id') ?? null;
        $title = $request->get('title') ?? null;
        $type = $request->get('type') ?? null;
        $tags = $request->get('tags') ?? null;
        $status = $request->get('status') ?? null;

        $order = $request->get('order') ?? 'id';
        $sort = $request->get('sort') ?? 'desc';

        $query = $this->model::with(['tags', 'chapters'])->withCount(['chapters'])->when($id, function (Builder $query, $id) {
            return $query->where('id', $id);
        })->when($title, function (Builder $query, $title) {
            return $query->where('title', 'like', '%' . $title . '%');
        })->when($type, function (Builder $query, $type) {
            return $query->where('type', $type);
        })->when($status, function (Builder $query, $status) {
            return $query->where('status', $status - 1);
        })->when($sort, function (Builder $query, $sort) use ($order) {
            if ($sort == 'desc') {
                return $query->orderByDesc($order);
            } else {
                return $query->orderBy($order);
            }
        });

        if ($tags && is_array($tags)) {
            foreach ($tags as $type => $tag) {
                $query->withAllTags($tag, $type);
            }
        }

        return $query;
    }

    /**
     * @param  array  $input
     *
     * @return Model|null
     */
    public function create(array $input = []): ?Model
    {
        $model = $this->model::create($input);

        if ($input['tags'] && is_array($input['tags'])) {
            foreach ($input['tags'] as $type => $tag) {
                $model->attachTags($tag, $type);
            }
        }

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

        $model->save();

        if ($input['tags'] && is_array($input['tags'])) {
            foreach ($input['tags'] as $type => $tag) {
                $model->syncTags($tag, $type);
            }
        }

        return $model;
    }
}
