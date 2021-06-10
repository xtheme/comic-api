<?php

namespace App\Repositories;

use App\Models\VideoDomain;
use App\Models\VideoSeries;
use App\Repositories\Contracts\VideoSeriesRepositoryInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class VideoSeriesRepository extends Repository implements VideoSeriesRepositoryInterface
{
    /**
     * @return string
     */
    public function model(): string
    {
        return VideoSeries::class;
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
        $video_id = $request->get('video_id') ?? '';
        $id = $request->get('id') ?? '';
        $charge= $request->get('charge') ?? '';
        $status = $request->get('status') ?? '';
        $title = $request->get('title') ?? '';
        $created_between = $request->get('created_between') ?? '';

        $order = $request->get('order') ?? 'created_at';
        $sort = $request->get('sort') ?? 'desc';

        return $this->model::with(['cdn'])->withCount(['member_histories' => function(Builder $query) use ($video_id) {
            $query->where('video_id', $video_id);
        } , 'guest_histories' => function(Builder $query) use ($video_id) {
            $query->where('video_id', $video_id);
        }])->when($video_id, function (Builder $query, $video_id) {
            return $query->where('video_id', $video_id);
        })->when($id, function (Builder $query, $id) {
            return $query->where('id', $id);
        })->when($charge, function (Builder $query, $charge) {
            return $query->where('charge', $charge);
        })->when($status, function (Builder $query, $status) {
            return $query->where('status', $status);
        })->when($title, function (Builder $query, $title) {
            return $query->whereLike(['title', 'link'], $title);
        })->when($created_between, function (Builder $query, $created_between) {
            $date = explode(' - ', $created_between);
            $start_date = $date[0];
            $end_date = $date[1];

            return $query->whereBetween('created_at', [$start_date, $end_date]);
        })->when($sort, function (Builder $query, $sort) use ($order) {
            if ($sort == 'desc') {
                return $query->orderByDesc($order);
            } else {
                return $query->orderBy($order);
            }
        });
    }

    public function getDomains(): ?Collection
    {
        return VideoDomain::where('status', 1)->orderByDesc('sort')->get();
    }
}
