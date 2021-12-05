<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Tag;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class TagController extends Controller
{
    private $front_length_limit = 4;

    const STATUS_OPTIONS = [
        1 => '显示',
        -1 => '隐藏',
    ];

    private function filter(Request $request): Builder
    {
        $keyword = $request->get('keyword') ?? '';
        $suggest = $request->get('suggest') ?? '';
        $tagged_book = $request->get('tagged_book') ?? '';
        $tagged_video = $request->get('tagged_video') ?? '';

        return Tag::with(['tagged_book', 'tagged_video', 'category'])->withCount(['tagged_book', 'tagged_video'])->when($keyword, function (Builder $query, $keyword) {
            return $query->where('name', 'like', '%' . $keyword . '%')->orWhere('slug', 'like', '%' . $keyword . '%');
        })->when($suggest, function (Builder $query, $suggest) {
            return $query->where('suggest', $suggest);
        })->when($tagged_book, function (Builder $query) {
            return $query->having('tagged_book_count', '>', 0);
        })->when($tagged_video, function (Builder $query) {
            return $query->having('tagged_video_count', '>', 0);
        })->orderByDesc('order_column');
    }

    public function index(Request $request)
    {
        $data = [
            'status_options' => self::STATUS_OPTIONS,
            'tags' => $this->filter($request)->paginate(),
            'pageConfigs' => ['hasSearchForm' => true],
        ];

        return view('backend.tag.index')->with($data);
    }

    public function editable(Request $request, $field)
    {
        $data = [
            'pk' => $request->post('pk'),
            'value' => $request->post('value'),
        ];

        $validator = Validator::make($data, [
            'pk' => 'required',
            'value' => 'required',
        ]);

        if ($validator->fails()) {
            return Response::jsonError($validator->errors()->first());
        }

        [$tag_name, $tag_type] = explode('-', $request->post('pk'));

        if ($tag_type) {
            $tag = Tag::findFromString($tag_name, $tag_type);
        } else {
            $tag = Tag::findFromString($tag_name);
        }

        // QZMHS-1110 新增标签限制4哥汉字
        if ($field == 'name') {
            if ($tag->name == $request->post('value')) {
                return Response::jsonError('已有相同标签');
            }

            if (Str::length($request->post('value')) > $this->front_length_limit) {
                return Response::jsonError('前端显示的标签请勿超过 ' . $this->front_length_limit . ' 个字');
            }
        }

        $tag->$field = $request->post('value');
        $tag->save();

        return Response::jsonSuccess(__('response.update.success'));
    }

    public function batch(Request $request, $action)
    {
        $ids = explode(',', $request->input('ids'));

        switch ($action) {
            case 'dismiss_book':
                $text = '解除关联的漫画';
                Tag::whereIn('id', $ids)->tagged_book()->delete();
                break;
            case 'dismiss_video':
                $text = '解除关联的动画';
                Tag::whereIn('id', $ids)->tagged_video()->delete();
                break;
            case 'disable':
                $text = '在前端隐藏';
                Tag::whereIn('id', $ids)->update(['suggest' => 0]);
                break;
            case 'enable':
                $text = '在前端显示';
                Tag::whereIn('id', $ids)->update(['suggest' => 1]);
                break;
            default:
                return Response::jsonError(__('response.error.unknown'));
        }

        return Response::jsonSuccess(__('response.success.complete', ['action' => $text]));
    }

    public function destroy($name)
    {
        $tag = Tag::findFromString($name);
        $tag->tagged_book()->delete();
        $tag->tagged_video()->delete();
        $tag->delete();

        return Response::jsonSuccess(__('response.destroy.success'));
    }
}
