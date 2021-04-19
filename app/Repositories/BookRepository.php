<?php

namespace App\Repositories;

use App\Models\Book;
use App\Repositories\Contracts\BookRepositoryInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class BookRepository extends Repository implements BookRepositoryInterface
{
    protected $cache_ttl = 60; // 缓存秒数

    /**
     * @return string
     */
    public function model(): string
    {
        return Book::class;
    }

    /**
     * @param  Request  $request
     *
     * @return Builder
     */
    public function filter(Request $request): Builder
    {
        $id = $request->get('id') ?? '';
        $book_name = $request->get('book_name') ?? '';
        $tag = $request->get('tag') ?? '';
        $check_status = $request->get('check_status') ?? '';
        $book_status = $request->get('book_status') ?? '';
        $charge = $request->get('charge') ?? '';

        $order = $request->get('order') ?? 'id';
        $sort = $request->get('sort') ?? 'desc';

        return $this->model::with(['tagged'])->withCount(['chapters', 'charge_chapters'])->when($id, function (Builder $query, $id) {
                return $query->where('id', $id);
            })->when($book_name, function (Builder $query, $book_name) {
                return $query->where('book_name', 'like', '%' . $book_name . '%');
            })->when($tag, function (Builder $query, $tag) {
                /** @noinspection PhpUndefinedMethodInspection */
                return $query->withAnyTag($tag);
            })->when($check_status, function (Builder $query, $check_status) {
                return $query->where('check_status', $check_status - 1);
            })->when($book_status, function (Builder $query, $book_status) {
                return $query->where('book_status', $book_status - 1);
            })->when($charge, function (Builder $query, $charge) {
                $charge = $charge - 1;
                if (!$charge) {
                    return $query->having('charge_chapters_count', 0);
                } else {
                    return $query->having('charge_chapters_count', '>=', 1);
                }
            })->when($sort, function (Builder $query, $sort) use ($order) {
                if ($sort == 'desc') {
                    return $query->orderByDesc($order);
                } else {
                    return $query->orderBy($order);
                }
            });
    }
}
