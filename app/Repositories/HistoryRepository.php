<?php

namespace App\Repositories;

use App\Models\History;
use App\Repositories\Contracts\HistoryRepositoryInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class HistoryRepository extends Repository implements HistoryRepositoryInterface
{
    protected $cache_ttl = 60; // 缓存秒数

    /**
     * @return string
     */
    public function model(): string
    {
        return History::class;
    }

    /**
     * @param array $input
     */
    public function log(array $input = [])
    {
        $where = [
            'major_id' => $input['major_id'],
            'minor_id' => $input['minor_id'],
            'user_vip' => $input['user_vip'],
            'user_id'  => $input['user_id'],
            'type'     => $input['type'],
            'class'    => $input['class'],
        ];

        $exists = $this->model::where($where)->exists();

        if (!$exists) {
            return $this->model::create($input);
        }

        return $this->model::where($where)->update(['created_at' => Carbon::now()]);
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
        $class = $request->input('class') ?? '';
        // $date_between = $request->input('date_between') ?? '';
        $tag = $request->input('tag') ?? '';

        $order = $request->input('order') ?? 'created_at';
        $sort = $request->input('sort') ?? 'desc';

        return $this->model::when($title, function (Builder $query, $title) {
            return $query->where('title', 'like' , '%' . $title . '%' );
        })->when($class, function (Builder $query, $class) {
            return $query->where('class', $class);
        })->when($tag, function (Builder $query, $tag) {
            $query->whereHas('video', function (Builder $query) use ($tag) {
                /** @noinspection PhpUndefinedMethodInspection */
                return $query->withAllTags($tag);
            });
        })->when($sort, function (Builder $query, $sort) use ($order) {
            if ($sort == 'desc') {
                return $query->orderByDesc($order);
            } else {
                return $query->orderBy($order);
            }
        });
    }
}
