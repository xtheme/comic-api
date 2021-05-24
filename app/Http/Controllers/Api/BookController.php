<?php

namespace App\Http\Controllers\Api;

use App\Models\Book;
use App\Repositories\Contracts\BookRepositoryInterface;
use App\Repositories\HistoryRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class BookController extends BaseController
{
    protected $repository;

    public function __construct(BookRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }
    public function detail(Request $request, $id)
    {
        $book = $this->repository->find($id);

        $data = [
            'id' => $book->id,
            'title' => $book->title,
            'author' => $book->author,
            'cover' => $book->horizontal_thumb,
            'description' => $book->description,
            'charge' => $book->charge,
            'visit' => $book->visit_histories->count(),
            'favorite' => $book->favorite_histories->count(),
            'release_at' => $book->release_at,
            'latest_chapter_title' => $book->chapters->first()->title,
            'tagged_tags' => $book->tagged_tags
            // 'vertical_thumb' => $book->vertical_thumb,
        ];

        // todo 訪問數+1
        $log = [
            'major_id' => $id,
            'minor_id' => 0,
            'user_vip' => $request->user->subscribed_status ? 1 : -1,
            'user_id'  => $request->user->id,
            'type'     => 'visit',
            'class'    => 'book',
        ];
        app(HistoryRepository::class)->log($log);

        return Response::jsonSuccess(__('api.success'), $data);
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
            $books = Book::select(['id', 'title', 'vertical_cover'])->withAnyTag($tags)->where('id', '!=', $id)->inRandomOrder()->limit($limit)->get();
        } else {
            $books = Book::select(['id', 'title', 'vertical_cover'])->inRandomOrder()->limit($limit)->get();
        }

        $data = $books->map(function($book) {
            return [
                'id' => $book->id,
                'title' => $book->title,
                // 'author' => $book->author,
                // 'description' => $book->description,
                'cover' => $book->vertical_thumb,
                'tagged_tags' => $book->tagged_tags,

            ];
        })->toArray();

        return Response::jsonSuccess(__('api.success'), $data);
    }
}
