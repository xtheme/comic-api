<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\RecomclassRequest;
use App\Models\Block;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class BlockController extends Controller
{


    private $style;

    public function __construct()
    {
        $this->style = [
            1 => '一排显示1个',
            2 => '一排显示2个',
            3 => '一排显示3个',
            4 => '广告图',
            5 => '动漫一大二小'
        ];
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $list = Block::orderBy('listorder')->paginate();

        return view('backend.block.index', [
            'list' => $list
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('backend.block.create', [
            'style' => $this->style
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(RecomclassRequest $request)
    {

        $post = $request->post();

        $block = new Block;

        $post['addtime'] = time();

        $block->fill($post)->save();

        return Response::jsonSuccess('添加推荐分类成功！');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data = Block::findOrFail($id);


        return view('backend.block.edit', [
            'data' => $data,
            'style' => $this->style
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(RecomclassRequest $request, $id)
    {
        $block = Block::findOrFail($id);

        $block->fill($request->post())->save();

        return Response::jsonSuccess('推荐分类修改成功！');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $block = Block::findOrFail($id);

        $block->delete();

        return Response::jsonSuccess('删除成功！');
    }

    /**
     * 修改順序
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function sort(Request $request)
    {
        $post = $request->post();

        Block::where('id', $post['pk'])->update(['listorder' => $post['value']]);

        return Response::jsonSuccess('更新资料成功！');
    }


    /**
     * 批量刪除
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function batchDestroy(Request $request, $ids)
    {
        $data = explode(',', $ids);
        Block::destroy($data);
        return Response::jsonSuccess('删除成功！');
    }

}
