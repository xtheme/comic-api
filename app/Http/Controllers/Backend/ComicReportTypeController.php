<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\ComicreporttypeRequest;
use App\Models\BookReportType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;

class ComicReportTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $list = BookReportType::with('admin')->orderByDesc('id')->paginate();

        return view('backend.bookreporttype.index', [
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
        return view('backend.bookreporttype.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ComicreporttypeRequest $request)
    {

        $post = $request->post();

        $bookreporttype = new BookReportType;

        $post['operator_id'] = Auth::user()->id;

        $bookreporttype->fill($post)->save();

        return Response::jsonSuccess('新增资料成功！');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data = BookReportType::findOrFail($id);

        return view('backend.bookreporttype.edit', [
            'data' => $data
        ]);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ComicreporttypeRequest $request, $id)
    {

        $post = $request->post();

        $bookreportType = BookReportType::findOrFail($id);

        $post['operator_id'] = Auth::user()->id;

        $bookreportType->fill($post)->save();

        return Response::jsonSuccess('更新资料成功！');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $bookreportType = BookReportType::findOrFail($id);

        $bookreportType->delete();

        return Response::jsonSuccess('删除资料成功！');
    }

    /**
     * 修改順序
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function sort(Request $request)
    {
        $post = $request->post();

        BookReportType::where('id' , $post['pk'])->update(['sort' => $post['value']]);

        return Response::jsonSuccess('更新资料成功！');
    }

}
