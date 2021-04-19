<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\BookRequest;
use App\Models\Book;
use App\Repositories\Contracts\BookRepositoryInterface;
use Conner\Tagging\Model\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class BookController extends Controller
{
    private $repository;

    public function __construct(BookRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function index(Request $request)
    {
        $data = [
            'list' => $this->repository->filter($request)->paginate(),
            'tags' => Tag::inGroup('category')->get(),
            'pageConfigs' => ['hasSearchForm' => true],
        ];

        return view('backend.book.index')->with($data);
    }

    public function create()
    {
        $data = [
            'tags' => Tag::inGroup('category')->where('suggest', 1)->orderByDesc('priority')->get(),
        ];

        return view('backend.book.create')->with($data);
    }

    public function store(BookRequest $request)
    {
        $validated = $request->validated();

        Book::create($validated);

        return Response::jsonSuccess('新增用户成功！');
    }


    public function edit(Book $book)
    {
        $data = [
            'tags' => Tag::inGroup('category')->where('suggest', 1)->orderByDesc('priority')->get(),
        ];

        return view('backend.book.edit')->with($data);
    }

    public function update(Request $request, Book $book)
    {
        //
    }

    /**
     * 批次更新
     */
    public function batch(Request $request, $action)
    {
        $ids = explode(',', $request->post('ids'));

        switch ($action) {
            case 'check_status-0':
            case 'check_status-1':
            case 'check_status-2':
            case 'check_status-3':
            case 'check_status-4':
                $text = '审核状态';
                $check_status = (int) explode('-', $action)[1];
                $data = ['check_status' => $check_status];
                break;
            case 'disable':
                $text = '批量封禁';
                $data = ['chapter_status' => 0];
                break;
            case 'charge':
                $text = '批量收费';
                $data = ['isvip' => 2];
                break;
            case 'free':
                $text = '批量免费';
                $data = ['isvip' => 0];
                break;
            case 'destroy':
                $text = '批量删除';
                $data = ['book_status' => 1];
                break;
            default:
                return Response::jsonError('未知的操作');
        }

        switch ($action) {
            case 'charge':
                $books = Book::whereIn('id', $ids)->get();
                foreach ($books as $book) {
                    $book->chapters()->where('idx', '>', 10)->update($data);
                }
                break;
            case 'free':
                $books = Book::whereIn('id', $ids)->get();
                foreach ($books as $book) {
                    $book->chapters()->update($data);
                }
                break;
            default:
                Book::whereIn('id', $ids)->update($data);
                break;
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
            return Response::jsonError($validator->errors()->first(), 500);
        }

        $this->repository->editable($request->post('pk'), $field, $request->post('value'));

        return Response::jsonSuccess('数据已更新成功');
    }
}
