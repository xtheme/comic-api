<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Repositories\Contracts\VideoSeriesRepositoryInterface;
use Illuminate\Http\Request;

class VideoSeriesController extends Controller
{
    private $repository;

    public function __construct(VideoSeriesRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function index(Request $request)
    {
        $data = [
            'list' => $this->repository->filter($request)->paginate(),
            'pageConfigs' => ['hasSearchForm' => true],
        ];

        return view('backend.video_series.index')->with($data);
    }
}
