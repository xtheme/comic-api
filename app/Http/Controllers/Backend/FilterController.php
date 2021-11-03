<?php

namespace App\Http\Controllers\Backend;

use App\Enums\BookOptions;
use App\Enums\Options;
use App\Http\Controllers\Controller;
use App\Models\Filter;
use App\Models\Topic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class FilterController extends Controller
{

    public function index(Request $request)
    {
        $data = [
            'list' => Filter::paginate(),
        ];

        return view('backend.filter.index')->with($data);
    }

    public function create($type)
    {
        $data = [
            'type' => $type,
            'categories' => getCategoryByType($type),
            'causer_options' => Options::CAUSER_OPTIONS,
            'ribbon_options' => Options::RIBBON_OPTIONS,
            'type_options' => BookOptions::TYPE_OPTIONS,
        ];

        return view('backend.filter.create')->with($data);
    }

    public function store(Request $request)
    {
        $post = $request->post();

        $rule = new Filter;

        $rule->fill($post);

        $rule->save();

        return Response::jsonSuccess(__('response.create.success'));
    }

    public function edit($type, $id)
    {
        $data = [
            'data' => Filter::findOrFail($id),
            'type' => $type,
            'categories' => getCategoryByType($type),
            'causer_options' => Options::CAUSER_OPTIONS,
            'ribbon_options' => Options::RIBBON_OPTIONS,
            'type_options' => BookOptions::TYPE_OPTIONS,
        ];

        return view('backend.filter.edit')->with($data);
    }

    public function update(Request $request, $id)
    {
        $post = $request->post();

        $rule = Filter::findOrFail($id);

        if (!isset($post['tags'])) {
            $post['tags'] = [];
        }

        $rule->fill($post);

        $rule->save();

        return Response::jsonSuccess(__('response.update.success'));
    }

    public function destroy($id)
    {
        $block = Topic::findOrFail($id);

        $block->delete();

        return Response::jsonSuccess(__('response.destroy.success'));
    }

    public function sort(Request $request)
    {
        $post = $request->post();

        Topic::where('id', $post['pk'])->update(['sort' => $post['value']]);

        return Response::jsonSuccess('更新资料成功！');
    }

    /**
     * 批量操作
     */
    public function batch(Request $request, $action)
    {
        $ids = explode(',', $request->input('ids'));

        switch ($action) {
            case 'enable':
                $text = '启用';
                Topic::whereIn('id', $ids)->update(['status' => 1]);
                break;
            case 'disable':
                $text = '隐藏';
                Topic::whereIn('id', $ids)->update(['status' => 0]);
                break;
            case 'destroy':
                $text = '删除';
                Topic::destroy($ids);
                break;
            default:
                return Response::jsonError(__('response.error.unknown'));
        }

        return Response::jsonSuccess(__('response.success.complete', ['action' => $text]));
    }
}
