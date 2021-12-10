<?php

namespace App\Http\Controllers\Backend;

use App\Enums\BookOptions;
use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\BookRequest;
use App\Http\Requests\Backend\UpdatePriceRequest;
use App\Models\Book;
use App\Models\BookChapter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class BookController extends Controller
{
    private function filter(Request $request): Builder
    {
        $id = $request->get('id') ?? null;
        $title = $request->get('title') ?? null;
        $type = $request->get('type') ?? null;
        $tags = $request->get('tags') ?? null;
        $review = $request->get('review') ?? null;
        $status = $request->get('status') ?? null;

        $order = $request->get('order') ?? 'id';
        $sort = $request->get('sort') ?? 'desc';

        $query = Book::with(['tags', 'last_chapter'])->withCount(['chapters'])->when($id, function (Builder $query, $id) {
            return $query->where('id', $id);
        })->when($title, function (Builder $query, $title) {
            return $query->where('title', 'like', '%' . $title . '%');
        })->when($type, function (Builder $query, $type) {
            return $query->where('type', $type);
        })->when($review, function (Builder $query, $review) {
            return $query->where('review', $review - 1);
        })->when($status, function (Builder $query, $status) {
            return $query->where('status', $status - 1);
        })->when($sort, function (Builder $query, $sort) use ($order) {
            if ($sort == 'desc') {
                return $query->orderByDesc($order);
            } else {
                return $query->orderBy($order);
            }
        });

        if ($tags && is_array($tags)) {
            foreach ($tags as $type => $tag) {
                $query->withAllTags($tag, $type);
            }
        }

        return $query;
    }

    public function index(Request $request)
    {
        $data = [
            'status_options' => BookOptions::STATUS_OPTIONS,
            'review_options' => BookOptions::REVIEW_OPTIONS,
            'charge_options' => BookOptions::CHARGE_OPTIONS,
            'type_options' => BookOptions::TYPE_OPTIONS,
            'list' => $this->filter($request)->paginate(),
            'categories' => getCategoryByType('book'),
            // 'default_charge_chapter' => getConfig('comic', 'default_charge_chapter'),
            // 'default_charge_price' => getConfig('comic', 'default_charge_price'),
            'pageConfigs' => ['hasSearchForm' => true],
        ];

        return view('backend.book.index')->with($data);
    }

    public function create()
    {
        $data = [
            'status_options' => BookOptions::STATUS_OPTIONS,
            'type_options' => BookOptions::TYPE_OPTIONS,
            'categories' => getCategoryByType('book'),
        ];

        return view('backend.book.create')->with($data);
    }

    public function store(BookRequest $request)
    {
        $book = Book::create($request->post());

        if ($request->has('tags') && is_array($request->input('tags'))) {
            foreach ($request->input('tags') as $type => $tag) {
                $book->attachTags($tag, $type);
            }
        }

        return Response::jsonSuccess(__('response.create.success'));
    }

    public function edit($id)
    {
        $data = [
            'status_options' => BookOptions::STATUS_OPTIONS,
            'type_options' => BookOptions::TYPE_OPTIONS,
            'categories' => getCategoryByType('book'),
            'book' => Book::findOrFail($id),
        ];

        return view('backend.book.edit')->with($data);
    }

    public function update(BookRequest $request, $id)
    {
        $book = Book::findOrFail($id);
        $book->fill($request->input());
        $book->save();

        if ($request->has('tags') && is_array($request->input('tags'))) {
            foreach ($request->input('tags') as $type => $tag) {
                $book->syncTagsWithType($tag, $type);
            }
        }


        return Response::jsonSuccess(__('response.update.success'));
    }

    public function destroy($id)
    {
        $book = Book::findOrFail($id);
        $book->delete();

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
            case 'review-0':
                $text = '待审核';
                $data = ['review' => 0];
                break;
            case 'review-1':
                $text = '审核成功';
                $data = ['review' => 1, 'status' => 1];
                break;
            case 'review-2':
                $text = '图片不完整';
                $data = ['review' => 2, 'status' => 0];
                break;
            case 'review-3':
                $text = '重复的漫画';
                $data = ['review' => 3, 'status' => 0];
                break;
            case 'review-4':
                $text = '版权争议';
                $data = ['review' => 4, 'status' => 0];
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

        // mass delete cache
        foreach($ids as $id) {
            $cache_key = sprintf('book:%s', $id);
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

        $book = Book::findOrFail($data['pk']);

        $book->update([
            $field => $data['value']
        ]);

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

    // public function price()
    // {
    //     $data = [
    //         'default_charge_chapter' => getConfig('comic', 'default_charge_chapter'),
    //         'default_charge_price' => getConfig('comic', 'default_charge_price'),
    //     ];
    //
    //     return view('backend.book.price')->with($data);
    // }

    public function revisePrice(UpdatePriceRequest $request)
    {
        $data = $request->validated();

        $ids = explode(',', $data['ids']);

        BookChapter::whereIn('book_id', $ids)->update(['price' => 0]);

        BookChapter::whereIn('book_id', $ids)->where('episode', '>=', $data['charge_chapter'])->update(['price' => $data['charge_price']]);

        return Response::jsonSuccess(__('response.update.success'));
    }

    public function review($id)
    {
        $data = [
            'review_options' => BookOptions::REVIEW_OPTIONS,
            'book' => Book::findOrFail($id),
        ];

        return view('backend.book.review')->with($data);
    }

    public function updateReview(Request $request, $id)
    {
        $book = Book::findOrFail($id);
        $book->fill($request->input());
        $book->save();

        return Response::jsonSuccess(__('response.update.success'));
    }
}
