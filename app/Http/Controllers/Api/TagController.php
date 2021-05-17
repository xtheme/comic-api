<?php

namespace App\Http\Controllers\Api;

use App\Repositories\Contracts\TagRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class TagController extends BaseController
{
    protected $repository;

    public function __construct(TagRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function categories(Request $request, $causer)
    {
        // $request->merge([
        //     'causer' => $causer,
        //     'status' => 1,
        // ]);
        //
        // $topics = $this->repository->filter($request)->get();
        //
        // $data = $topics->map(function ($topic) {
        //     return [
        //         'topic'     => $topic->id,
        //         'title'     => $topic->title,
        //         'tags'      => $topic->properties['tag'] ?? [],
        //         'spotlight' => $topic->spotlight,
        //         'per_line'  => $topic->row,
        //         'list'      => $topic->query_result,
        //         // 'more'      => route('api.topic.more', $topic->id),
        //     ];
        // });

        return Response::jsonSuccess(__('api.success'), $data);
    }
}
