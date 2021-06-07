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
        $tags = Tag::where('suggest', 1)->orderByDesc('priority')->get();

        $data = $tags->map(function ($tag) {
            return [
                'id'          => $tag->id,
                'name'        => $tag->name,
                'priority'    => $tag->priority,
                'description' => $tag->description,
            ];
        })->toArray();

        return Response::jsonSuccess(__('api.success'), $data);
    }

    public function book($tag, $page = 1)
    {
        $books = Book::with(['tagged'])->withCount(['visit_histories'])->withAnyTag([$tag])->forPage($page)->get();

        $data = $books->map(function ($book) {
            return [
                'id'                    => $book->id,
                'title'                 => $book->title,
                'author'                => $book->author,
                'cover'                 => $book->vertical_thumb,
                'tagged_tags'           => $book->tagged_tags,
                'visit_histories_count' => shortenNumber($book->visit_histories_count),
                'updated_at'            => $book->updated_at->format('Y-m-d'),
            ];
        })->toArray();

        return Response::jsonSuccess(__('api.success'), $data);
    }

    public function video($tag, $page = 1)
    {
        $videos = Video::with(['tagged'])->withCount(['visit_histories'])->withAnyTag([$tag])->forPage($page)->get();

        $data = $videos->map(function ($video) {
            return [
                'id'                    => $video->id,
                'title'                 => $video->title,
                'author'                => $video->author,
                'ribbon'                => $video->ribbon,
                'cover'                 => image_thumb($video->cover),
                'tagged_tags'           => $video->tagged_tags,
                'visit_histories_count' => shortenNumber($video->visit_histories_count),
                'updated_at'            => $video->updated_at->format('Y-m-d'),
            ];
        })->toArray();

        return Response::jsonSuccess(__('api.success'), $data);
    }
}
