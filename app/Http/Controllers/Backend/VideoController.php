<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Repositories\Contracts\VideoRepositoryInterface;
use Illuminate\Http\Request;

class VideoController extends Controller
{
    private $repository;

    public function __construct(VideoRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function index(Request $request)
    {
        $data = [
            'list' => $this->repository->filter($request)->paginate(),
            'pageConfigs' => ['hasSearchForm' => true],
        ];

        return view('backend.video.index')->with($data);
    }
}
