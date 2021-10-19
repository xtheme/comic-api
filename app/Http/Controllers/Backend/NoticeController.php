<?php

namespace App\Http\Controllers\Backend;

use App\Enums\Options;
use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\NoticeRequest;
use App\Models\Notice;
use Illuminate\Support\Facades\Response;

class NoticeController extends Controller
{
    public function index()
    {
        $data = [
            'status_options' => Options::STATUS_OPTIONS,
            'list' => Notice::latest('sort')->paginate(),
        ];

        return view('backend.notice.index')->with($data);
    }

    public function create()
    {
        $data = [
            'status_options' => Options::STATUS_OPTIONS,
        ];

        return view('backend.notice.create')->with($data);
    }

    public function store(NoticeRequest $request)
    {
        $post = $request->post();

        $notice = new Notice;

        $notice->fill($post)->save();

        return Response::jsonSuccess(__('response.create.success'));
    }

    public function edit($id)
    {
        $data = [
            'status_options' => Options::STATUS_OPTIONS,
            'notice' => Notice::findOrFail($id),
        ];

        return view('backend.notice.edit')->with($data);
    }

    public function update(NoticeRequest $request, $id)
    {
        $post = $request->post();

        $notice = Notice::findOrFail($id);

        $notice->fill($post)->save();

        return Response::jsonSuccess(__('response.update.success'));
    }

    public function destroy($id)
    {
        $notice = Notice::findOrFail($id);

        $notice->delete();

        return Response::jsonSuccess(__('response.destroy.success'));
    }
}
