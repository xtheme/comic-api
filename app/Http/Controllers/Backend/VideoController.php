<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\VideoRequest;
use App\Models\Video;
use App\Repositories\Contracts\VideoRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class VideoController extends Controller
{
    private $repository;

    const STATUS_OPTIONS = [1 => '上架', -1 => '下架'];
    const RIBBON_OPTIONS = [1 => '限时免费', 2 => '会员抢先'];

    public function __construct(VideoRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function index(Request $request)
    {
        $data = [
            'status_options' => self::STATUS_OPTIONS,
            'ribbon_options' => self::RIBBON_OPTIONS,
            'videos' => $this->repository->filter($request)->paginate(),
            'tags' => $this->repository->getTags(),
            'pageConfigs' => ['hasSearchForm' => true],
        ];

        return view('backend.video.index')->with($data);
    }

    public function create()
    {
        $data = [
            'status_options' => self::STATUS_OPTIONS,
            'ribbon_options' => self::RIBBON_OPTIONS,
            'tags' => $this->repository->getTags(),
            'domains' => $this->repository->getDomains(),
        ];

        return view('backend.video.create')->with($data);
    }

    public function store(VideoRequest $request)
    {
        $this->repository->create($request->post());

        return Response::jsonSuccess(__('response.create.success'));
    }

    public function edit($id)
    {
        $data = [
            'status_options' => self::STATUS_OPTIONS,
            'ribbon_options' => self::RIBBON_OPTIONS,
            'tags' => $this->repository->getTags(),
            'domains' => $this->repository->getDomains(),
            'video' => $this->repository->find($id),
        ];

        return view('backend.video.edit')->with($data);
    }

    public function update(VideoRequest $request, $id)
    {
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
            default:
                return Response::jsonError(__('response.error.unknown'));
        }

        Video::whereIn('id', $ids)->update($data);

        return Response::jsonSuccess(__('response.success.complete', ['action' => $text]));
    }
}
