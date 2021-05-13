<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\NoticeRequest;
use App\Models\Notice;
use Illuminate\Support\Facades\Response;

class NoticeController extends Controller
{
    public function index()
    {
        $list = Notice::orderByDesc('id')->paginate();

        return view('backend.notice.index', [
            'list' => $list
        ]);
    }

    public function create()
    {
        return view('backend.notice.create');
    }

    public function store(NoticeRequest $request)
    {
        $notice = new Notice;

        $post = $request->post();

        $post['time'] = time();

        $notice->fill($post)->save();

        return Response::jsonSuccess('添加公告成功！');
    }

    public function edit($id)
    {
        $notice = Notice::findOrFail($id);

        return view('backend.notice.edit', [
            'data' => $notice
        ]);
    }

    public function update(NoticeRequest $request, $id)
    {
        $notice = Notice::findOrFail($id);

        $post = $request->post();

        $notice->fill($post)->save();

        return Response::jsonSuccess('修改公告成功！');
    }

    public function destroy($id)
    {
        $notice = Notice::findOrFail($id);

        $notice->delete();

        return Response::jsonSuccess('删除公告成功！');
    }
}
