<?php

namespace App\Http\Controllers\Backend;

use App\Enums\NavigationOptions;
use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\NavigationRequest;
use App\Models\Navigation;
use Illuminate\Support\Facades\Response;

class NavigationController extends Controller
{
    public function index()
    {
        $data = [
            'list' => Navigation::orderByDesc('id')->paginate(),
            'type_options' => NavigationOptions::TYPE_OPTIONS,
        ];

        return view('backend.navigation.index')->with($data);
    }

    public function create()
    {
        $data = [
            'type_options' => NavigationOptions::TYPE_OPTIONS,
            'target_options' => NavigationOptions::TARGET_OPTIONS,
            'status_options' => NavigationOptions::STATUS_OPTIONS,
        ];

        return view('backend.navigation.create')->with($data);
    }

    public function store(NavigationRequest $request)
    {
        $post = $request->validated();
        $post['time'] = time();

        $model = new Navigation;
        $model->fill($post)->save();

        return Response::jsonSuccess(__('response.create.success'));
    }

    public function edit($id)
    {
        $data = [
            'data' => Navigation::findOrFail($id),
            'type_options' => NavigationOptions::TYPE_OPTIONS,
            'target_options' => NavigationOptions::TARGET_OPTIONS,
            'status_options' => NavigationOptions::STATUS_OPTIONS,
        ];

        return view('backend.navigation.edit')->with($data);
    }

    public function update(NavigationRequest $request, $id)
    {
        $post = $request->validated();

        $model = Navigation::findOrFail($id);
        $model->fill($post)->save();

        return Response::jsonSuccess(__('response.update.success'));
    }

    public function destroy($id)
    {
        $model = Navigation::findOrFail($id);

        $model->delete();

        return Response::jsonSuccess(__('response.destroy.success'));
    }
}
