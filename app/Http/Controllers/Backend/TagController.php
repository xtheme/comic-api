<?php

namespace App\Http\Controllers\Backend;

use App\Models\Category;
use App\Models\Tag;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Response;
use App\Http\Requests\Backend\TagRequest;
use Illuminate\Support\Facades\Validator;
use App\Repositories\Contracts\TagRepositoryInterface;
use Illuminate\Support\Str;

class TagController extends Controller
{
    private $repository;

    private $front_length_limit = 4;

    const STATUS_OPTIONS = [
        1 => '显示',
        -1 => '隐藏',
    ];

    public function __construct(TagRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function index(Request $request)
    {
        $data = [
            'status_options' => self::STATUS_OPTIONS,
            'tags' => $this->repository->filter($request)->paginate(),
            'pageConfigs' => ['hasSearchForm' => true],
        ];

        return view('backend.tag.index')->with($data);
    }

    public function create()
    {
        $data = [
            'status_options' => self::STATUS_OPTIONS,
            'categories' => Category::where('status', 1)->get(),
        ];

        return view('backend.tag.create')->with($data);
    }

    public function store(TagRequest $request)
    {
        if (Tag::where('name', $request->post('name'))->exists()) {
            return Response::jsonError('已有相同标签');
        }
        // QZMHS-1110 新增标签限制4哥汉字
        if ($request->has('suggest') && $request->post('suggest') == 1) {
            if (Str::length($request->post('name')) > $this->front_length_limit) {
                return Response::jsonError('前端显示的标签请勿超过 ' . $this->front_length_limit . ' 个字');
            }
        }

        $request->merge([
            'slug' => mb_strtolower($request->post('name'), 'UTF-8'),
            'queries' => 0,
        ]);

        $this->repository->create($request->post());

        return Response::jsonSuccess(__('response.create.success'));
    }

    public function edit($id)
    {
        $data = [
            'status_options' => self::STATUS_OPTIONS,
            'categories' => Category::where('status', 1)->get(),
        ];

        return view('backend.category.edit')->with($data);
    }

    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);

        $is_duplicate = Category::where('id', '!=', $id)->where('type', $request->input('type'))->exists();

        if ($is_duplicate) {
            return Response::jsonError('标签分类代号已经存在！');
        }

        $category->update($request->input());

        return Response::jsonSuccess(__('response.update.success'));
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
            return Response::jsonError($validator->errors()->first(), 500);
        }

        $tag = Tag::findFromString($request->post('pk'));

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
        $tags = explode(',', $request->input('ids'));

        switch ($action) {
            case 'dismiss_book':
                $text = '解除关联的漫画';
                collect($tags)->each(function ($string) {
                    $tag = Tag::findFromString($string);
                    $tag->tagged_book()->delete();
                });
                break;
            case 'dismiss_video':
                $text = '解除关联的动画';
                collect($tags)->each(function ($string) {
                    $tag = Tag::findFromString($string);
                    $tag->tagged_video()->delete();
                });
                break;
            case 'disable':
                $text = '在前端隐藏';
                collect($tags)->each(function ($string) {
                    $tag = Tag::findFromString($string);
                    $tag->suggest = 0;
                    $tag->save();
                });
                break;
            case 'enable':
                $text = '在前端显示';
                collect($tags)->each(function ($string) {
                    $tag = Tag::findFromString($string);
                    $tag->suggest = 1;
                    $tag->save();
                });
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
