<?php

namespace App\Repositories;

use App\Models\Topic;
use App\Repositories\Contracts\TopicRepositoryInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class TopicRepository extends Repository implements TopicRepositoryInterface
{
    /**
     * @return string
     */
    public function model(): string
    {
        return Topic::class;
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
        $title = $request->input('title') ?? '';
        $type = $request->input('type') ?? '';
        $status = $request->input('status') ?? '';

        $order = $request->input('order') ?? 'sort';
        $sort = $request->input('sort') ?? 'desc';

        return $this->model::with(['filter'])->when($title, function (Builder $query, $title) {
            return $query->where('title', 'like' , '%' . $title . '%' );
        })->when($type, function (Builder $query, $type) {
            return $query->where('type', $type);
        })->when($status, function (Builder $query, $status) {
            return $query->where('status', $status);
        })->when($sort, function (Builder $query, $sort) use ($order) {
            if ($sort == 'desc') {
                return $query->orderByDesc($order);
            } else {
                return $query->orderBy($order);
            }
        });
    }
}
