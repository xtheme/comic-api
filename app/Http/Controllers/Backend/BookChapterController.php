<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\BookChapter;
use App\Repositories\Contracts\BookChapterRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class BookChapterController extends Controller
{
    private $repository;

    public function __construct(BookChapterRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * 章节列表
     */
    public function index($book_id)
    {
        $data = [
            'book_id' => $book_id,
            'list' => $this->repository->filter($book_id)->paginate(),
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
            'images' => $chapter->json_image_thumb,
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

        $this->repository->create($request->post());

        return Response::jsonSuccess(__('response.create.success'));
    }

    public function edit($id)
    {
        $data = [
            'data' => $this->repository->find($id),
        ];

        return view('backend.book_chapter.edit')->with($data);
    }

    public function update(Request $request, $id)
    {
        $request->merge([
            'title' => $request->input('title') ?? sprintf('第 %s 章', $request->input('episode')),
        ]);

        $this->repository->update($id, $request->post());

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
            return Response::jsonError($validator->errors()->first(), 500);
        }

        $this->repository->editable($request->post('pk'), $field, $request->post('value'));

        return Response::jsonSuccess('数据已更新成功');
    }

}
