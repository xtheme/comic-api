<?php

namespace App\Http\Controllers\Backend;

use App\Enums\Options;
use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\VideoRequest;
use App\Models\Video;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class VideoController extends Controller
{
    /**
     * 查询DB
     *
     * @param  Request  $request
     *
     * @return Builder
     */
    public function filter(Request $request): Builder
    {
        $title = $request->input('title') ?? '';
        $author = $request->input('author') ?? '';
        $ribbon = $request->input('ribbon') ?? '';
        $status = $request->input('status') ?? '';
        $tags = $request->input('tags') ?? '';
        $date_between = $request->input('date_between') ?? '';

        $order = $request->input('order') ?? 'created_at';
        $sort = $request->input('sort') ?? 'desc';

        $query = Video::when($title, function (Builder $query, $title) {
            return $query->whereLike('title', $title);
        })->when($author, function (Builder $query, $author) {
            return $query->whereLike('author', $author);
        })->when($ribbon, function (Builder $query, $ribbon) {
            return $query->where('ribbon', $ribbon);
        })->when($status, function (Builder $query, $status) {
            return $query->where('status', $status);
        })->when($date_between, function (Builder $query, $date_between) {
            $date = explode(' - ', $date_between);
            $start_date = $date[0] . ' 00:00:00';
            $end_date = $date[1] . ' 23:59:59';
            return $query->whereBetween('created_at', [
                $start_date,
                $end_date,
            ]);
        })->when($sort, function (Builder $query, $sort) use ($order) {
            if ($sort == 'desc') {
                return $query->orderByDesc($order);
            } else {
                return $query->orderBy($order);
            }
        });

        if ($tags && is_array($tags)) {
            foreach ($tags as $type => $tag) {
                $query->withAllTags($tag, $type);
            }
        }

        return $query;
    }

    public function index(Request $request)
    {
        $data = [
            'status_options' => Options::STATUS_OPTIONS,
            'ribbon_options' => Options::RIBBON_OPTIONS,
            'videos' => $this->filter($request)->paginate(),
            'categories' => getCategoryByType('video'),
            'pageConfigs' => ['hasSearchForm' => true],
        ];

        return view('backend.video.index')->with($data);
    }

    public function create()
    {
        $data = [
            'mosaic_options' => Options::MOSAIC_OPTIONS,
            'style_options' => Options::STYLE_OPTIONS,
            'subtitle_options' => Options::SUBTITLE_OPTIONS,
            'status_options' => Options::STATUS_OPTIONS,
            'ribbon_options' => Options::RIBBON_OPTIONS,
            'categories' => getCategoryByType('video'),
        ];

        return view('backend.video.create')->with($data);
    }

    public function store(VideoRequest $request)
    {
        $video = Video::create($request->post());

        if ($request->has('tags') && is_array($request->input('tags'))) {
            foreach ($request->input('tags') as $type => $tag) {
                $video->attachTags($tag, $type);
            }
        }

        return Response::jsonSuccess(__('response.create.success'));
    }

    public function edit($id)
    {
        $video = Video::findOrFail($id);

        $data = [
            'mosaic_options' => Options::MOSAIC_OPTIONS,
            'style_options' => Options::STYLE_OPTIONS,
            'subtitle_options' => Options::SUBTITLE_OPTIONS,
            'status_options' => Options::STATUS_OPTIONS,
            'ribbon_options' => Options::RIBBON_OPTIONS,
            'categories' => getCategoryByType('video'),
            'video' => $video,
        ];

        return view('backend.video.edit')->with($data);
    }

    public function update(VideoRequest $request, $id)
    {
        $video = Video::findOrFail($id);
        $video->fill($request->input());
        $video->save();

        if ($request->has('tags') && is_array($request->input('tags'))) {
            foreach ($request->input('tags') as $type => $tag) {
                $video->syncTagsWithType($tag, $type);
            }
        }

        return Response::jsonSuccess(__('response.update.success'));
    }

    public function destroy($id)
    {
        $video = Video::findOrFail($id);
        $video->tags()->detach();
        $video->delete();

        return Response::jsonSuccess(__('response.destroy.success'));
    }

    public function batch(Request $request, $action)
    {
        $ids = explode(',', $request->input('ids'));

        switch ($action) {
            case 'enable':
                $text = '上架';
                $data = ['status' => 1];
                break;
            case 'disable':
                $text = '下架';
                $data = ['status' => -1];
                break;
            default:
                return Response::jsonError(__('response.error.unknown'));
        }

        Video::whereIn('id', $ids)->update($data);

        return Response::jsonSuccess(__('response.success.complete', ['action' => $text]));
    }
}
