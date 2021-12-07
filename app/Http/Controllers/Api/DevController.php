<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\BookResource;
use App\Http\Resources\VideoResource;
use App\Repositories\Contracts\TopicRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Response;

class DevController extends Controller
{
    protected $repository;

    public function __construct(TopicRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function stress(Request $request)
    {
        $request->merge([
            'type' => 'book',
            'status' => 1,
        ]);

        $cache_key = 'stress';

        $data = Cache::remember($cache_key, 60, function () use ($request) {
            $topics = $this->repository->filter($request)->get();

            return $topics->map(function ($topic) {
                $list = $topic->filter->buildQuery()->take($topic->limit)->get();

                return [
                    'title' => $topic->filter->title,
                    'filter_id' => $topic->filter_id,
                    'spotlight' => $topic->spotlight,
                    'per_line' => $topic->row,
                    'list' => $this->arrangeData($topic->type, $list),
                ];
            })->toArray();
        });

        return Response::jsonSuccess(__('api.success'), $data);
    }

    private function arrangeData($type, $list)
    {
        $list = $list->map(function ($item) use ($type) {
            if ($type == 'book' || $type == 'book_safe') {
                return (new BookResource($item));
            } else {
                return (new VideoResource($item));
            }
        })->toArray();

        return $list;
    }

    /**
     * 查询廣告位底下的廣告列表
     */
    public function decrypt(Request $request)
    {
        $encrypted = $request->getContent();
        $decrypted = Crypt::decryptString($encrypted);
        // parse_str($decrypted, $params);
        $params = json_decode($decrypted, true);
        $request->replace($params);
        return Response::json($request->input());
    }
}
