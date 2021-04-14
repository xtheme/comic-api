<?php

namespace App\Repositories;

use App\Models\BookChapter;
use App\Repositories\Contracts\BookChapterRepositoryInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class BookChapterRepository extends Repository implements BookChapterRepositoryInterface
{
    protected $cache_ttl = 60; // 缓存秒数

    /**
     * @return string
     */
    public function model(): string
    {
        return BookChapter::class;
    }

    /**
     * @param  Request  $request
     *
     * @return Builder
     */
    public function filter(Request $request): Builder
    {
        $title = $request->get('title') ?? '';
        $model_id = $request->get('type') ?? '';
        $nickname = $request->get('nickname') ?? '';
        $status = $request->get('status') ?? '';
        $audit_status = $request->get('audit_status') ?? '';
        $created_at = $request->get('created_at') ?? '';

        $order = $request->get('order') ?? 'created_at';
        $sort = $request->get('sort') ?? 'DESC';

        return $this->model::latest();
    }
}
