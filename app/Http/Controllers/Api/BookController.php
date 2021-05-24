<?php

namespace App\Http\Controllers\Api;

use App\Models\Book;
use Illuminate\Support\Facades\Response;

class BookController extends BaseController
{
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
