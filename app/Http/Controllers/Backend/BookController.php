<?php

namespace App\Http\Controllers\Backend;

use App\Enums\Options;
use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\BookRequest;
use App\Models\Book;
use App\Repositories\Contracts\BookRepositoryInterface;
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
            'status_options' => Options::STATUS_OPTIONS,
            'review_options' => Options::REVIEW_OPTIONS,
            'charge_options' => Options::CHARGE_OPTIONS,
            'list' => $this->repository->filter($request)->paginate(),
            'tags' => getAllTags(),
            'pageConfigs' => ['hasSearchForm' => true],
        ];

        return view('backend.book.index')->with($data);
    }

    public function create()
    {
        $data = [
            'status_options' => Options::STATUS_OPTIONS,
            'tags' => getAllTags(),
        ];

        return view('backend.book.create')->with($data);
    }

    public function store(BookRequest $request)
    {
        $validated = $request->validated();

        $book = $this->repository->create($validated);

        $book->tag($validated['tag']);

        return Response::jsonSuccess(__('response.create.success'));
    }

    public function edit($id)
    {
        $data = [
            'status_options' => Options::STATUS_OPTIONS,
            'tags' => getAllTags(),
            'book' => Book::findOrFail($id),
        ];

        return view('backend.book.edit')->with($data);
    }

    public function update(BookRequest $request, $id)
    {
        $validated = $request->validated();

        $this->repository->update($id, $validated);

        $book = $this->repository->find($id);

        $book->tag($validated['tag']);

        return Response::jsonSuccess(__('response.update.success'));
    }

    public function destroy($id)
    {
        $this->repository->destroy($id);

        return Response::jsonSuccess(__('response.destroy.success'));
    }

    public function review($id)
    {
        $data = [
            'review_options' => Options::REVIEW_OPTIONS,
            'book' => Book::findOrFail($id),
        ];

        return view('backend.book.review')->with($data);
    }

    public function updateReview(Request $request, $id)
    {
        $this->repository->update($id, $request->input());

        return Response::jsonSuccess(__('response.update.success'));
    }

    /**
     * 批次更新
     */
    public function batch(Request $request, $action)
    {
        $ids = explode(',', $request->input('ids'));

        switch ($action) {
            case 'review-1':
            case 'review-2':
            case 'review-3':
            case 'review-4':
            case 'review-5':
                $text = '审核状态';
                $check_status = (int) explode('-', $action)[1];
                $data = ['review' => $check_status];
                break;
            case 'enable':
                $text = '批量上架';
                $data = ['status' => 1];
                break;
            case 'disable':
                $text = '批量下架';
                $data = ['status' => -1];
                break;
            case 'charge':
                $text = '批量收费';
                break;
            case 'free':
                $text = '批量免费';
                break;
            case 'destroy':
                $text = '批量删除';
                break;
            default:
                return Response::jsonError('未知的操作');
        }

        switch ($action) {
            case 'charge':
                $books = Book::whereIn('id', $ids)->get();
                foreach ($books as $book) {
                    $book->chapters()->where('episode', '>', 10)->update(['charge' => 1]);
                }
                break;
            case 'free':
                $books = Book::whereIn('id', $ids)->get();
                foreach ($books as $book) {
                    $book->chapters()->update(['charge' => -1]);
                }
                break;
            case 'destroy':
                Book::whereIn('id', $ids)->delete();
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
