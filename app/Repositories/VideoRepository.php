<?php

namespace App\Repositories;

use App\Models\Video;
use App\Repositories\Contracts\VideoRepositoryInterface;
use Conner\Tagging\Model\Tag;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;

class VideoRepository extends Repository implements VideoRepositoryInterface
{
    protected $cache_ttl = 60; // 缓存秒数

    /**
     * @return string
     */
    public function model(): string
    {
        return Video::class;
    }

    public function find($id): ?Model
    {
        return $this->model->findOrFail($id);
        // return $this->model->with(['series', 'series.cdn', 'series.play_histories'])->findOrFail($id);
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
        $author = $request->input('author') ?? '';
        $ribbon = $request->input('ribbon') ?? '';
        $status = $request->input('status') ?? '';
        $tag = $request->input('tag') ?? '';
        $date_between = $request->input('date_between') ?? '';

        $order = $request->input('order') ?? 'created_at';
        $sort = $request->input('sort') ?? 'desc';

        $page = $request->input('page') ?? 1;
        $perPage = $request->input('perPage') ?? 10;

        // return $this->model::with(['series', 'series.cdn'])->withCount(['series' , 'visit_histories' , 'play_histories'])->when($title, function (Builder $query, $title) {
        // return $this->model::withCount(['series' , 'visit_histories' , 'play_histories'])->when($title, function (Builder $query, $title) {
        return $this->model::when($title, function (Builder $query, $title) {
            return $query->whereLike('title', $title);
        })->when($author, function (Builder $query, $author) {
            return $query->whereLike('author', $author);
        })->when($ribbon, function (Builder $query, $ribbon) {
            return $query->where('ribbon', $ribbon);
        })->when($status, function (Builder $query, $status) {
            return $query->where('status', $status);
        })->when($tag, function (Builder $query, $tag) {
            /** @noinspection PhpUndefinedMethodInspection */
            return $query->withAllTags($tag);
        })->when($date_between, function (Builder $query, $date_between) {
            $date = explode(' - ', $date_between);
            $start_date = $date[0] . ' 00:00:00';
            $end_date = $date[1] . ' 23:59:59';
            return $query->whereBetween('created_at', [
                $start_date,
                $end_date,
            ]);
        })->when($sort, function (Builder $query, $sort) use ($order) {
            if ($sort == 'desc') {
                return $query->orderByDesc($order);
            } else {
                return $query->orderBy($order);
            }
        })->when($page, function (Builder $query, $page) use ($perPage) {
            return $query->forPage($page, $perPage);
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

        $model->syncTagsWithType($input['tag'], 'video');

        return $model->save();
    }

    public function random(int $limit): Collection
    {
        return $this->model::inRandomOrder()->limit($limit)->get();
    }

    public function destroy($id): bool
    {
        $model = $this->model::findOrFail($id);

        $model->series()->delete();

        return $model->delete();
    }
}
