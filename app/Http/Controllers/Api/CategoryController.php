<?php

namespace App\Http\Controllers\Api;

use App\Models\Book;
use App\Models\Pricing;
use App\Models\Tag;
use App\Models\Video;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class CategoryController extends BaseController
{
    public function tags()
    {
        $tags = Tag::where('suggest', 1)->latest('order_column')->take(100)->get();

        $data = $tags->map(function ($tag) {
            return $tag->name;
        })->toArray();

        return Response::jsonSuccess(__('api.success'), $data);
    }

    public function search(Request $request)
    {
        $type = $request->post('type') ?? 'book';

        switch ($type) {
            case 'video':
                $data = $this->searchVideo($request);
                break;
            default:
            case 'book':
                $data = $this->searchBook($request);
                break;
        }


        $data = $data->map(function ($item) {
            return [
                'id' => $item->id,
                'title' => $item->title,
                'author' => $item->author,
                'cover' => $item->vertical_cover,
                'tagged_tags' => $item->tagged_tags,
                'end' => $item->end,
                'view_counts' => shortenNumber($item->view_counts),
                'created_at' => optional($item->created_at)->format('Y-m-d'),
            ];
        })->toArray();

        return Response::jsonSuccess(__('api.success'), $data);
    }

    private function searchBook(Request $request)
    {
        $keyword = $request->post('keyword') ?? null;
        $tag = $request->post('tag') ?? null;
        $end = $request->post('end') ?? null;
        $sort = $request->post('sort') ?? 'created_at';
        $page = $request->post('page') ?? 1;
        $size = $request->post('page') ?? 20;

        $data = Book::when($keyword, function (Builder $query, $keyword) {
            return $query->where('title', 'like', '%' . $keyword . '%')->orWhere('author', 'like', '%' . $keyword . '%');
        })->when($tag, function (Builder $query, $tag) {
            $tag = explode(',', $tag);
            return $query->withAnyTags($tag);
        })->when($end, function (Builder $query, $end) {
            return $query->where('end', $end);
        })->forPage($page, $size)->latest($sort)->get();

        return $data;
    }

    private function searchVideo(Request $request)
    {
        return [];
    }
}
