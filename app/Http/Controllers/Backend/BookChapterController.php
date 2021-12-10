<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\BookChapter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class BookChapterController extends Controller
{
    /**
     * 章节列表
     */
    public function index($book_id)
    {
        $list = BookChapter::where('book_id', $book_id)->latest('id')->paginate();

        $data = [
            'book_id' => $book_id,
            'list' => $list,
        ];

        return view('backend.book_chapter.index')->with($data);
    }

    /**
     * 图片预览
     */
    public function preview($id)
    {
        $chapter = BookChapter::findOrFail($id);

        $data = [
            'title' => $chapter->title,
            'images' => $chapter->content,
        ];

        return view('backend.book_chapter.preview')->with($data);
    }

    public function create($book_id)
    {
        // 建議章節
        $chapters = BookChapter::where('book_id', $book_id)->count() + 1;

        $data = [
            'book_id' => $book_id,
            'episode' => $chapters,
            'title' => sprintf('第%s话', $chapters),
            'price' => $chapters >= getConfig('comic', 'default_charge_chapter') ? getConfig('comic', 'default_charge_price') : 0,
        ];

        return view('backend.book_chapter.create')->with($data);
    }

    public function store(Request $request)
    {
        $request->merge([
            'title' => $request->input('title') ?? sprintf('第%s话', $request->input('episode')),
        ]);

        BookChapter::create($request->post());

        return Response::jsonSuccess(__('response.create.success'));
    }

    public function edit($id)
    {
        $data = [
            'data' => BookChapter::findOrFail($id),
        ];

        return view('backend.book_chapter.edit')->with($data);
    }

    public function update(Request $request, $id)
    {
        $request->merge([
            'title' => $request->input('title') ?? sprintf('第 %s 章', $request->input('episode')),
        ]);

        $chapter = BookChapter::find($id);
        $chapter->fill($request->post());
        $chapter->save();

        return Response::jsonSuccess(__('response.update.success'));
    }

    /**
     * 批次更新
     */
    public function batch(Request $request, $action)
    {
        $ids = explode(',', $request->input('ids'));

        switch ($action) {
            case 'enable':
                $text = '批量启用';
                $data = ['status' => 1];
                break;
            case 'disable':
                $text = '批量封禁';
                $data = ['status' => 0];
                break;
        }

        BookChapter::whereIn('id', $ids)->update($data);

        // mass delete cache
        foreach($ids as $id) {
            $cache_key = sprintf('chapter:%s', $id);
            Cache::forget($cache_key);
        }

        return Response::jsonSuccess($text . '成功！');
    }

    public function editable(Request $request, $field)
    {
        $data = [
            'pk' => $request->post('pk'),
            'value' => $request->post('value'),
        ];

        $validator = Validator::make($data, [
            'pk' => 'required',
            'value' => 'required',
        ]);

        if ($validator->fails()) {
            return Response::jsonError($validator->errors()->first());
        }

        $chapter = BookChapter::findOrFail($data['pk']);

        $chapter->update([
            $field => $data['value']
        ]);

        return Response::jsonSuccess('数据已更新成功');
    }

}
