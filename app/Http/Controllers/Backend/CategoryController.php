<?php

namespace App\Http\Controllers\Backend;

use App\Enums\Options;
use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

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
        Category::fill($request->post())->save();

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
}
