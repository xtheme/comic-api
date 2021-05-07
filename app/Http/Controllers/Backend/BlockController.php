<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\BlockRequest;
use App\Models\Block;
use App\Repositories\Contracts\BlockRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class BlockController extends Controller
{

    private $repository;
    private $style;
    private $causer;

    public function __construct(BlockRepositoryInterface $repository)
    {
        $this->repository = $repository;

        $this->style = [
            '1_2' => '动漫一大二小'
        ];

        $this->causer = [
            'App\Models\Video' => 'video' ,
            'App\Models\Book'  => 'comic' ,
        ];
    }

    public function index(Request $request)
    {
        $data = [
            'tags' => getSuggestTags(),
            'style'=> $this->style,
            'causer'=> $this->causer,
            'list' => $this->repository->filter($request)->paginate(),
        ];

        return view('backend.block.index')->with($data);
    }

    public function create()
    {
        $data = [
            'style' => $this->style,
            'tags' => getSuggestTags()
        ];

        return view('backend.block.create')->with($data);
    }

    public function store(BlockRequest $request)
    {
        $post = $request->post();

        $this->repository->create($post);

        return Response::jsonSuccess('添加模块成功！');
    }

    public function edit($id)
    {
        $data = [
            'data'  => $this->repository->find($id),
            'style' => $this->style,
            'causer'=> $this->causer,
            'tags' => getSuggestTags()
        ];

        return view('backend.block.edit')->with($data);
    }

    public function update(BlockRequest $request, $id)
    {
        $this->repository->update($id , $request->post());

        return Response::jsonSuccess('模块修改成功！');
    }

    public function destroy($id)
    {
        $block = Block::findOrFail($id);

        $block->delete();

        return Response::jsonSuccess('删除成功！');
    }

    public function sort(Request $request)
    {
        $post = $request->post();

        Block::where('id', $post['pk'])->update(['sort' => $post['value']]);

        return Response::jsonSuccess('更新资料成功！');
    }

    /**
     * 批量操作
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function batch(Request $request, $action)
    {
        $ids = explode(',', $request->input('ids'));

        switch ($action) {
            case 'enable':
                $text = '启用';
                Block::whereIn('id', $ids)->update(['status' => 1]);
                break;
            case 'disable':
                $text = '隐藏';
                Block::whereIn('id', $ids)->update(['status' => -1]);
                break;
            case 'destroy':
                $text = '删除';
                Block::destroy($ids);
                break;
            default:
                return Response::jsonError(__('response.error.unknown'));
        }

        return Response::jsonSuccess(__('response.success.complete', ['action' => $text]));
    }
}
