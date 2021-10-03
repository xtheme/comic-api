<?php

namespace App\Http\Controllers\Api;

use App\Models\Book;
use App\Models\BookChapter;
// use App\Repositories\Contracts\BookRepositoryInterface;
// use App\Repositories\HistoryRepository;
use App\Services\AdService;
use App\Services\RankingService;
use App\Traits\CacheTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Str;
use Record;

class BookController extends BaseController
{
    use CacheTrait;

    // protected $repository;
    //
    // public function __construct(BookRepositoryInterface $repository)
    // {
    //     $this->repository = $repository;
    // }

    public function detail($id)
    {
        // $book = Book::with(['chapters'])->withCount(['chapters', 'visit_histories', 'favorite_histories'])->find($id);
        $book = Book::with(['chapters'])->withCount(['chapters', 'visit_histories'])->find($id);

        if (!$book) {
            return Response::jsonError('该漫画不存在或已下架！');
        }

        $data = [
            'id' => $book->id,
            'title' => $book->title,
            'author' => $book->author,
            'cover' => $book->horizontal_thumb,
            'description' => $book->description,
            'charge' => $book->charge,
            'end' => $book->end,
            'type' => $book->type,
            'release_at' => $book->release_at,
            'latest_chapter_title' => $book->chapters_count ? $book->chapters->first()->title : '',
            // 'tagged_tags' => $book->tagged_tags,
            'tags' => $book->tags()->pluck('name'),
            'visit_histories_count' => shortenNumber($book->visit_histories_count),
            'favorite_histories_count' => shortenNumber($book->favorite_histories_count),
            'chapters' => $book->chapters->map(function ($chapter) {
                return [
                    'book_id' => $chapter->book_id,
                    'chapter_id' => $chapter->id,
                    'title' => $chapter->title,
                    'charge' => $chapter->charge,
                    'created_at' => $chapter->created_at->format('Y-m-d'),
                ];
            })->toArray(),
        ];

        // 訪問數+1
        Record::from('book')->visit($id);

        return Response::jsonSuccess(__('api.success'), $data);
    }

    public function chapters($book_id)
    {
        $book = Book::find($book_id);

        if (!$book) {
            return Response::jsonError('该漫画不存在或已下架！');
        }

        $data = $book->chapters->map(function ($chapter) {
            return [
                'book_id' => $chapter->book_id,
                'chapter_id' => $chapter->id,
                'title' => $chapter->title,
                'charge' => $chapter->charge,
                'created_at' => $chapter->created_at->format('Y-m-d'),
            ];
        })->toArray();

        return Response::jsonSuccess(__('api.success'), $data);
    }

    public function chapter(Request $request, $book_id, $chapter_id, $page = 1)
    {
        // 返回页面缓存
        $cache_key = $this->getCacheKeyPrefix() . sprintf('book:%s:chapter:%s:page:%s', $book_id, $chapter_id, $page);

        $cache_data = Cache::get($cache_key);

        if ($cache_data) {
            return Response::jsonSuccess(__('api.success'), $cache_data);
        }

        $page_size = 10;
        $chapter = BookChapter::where('book_id', $book_id)->find($chapter_id);

        if (!$chapter) {
            return Response::jsonError('该漫画不存在或已下架！');
        }

        if (!$request->user->subscribed_status && $chapter->charge == 1) {
            return Response::jsonError('请先开通 VIP！');
        }

        if (!$chapter->json_images) {
            $json_images = parseImgFromHtml($chapter->content);
            $chapter->update(['json_images' => $json_images]);
        }

        $images = collect($chapter->json_images)->map(function ($image) use ($chapter) {
            // todo change config
            // $encrypt_img = getOldConfig('web_config', 'if_encode_pic');
            // $webp_domain = getOldConfig('web_config', 'img_sync_url_password_webp');
            // $webp_width = getOldConfig('web_config', 'pic_webp_width');
            // $img_domain = getOldConfig('web_config', 'api_url');
            $encrypt_img = config('api.encrypt.image');
            $webp_domain = getConfig('app', 'webp_url');
            $webp_width = getConfig('app', 'webp_width');
            $img_domain = getConfig('app', 'img_url');

            if ($encrypt_img == 1) {
                if (Str::endsWith($webp_domain, '/')) {
                    $webp_domain = substr($webp_domain, 0, -1);
                }
                $image = $webp_domain . webp($image, $webp_width);
            } else {
                if (Str::endsWith($img_domain, '/')) {
                    $img_domain = substr($img_domain, 0, -1);
                }
                $image = $img_domain . $image;
            }

            return [
                'url' => $image
            ];
        })->toArray();

        // 分页
        $image_count = count($images);                  // 图片数
        $total_pages = ceil($image_count / $page_size); // 页数

        if ($page > $total_pages) {
            return Response::jsonError('已经是最后一页！');
        }

        // 针对每一页生成缓存
        $pages = [];
        for ($i = 1; $i <= $total_pages; $i++) {
            $start = ($i -1) * $page_size;
            $slice = array_slice($images, $start, $page_size); // 分页切片

            // 针对每一页插入广告
            $ad_type_id = 25; // 警告: 这里是写死的
            $adService = new AdService();
            $slice = $adService->insertAd($ad_type_id, $slice);

            $data = [
                'list'    => $slice,
                'extends' => [
                    'current_page' => $i,
                    'total_page'   => $total_pages,
                ],
            ];

            // 分页缓存
            $cache_key = $this->getCacheKeyPrefix() . sprintf('book:%s:chapter:%s:page:%s', $book_id, $chapter_id, $i);
            Cache::set($cache_key, $data, $this->getRandomTtl());

            $pages[$i] = $data;
        }

        // 记录访问
        // $this->logVisit($book_id);

        return Response::jsonSuccess(__('api.success'), $pages[$page]);
    }

    public function recommend($id = null)
    {
        $limit = 6;
        $tags = [];

        if ($id) {
            $book = Book::findOrFail($id);
            $tags = $book->tagged_tags;
        }

        if ($tags) {
            $books = Book::select(['id', 'title', 'vertical_cover'])->withCount(['visit_histories'])->withAnyTag($tags)->where('id', '!=', $id)->inRandomOrder()->limit($limit)->get();
        } else {
            $books = Book::select(['id', 'title', 'vertical_cover'])->withCount(['visit_histories'])->inRandomOrder()->limit($limit)->get();
        }

        $data = $books->map(function($book) {
            return [
                'id' => $book->id,
                'title' => $book->title,
                // 'author' => $book->author,
                // 'description' => $book->description,
                'cover' => $book->vertical_thumb,
                'tagged_tags' => $book->tagged_tags,
                'visit_histories_count' => shortenNumber($book->visit_histories_count),
            ];
        })->toArray();

        return Response::jsonSuccess(__('api.success'), $data);
    }
}
