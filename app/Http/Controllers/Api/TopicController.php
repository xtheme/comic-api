<?php

namespace App\Http\Controllers\Api;

use App\Repositories\BlockRepository;
use App\Repositories\VideoRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class TopicController extends BaseController
{
    protected $blockRepository;
    protected $videoRepository;

    public function __construct()
    {
        $this->blockRepository = app(BlockRepository::class);
        $this->videoRepository = app(VideoRepository::class);
    }

    private function buildQuery($topic)
    {
        $model = new $topic->causer;

        $query = $model::query();

        foreach ($topic->properties as $key => $data) {
            $value = $data['value'] ?? null;

            if (!$value) {
                continue;
            }

            switch ($key) {
                case 'tag':
                    $query->withAllTags($value);
                    break;
                case 'limit':
                    $query->limit($value);
                    break;
                case 'order':
                    $query->orderByDesc($value);
                    break;
                case 'author':
                    $query->whereLike('author', $value);
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

        return $query->get();
    }

    public function topic(Request $request, $causer)
    {
        $request->merge([
            'causer' => $causer,
            'status' => 1,
        ]);

        $topics = $this->blockRepository->filter($request)->get();

        $data = $topics->map(function ($topic) {
            return [
                'title'     => $topic->title,
                'spotlight' => $topic->spotlight,
                'per_line'  => $topic->row,
                'list'      => $this->buildQuery($topic),
            ];
        });

        return Response::jsonSuccess(__('api.success'), $data);
    }


}
