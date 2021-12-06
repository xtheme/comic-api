<?php

namespace App\Http\Controllers\Backend;

use App\Enums\Options;
use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\TopicRequest;
use App\Models\Topic;
use App\Models\Filter;
use App\Repositories\Contracts\TopicRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Response;

class TopicController extends Controller
{
    private $repository;

    public function __construct(TopicRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function index(Request $request)
    {
        $data = [
            'list' => $this->repository->filter($request)->paginate(),
        ];

        return view('backend.topic.index')->with($data);
    }

    public function create()
    {
        $data = [
            'filters' => Filter::get(),
            'causer_options' => Options::CAUSER_OPTIONS,
            'ribbon_options' => Options::RIBBON_OPTIONS,
        ];

        return view('backend.topic.create')->with($data);
    }

    public function store(TopicRequest $request)
    {
        $input = $request->validated();

        $this->repository->create($input);

        return Response::jsonSuccess(__('response.create.success'));
    }

    public function edit($id)
    {
        $data = [
            'data' => $this->repository->find($id),
            'filters' => Filter::get(),
            'causer_options' => Options::CAUSER_OPTIONS,
            'ribbon_options' => Options::RIBBON_OPTIONS,
        ];

        return view('backend.topic.edit')->with($data);
    }

    public function update(TopicRequest $request, $id)
    {
        $input = $request->validated();

        $this->repository->update($id, $input);

        $cache_key = sprintf('topic:%s', $input['type']);
        Cache::delete($cache_key);

        return Response::jsonSuccess(__('response.update.success'));
    }

    public function destroy($id)
    {
        $block = Topic::findOrFail($id);

        $block->delete();

        return Response::jsonSuccess(__('response.destroy.success'));
    }

    public function sort(Request $request)
    {
        $post = $request->post();

        Topic::where('id', $post['pk'])->update(['sort' => $post['value']]);

        return Response::jsonSuccess('更新资料成功！');
    }

    /**
     * 批量操作
     */
    public function batch(Request $request, $action)
    {
        $ids = explode(',', $request->input('ids'));

        switch ($action) {
            case 'enable':
                $text = '启用';
                Topic::whereIn('id', $ids)->update(['status' => 1]);
                break;
            case 'disable':
                $text = '隐藏';
                Topic::whereIn('id', $ids)->update(['status' => 0]);
                break;
            case 'destroy':
                $text = '删除';
                Topic::destroy($ids);
                break;
            default:
                return Response::jsonError(__('response.error.unknown'));
        }

        return Response::jsonSuccess(__('response.success.complete', ['action' => $text]));
    }
}
