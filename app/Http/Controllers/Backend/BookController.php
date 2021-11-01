<?php

namespace App\Http\Controllers\Backend;

use App\Enums\BookOptions;
use App\Enums\Options;
use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\BookRequest;
use App\Http\Requests\Backend\UpdatePriceRequest;
use App\Models\Book;
use App\Models\BookChapter;
use App\Repositories\Contracts\BookRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
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
            'type_options' => BookOptions::TYPE_OPTIONS,
            'list' => $this->repository->filter($request)->paginate(),
            'categories' => getCategoryByType('book'),
            'pageConfigs' => ['hasSearchForm' => true],
        ];

        return view('backend.book.index')->with($data);
    }

    public function price()
    {
        $data = [
            'default_charge_chapter' => getConfig('comic', 'default_charge_chapter'),
            'default_charge_price' => getConfig('comic', 'default_charge_price'),
        ];

        return view('backend.book.price')->with($data);
    }

    public function revisePrice(UpdatePriceRequest $request)
    {
        BookChapter::where('episode', '>=', $request->input('charge_chapter'))->update(['price' => $request->input('charge_price')]);

        return Response::jsonSuccess(__('response.update.success'));
    }

    public function create()
    {
        $data = [
            'status_options' => Options::STATUS_OPTIONS,
            'categories' => getCategoryByType('book'),
        ];

        return view('backend.book.create')->with($data);
    }

    public function store(BookRequest $request)
    {
        $this->repository->create($request->post());

        return Response::jsonSuccess(__('response.create.success'));
    }

    public function edit($id)
    {
        $data = [
            'status_options' => Options::STATUS_OPTIONS,
            'categories' => getCategoryByType('book'),
            'book' => Book::findOrFail($id),
        ];

        return view('backend.book.edit')->with($data);
    }

    public function update(BookRequest $request, $id)
    {
        $this->repository->update($id, $request->post());

        return Response::jsonSuccess(__('response.update.success'));
    }

    public function destroy($id)
    {
        $this->repository->destroy($id);

        return Response::jsonSuccess(__('response.destroy.success'));
    }

    /**
     * 批次更新
     */
    public function batch(Request $request, $action)
    {
        $ids = explode(',', $request->input('ids'));

        switch ($action) {
            case 'japan':
                $text = '标记为日漫';
                $data = ['type' => 1];
                break;
            case 'korea':
                $text = '标记为韩漫';
                $data = ['type' => 2];
                break;
            case 'american':
                $text = '标记为美漫';
                $data = ['type' => 3];
                break;
            case 'album':
                $text = '标记为写真';
                $data = ['type' => 4];
                break;
            case 'cg':
                $text = '标记为CG';
                $data = ['type' => 5];
                break;
            case 'featured':
                $text = '标记为精选封面';
                $data = null;
                break;
            case 'end':
                $text = '标记为完结';
                $data = ['end' => 1];
                break;
            case 'enable':
                $text = '批量上架';
                $data = ['status' => 1];
                break;
            case 'disable':
                $text = '批量下架';
                $data = ['status' => 0];
                break;
            case 'syncPrice':
                $text = '套用预设收费设置';
                break;
            case 'destroy':
                $text = '批量删除';
                break;
            default:
                return Response::jsonError('未知的操作');
        }

        switch ($action) {
            case 'japan':
            case 'korea':
            case 'american':
            case 'album':
            case 'cg':
            case 'end':
            case 'featured':
                $tag = [
                    'japan' => '日漫',
                    'korea' => '韩漫',
                    'american' => '美漫',
                    'album' => '写真',
                    'cg' => 'CG',
                    'featured' => '精选',
                    'end' => '完结',
                ];
                $books = Book::whereIn('id', $ids)->get();
                foreach ($books as $book) {
                    $book->attachTag($tag[$action], 'book');
                }

                if ($data) {
                    Book::whereIn('id', $ids)->update($data);
                }
                break;
            case 'syncPrice':
                // 批量收费
                $books = Book::whereIn('id', $ids)->get();
                foreach ($books as $book) {
                    $book->chapters()->where('episode', '>', getConfig('comic', 'default_charge_chapter'))->update(['price' => getConfig('comic', 'default_charge_price')]);
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

    // 批輛新增標籤
    public function modifyTag(Request $request, $action)
    {
        $data = [
            'url' => ($action == 'add') ? route('backend.book.addTag') : route('backend.book.deleteTag'),
            'ids' => $request->input('ids'),
            'categories' => getCategoryByType('book'),
        ];

        return view('backend.book.modifyTag')->with($data);
    }

    public function addTag(Request $request)
    {
        $ids = explode(',', $request->post('ids'));
        $tags = $request->post('tags');

        foreach ($ids as $id) {
            $book = Book::findOrFail($id);

            foreach ($tags as $type => $tag) {
                $book->attachTags($tag, $type);
            }
        }

        return Response::jsonSuccess('标签已更新');
    }

    public function deleteTag(Request $request)
    {
        $ids = explode(',', $request->post('ids'));
        $tags = $request->post('tags');

        foreach ($ids as $id) {
            $book = Book::findOrFail($id);

            foreach ($tags as $type => $tag) {
                $book->detachTags($tag, $type);
            }
        }

        return Response::jsonSuccess('标签已更新');
    }

    // CDN 預熱清單
    public function caching(Request $request)
    {
        $books = $this->repository->filter($request)->take(20)->get();

        $images = $books->reject(function ($book) {
            return $book->chapters_count === 0;
        })->map(function ($book) {
            $chapters = $book->chapters;

            return $chapters->reject(function ($chapter) {
                return $chapter->json_images === '';
            })->map(function ($chapter) {
                return collect($chapter->json_images)->map(function ($image) {
                    return getImageDomain() . webpWidth($image, getConfig('app', 'webp_width'));
                });
            })->flatten()->toArray();
        })->flatten()->toArray();

        $txt = '';

        foreach ($images as $image) {
            $txt .= $image . "\n";
        }

        return response($txt)->withHeaders([
            'Content-Type' => 'text/plain',
            'Cache-Control' => 'no-store, no-cache',
            'Content-Disposition' => 'attachment; filename="CDN预热名单_' . date('Y-m-d') . '.txt',
        ]);
    }
}
