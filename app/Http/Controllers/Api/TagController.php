<?php

namespace App\Http\Controllers\Api;

use App\Models\Book;
use App\Models\Tag;
use App\Models\Video;
use Illuminate\Support\Facades\Response;

class TagController extends BaseController
{
    public function list()
    {
        $tags = Tag::where('suggest', 1)->latest('order_column')->take(100)->get();

        $data = $tags->map(function ($tag) {
            return $tag->name;
        })->toArray();

        return Response::jsonSuccess(__('api.success'), $data);
    }

    public function book($tag, $page = 1)
    {
        $books = Book::withCount(['visit_histories'])->withAllTags([$tag])->forPage($page)->get();

        $data = $books->map(function ($book) {
            return [
                'id'                    => $book->id,
                'title'                 => $book->title,
                'author'                => $book->author,
                'cover'                 => $book->vertical_cover,
                'tagged_tags'           => $book->tagged_tags,
                'visit_histories_count' => shortenNumber($book->visit_histories_count),
                'created_at'            => $book->created_at->format('Y-m-d'),
            ];
        })->toArray();

        return Response::jsonSuccess(__('api.success'), $data);
    }

    public function video($tag, $page = 1)
    {
        $videos = Video::withCount(['visit_histories'])->withAllTags([$tag])->forPage($page)->get();

        $data = $videos->map(function ($video) {
            return [
                'id'                    => $video->id,
                'title'                 => $video->title,
                'author'                => $video->author,
                'ribbon'                => $video->ribbon,
                'cover'                 => $video->cover,
                'tagged_tags'           => $video->tagged_tags,
                'visit_histories_count' => shortenNumber($video->visit_histories_count),
                'created_at'            => $video->created_at->format('Y-m-d'),
            ];
        })->toArray();

        return Response::jsonSuccess(__('api.success'), $data);
    }
}
