<?php

namespace App\Http\Controllers\Backend;

use App\Enums\Options;
use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Spatie\Tags\Tag;

class CategoryController extends Controller
{
    public function index()
    {
        $dara = [
            'list' => Category::paginate(),
            'status_options' => Options::STATUS_OPTIONS,
        ];

        return view('backend.category.index')->with($dara);
    }

    public function create()
    {
        $data = [
            'status_options' => Options::STATUS_OPTIONS,
        ];

        return view('backend.category.create')->with($data);
    }

    public function store(Request $request)
    {
        $post = $request->post();

        $category = new Category;

        $category->fill($post)->save();

        return Response::jsonSuccess(__('response.create.success'));
    }

    public function edit($id)
    {
        $data = [
            'category' => Category::findOrFail($id),
            'status_options' => Options::STATUS_OPTIONS,
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

    public function destroy($id)
    {
        $category = Category::findOrFail($id);

        $category->delete();

        return Response::jsonSuccess(__('response.destroy.success'));
    }

    public function editTags($id)
    {
        $category = Category::findOrFail($id);

        $data = [
            'category' => $category,
            'tags' => $category->tags,
        ];

        return view('backend.category.editTags')->with($data);
    }

    public function updateTags(Request $request, $id)
    {
        // Log::debug($request->input());

        $category = Category::findOrFail($id);

        $type = $category->type;

        // 先獲取該類別所有標籤 id
        $tags = Tag::withType($type)->get();

        // 更新此次異動
        $changes = collect($request->input('tags'))->each(function($item) use ($type) {
            if (!$item['new_name']) return true;

            if ($item['name']) {
                // 舊標籤更新
                $tag = Tag::findOrCreate($item['name'], $type);
                $tag->name = $item['new_name'];
                $tag->save();
            } else {
                // 新標籤創建
                Tag::findOrCreate($item['new_name'], $type);
            }

            return true;
        })->map(function($item) {
            return $item['name'];
        })->toArray();

        // 比對哪些舊標籤不存在需要刪除
        $tags->each(function($tag) use ($changes) {
            if (!in_array($tag->name, $changes)) {
                $tag->delete();
            }
        });

        return Response::jsonSuccess(__('response.update.success'));
    }
}
