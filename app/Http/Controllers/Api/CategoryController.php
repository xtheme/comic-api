<?php

namespace App\Http\Controllers\Api;

use App\Models\Book;
use App\Models\Tag;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Str;

class CategoryController extends BaseController
{
    public function tags($type = 'book')
    {
        $tags = Tag::whereLike('type', $type)->latest('order_column')->get();

        $data = $tags->mapToGroups(function ($tag) {
            return [$tag->type => $tag->name];
        })->toArray();

        return Response::jsonSuccess(__('api.success'), $data);
    }

    // todo composer require protonemedia/laravel-cross-eloquent-search
    public function search(Request $request)
    {
        $params = [
            'keyword' => $request->post('keyword') ?? null,
            'tags' => $request->post('tags') ?? null,
            'end' => $request->post('end') ?? null,
            'sort' => $request->post('sort') ?? 'created_at',
            'page' => $request->post('page') ?? 1,
            'size' => $request->post('size') ?? 20,
        ];

        $type = $request->post('type') ?? 'book';

        $class = sprintf('App\Models\%s', Str::ucfirst($type));

        $model = new $class;

        $query = $model::query()->where('status', 1);

        // 標籤條件
        if ($params['tags']) {
            if (is_array($params['tags'])) {
                foreach ($params['tags'] as $type => $tag_str) {
                    $query->withAllTags(explode(',', $tag_str), $type);
                }
            } else {
                $query->withAllTags(explode(',', $params['tags']), 'book');
            }
        }

        // 查詢條件
        foreach ($params as $field => $value) {
            if (!$value) {
                continue;
            }

            switch ($field) {
                case 'keyword':
                    $query->where('title', 'like', '%' . $value . '%')->orWhere('author', 'like', '%' . $value . '%');
                    break;
                case 'end':
                    // 漫畫才有此欄位
                    $query->where('end', 1);
                    break;
                case 'order_by':
                    $query->latest($value);
                    break;
                case 'date_between':
                    $date = explode(' - ', $value);
                    $start_date = $date[0] . ' 00:00:00';
                    $end_date = $date[1] . ' 23:59:59';
                    $query->whereBetween('created_at', [
                        $start_date,
                        $end_date,
                    ]);
                    break;
            }
        }

        $count = (clone $query)->count();

        $total_page = ceil($count / $params['size']);

        // $sql = $query->forPage($page, $size)->toSql();
        // Log::debug($sql);
        $data = (clone $query)->forPage($params['page'], $params['size'])->get();

        $list = $data->map(function ($item) use ($type) {
            return [
                'id' => $item->id,
                'title' => $item->title,
                'author' => $item->author,
                'cover' => ($type == 'book') ? $item->horizontal_cover : $item->cover,
                'tagged_tags' => $item->tagged_tags,
                'view_counts' => shortenNumber($item->view_counts),
                'created_at' => optional($item->created_at)->format('Y-m-d'),
            ];
        })->toArray();

        $data = [
            'page' => $params['page'],
            'size' => $params['size'],
            'total_page' => $total_page,
            'list' => $list,
        ];

        return Response::jsonSuccess(__('api.success'), $data);
    }
}
