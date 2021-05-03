<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\VideoSeriesRequest;
use App\Models\VideoSeries;
use App\Repositories\Contracts\VideoSeriesRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;

class VideoSeriesController extends Controller
{
    private $repository;

    const STATUS_OPTIONS = [1 => '上架', -1 => '下架'];
    const CHARGE_OPTIONS = [1 => 'VIP', -1 => '免费'];

    public function __construct(VideoSeriesRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function index(Request $request, $video_id)
    {
        // set $video_id to request
        $request->merge([
            'video_id' => $video_id,
        ]);

        $data = [
            'video_id' => $video_id,
            'status_options' => self::STATUS_OPTIONS,
            'charge_options' => self::CHARGE_OPTIONS,
            'list' => $this->repository->filter($request)->paginate(),
        ];

        return view('backend.video_series.index')->with($data);
    }

    public function create(Request $request, $video_id)
    {
        // set $video_id to request
        $request->merge([
            'video_id' => $video_id,
        ]);

        $data = [
            'video_id' => $video_id,
            'status_options' => self::STATUS_OPTIONS,
            'charge_options' => self::CHARGE_OPTIONS,
            'domains' => $this->repository->getDomains(),
        ];

        return view('backend.video_series.create')->with($data);
    }

    public function store(VideoSeriesRequest $request, $video_id)
    {
        // set $video_id to request
        $request->merge([
            'video_id' => $video_id,
        ]);

        $this->repository->create($request->post());

        return Response::jsonSuccess(__('response.create.success'));
    }

    public function edit(Request $request, $video_id, $id)
    {
        // set $video_id to request
        $request->merge([
            'video_id' => $video_id,
        ]);

        $data = [
            'video_id' => $video_id,
            'status_options' => self::STATUS_OPTIONS,
            'charge_options' => self::CHARGE_OPTIONS,
            'domains' => $this->repository->getDomains(),
            'series' => $this->repository->find($id),
        ];

        return view('backend.video_series.edit')->with($data);
    }

    public function update(VideoSeriesRequest $request, $video_id, $id)
    {
        // set $video_id to request
        $request->merge([
            'video_id' => $video_id,
        ]);

        $this->repository->update($id, $request->post());

        return Response::jsonSuccess(__('response.update.success'));
    }

    public function batch(Request $request, $action)
    {
        $ids = explode(',', $request->input('ids'));

        switch ($action) {
            case 'enable':
                $text = '上架';
                $data = ['status' => 1];
                break;
            case 'disable':
                $text = '下架';
                $data = ['status' => -1];
                break;
            case 'charge':
                $text = '观看资格变更为收费';
                $data = ['vip' => 1];
                break;
            case 'free':
                $text = '观看资格变更为免费';
                $data = ['vip' => -1];
                break;
            default:
                return Response::jsonError(__('response.error.unknown'));
        }

        VideoSeries::whereIn('id', $ids)->update($data);

        return Response::jsonSuccess(__('response.success.complete', ['action' => $text]));
    }

    public function preview(Request $request, $id)
    {
        $series = VideoSeries::findOrFail($id);

        $data = [
            'series' => $series,
        ];

        return view('backend.video_series.preview')->with($data);
    }
}
