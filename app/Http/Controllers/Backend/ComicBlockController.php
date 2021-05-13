<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\RecomclassRequest;
use App\Models\ComicBlock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class ComicBlockController extends Controller
{
    private $style;

    public function __construct()
    {
        $this->style = [
            1 => '一排显示1个',
            2 => '一排显示2个',
            3 => '一排显示3个',
            4 => '广告图'
        ];
    }

    public function index()
    {
        $list = ComicBlock::orderBy('listorder')->paginate();

        return view('backend.comic_block.index', [
            'list' => $list
        ]);
    }

    public function create()
    {
        return view('backend.comic_block.create', [
            'style' => $this->style
        ]);
    }

    public function store(RecomclassRequest $request)
    {
        $post = $request->post();

        $block = new ComicBlock;

        $post['addtime'] = time();

        $block->fill($post)->save();

        return Response::jsonSuccess('添加推荐分类成功！');
    }

    public function edit($id)
    {
        $data = ComicBlock::findOrFail($id);

        return view('backend.comic_block.edit', [
            'data' => $data,
            'style' => $this->style
        ]);
    }

    public function update(RecomclassRequest $request, $id)
    {
        $block = ComicBlock::findOrFail($id);

        $block->fill($request->post())->save();

        return Response::jsonSuccess('推荐分类修改成功！');
    }

    public function destroy($id)
    {
        $block = ComicBlock::findOrFail($id);

        $block->delete();

        return Response::jsonSuccess('删除成功！');
    }

    public function sort(Request $request)
    {
        $post = $request->post();

        ComicBlock::where('id', $post['pk'])->update(['listorder' => $post['value']]);

        return Response::jsonSuccess('更新资料成功！');
    }

    public function batchDestroy(Request $request, $ids)
    {
        $data = explode(',', $ids);
        ComicBlock::destroy($data);
        return Response::jsonSuccess('删除成功！');
    }
}
