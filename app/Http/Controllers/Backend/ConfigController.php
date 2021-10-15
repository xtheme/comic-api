<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\ConfigRequest;
use App\Models\Config;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Response;

class ConfigController extends Controller
{
    public function index(Request $request)
    {
        $keyword = $request->input('keyword');

        $data = [
            'list' => Config::keyword($keyword)->paginate(),
            'pageConfigs' => ['hasSearchForm' => true],
        ];

        return view('backend.config.index')->with($data);
    }

    public function create()
    {
        return view('backend.config.create');
    }

    public function store(ConfigRequest $request)
    {
        $exists = Config::code($request->post('code'))->exists();

        if ($exists) {
            return Response::jsonError('配置代码冲突，请变更配置代码！');
        }

        $options = collect($request->post('options'))->reject(function ($item) {
            return $item['key'] == '';
        })->flatMap(function ($item) {
            return [$item['key'] => $item['value']];
        })->toArray();

        $config = new Config;
        $config->name = $request->post('name');
        $config->code = $request->post('code');
        $config->options = $options;
        $config->save();

        return Response::jsonSuccess(__('response.create.success'));
    }

    public function edit($id)
    {
        $config = Config::findOrFail($id);

        return view('backend.config.edit', [
            'config' => $config,
            'pageConfigs' => ['hasSearchForm' => false],
        ]);
    }

    public function update(ConfigRequest $request, $id)
    {
        $exists = Config::where('id', '!=', $id)->code($request->post('code'))->exists();

        if ($exists) {
            return Response::jsonError('配置代码冲突，请变更配置代码！');
        }

        $options = collect($request->post('options'))->reject(function ($item) {
            return $item['key'] == '';
        })->flatMap(function ($item) {
            return [$item['key'] => $item['value']];
        })->toArray();

        $config = Config::findOrFail($id);
        $config->name = $request->post('name');
        $config->code = $request->post('code');
        $config->options = $options;
        $config->save();

        // todo Observer 清除緩存
        $cache_key = 'config:' . $config->code;
        Cache::delete($cache_key);

        return Response::jsonSuccess(__('response.create.success'));
    }

    public function destroy($id)
    {
        $config = Config::findOrFail($id);

        $config->delete();

        return Response::jsonSuccess(__('response.destroy.success'));
    }
}
