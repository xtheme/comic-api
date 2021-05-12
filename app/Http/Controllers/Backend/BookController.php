<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\BookRequest;
use App\Models\Book;
use App\Repositories\Contracts\BookRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Upload;

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
            'tags' => getAllTags(),
            'pageConfigs' => ['hasSearchForm' => true],
        ];

        return view('backend.book.index')->with($data);
    }

    public function create()
    {

        $data = [
            'tags' => getAllTags(),
        ];

        return view('backend.book.create')->with($data);
    }

    public function store(BookRequest $request)
    {
        // $validated = $request->validated();

        // Book::create($validated);

        // dd($validated);
        // return Response::jsonSuccess('OK', Upload::Ok());

        // Book::create($validated);

        $response = Upload::unsync()->to('book', 12345)->store($request->file('book_thumb'));

        if (!$response['success']) {
            return Response::jsonError($response['message']);
        }

        return Response::jsonSuccess($response['message'], $response['path']);
    }


    public function edit($id)
    {
        $data = [
            'book' => Book::findOrFail($id),
            'tags' => getAllTags(),
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

    public function caching(Request $request)
    {
        $books = $this->repository->filter($request)->take(20)->get();

        $domain = getOldConfig('web_config', 'img_sync_url_password_webp');

        $images = $books->reject(function ($book) {
            return $book->chapters_count === 0;
        })->map(function ($book) use ($domain) {
            $chapters = $book->chapters;

            return $chapters->reject(function ($chapter)  {
                return $chapter->json_images === '';
            })->map(function ($chapter) use ($domain) {
                return collect($chapter->json_images)->map(function ($image) use ($domain)  {
                    return $domain . webp($image['url']);
                });
            })->flatten()->toArray();

        })->flatten()->toArray();

        $txt = '';

        foreach ($images as $image) {
            $txt .= $image . "\n";
        }

        return response($txt)->withHeaders([
                'Content-Type'        => 'text/plain',
                'Cache-Control'       => 'no-store, no-cache',
                'Content-Disposition' => 'attachment; filename="CDN预热名单_' . date('Y-m-d') . '.txt',
            ]);
    }
}
