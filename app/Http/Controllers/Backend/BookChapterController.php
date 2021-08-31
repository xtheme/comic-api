<?php

namespace App\Http\Controllers\Backend;

use App\Enums\Options;
use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\BookRequest;
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

        $json_images = $chapter->json_images;

        $domain = ($chapter->operating == 1) ? getOldConfig('web_config', 'api_url') : getOldConfig('web_config', 'img_sync_url');

        $images = [];

        foreach ($json_images as $image) {
            $images[] = $domain . $image['url'];
        }

        $data = [
            'title' => $chapter->title,
            'images' => $images,
        ];

        return view('backend.book_chapter.preview')->with($data);
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
                $data = ['chapter_status' => 1];
                break;
            case 'disable':
                $text = '批量封禁';
                $data = ['chapter_status' => 0];
                break;
            case 'charge':
                $text = '批量收费';
                $data = ['isvip' => 1];
                break;
            default:
            case 'free':
                $text = '批量免费';
                $data = ['isvip' => 0];
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

    public function create($book_id)
    {
        $data = [
            'status_options' => Options::STATUS_OPTIONS,
            'tags' => getAllTags(),
        ];

        return view('backend.book_chapter.create')->with($data);
    }

    public function store(BookRequest $request)
    {
        $validated = $request->validated();

        $book = $this->repository->create($validated);

        $book->tag($validated['tag']);

        return Response::jsonSuccess(__('response.create.success'));
    }

    public function edit($book_id)
    {
        $data = [
            'book_id' => $book_id,
        ];

        return view('backend.book_chapter.edit')->with($data);
    }

    public function update(BookRequest $request, $id)
    {
        $validated = $request->validated();

        $this->repository->update($id, $validated);

        $book = $this->repository->find($id);

        $book->tag($validated['tag']);

        return Response::jsonSuccess(__('response.update.success'));
    }
}
