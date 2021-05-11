<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Repositories\Contracts\VideoRepositoryInterface;
use App\Repositories\Contracts\VideoSeriesRepositoryInterface;
use Illuminate\Http\Request;

class StatisticsController extends Controller
{

    private $video_repository, $series_repository;

    const STATUS_OPTIONS = [
        1  => '上架',
        -1 => '下架',
    ];

    const ORDER_BY_OPTIONS = [
        'created_at'          => '上架时间',
        'history_visit_count' => '点击量',
        'history_click_count' => '播放总量',
    ];

    public function __construct(VideoRepositoryInterface $video_repository, VideoSeriesRepositoryInterface $series_repository)
    {
        $this->video_repository = $video_repository;
        $this->series_repository = $series_repository;
    }

    public function index(Request $request)
    {
        $data = [
            'status_options'   => self::STATUS_OPTIONS,
            'order_by_options' => self::ORDER_BY_OPTIONS,
            'data'             => $this->video_repository->filter($request)->paginate(),
            'tags'             => getSuggestTags(),
            'pageConfigs'      => ['hasSearchForm' => true],
        ];

        return view('backend.statistics.index')->with($data);
    }

    public function series(Request $request, $video_id)
    {
        // set $video_id to request
        $request->merge([
            'video_id' => $video_id,
        ]);

        $data = [
            'series' => $this->series_repository->filter($request)->get(),
        ];

        return view('backend.statistics.series')->with($data);
    }
}
