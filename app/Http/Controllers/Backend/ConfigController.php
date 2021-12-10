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
            'pageConfigs' => ['hasSearchForm' => false],
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
            return [$item['key'] => [
                'remark' => $item['remark'],
                'value' => $item['value'],
            ]];
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
        $data = [
            'config' => Config::findOrFail($id),
            'pageConfigs' => ['hasSearchForm' => false],
        ];

        return view('backend.config.edit')->with($data);
    }

    public function update(ConfigRequest $request, $id)
    {
        $name = $request->post('name');
        $code = $request->post('code');
        $options = $request->post('options');

        $exists = Config::where('id', '!=', $id)->code($code)->exists();

        if ($exists) {
            return Response::jsonError('配置代码冲突，请变更配置代码！');
        }

        $options = collect($options)->reject(function ($item) {
            return $item['key'] == '';
        })->flatMap(function ($item) use ($code) {
            // 清除緩存
            $cache_key = sprintf('config:%s:%s', $code, $item['key']);
            Cache::forget($cache_key);

            return [$item['key'] => [
                'remark' => $item['remark'],
                'value' => $item['value'],
            ]];
        });

        $config = Config::findOrFail($id);
        $config->name = $name;
        $config->code = $code;
        $config->options = $options;
        $config->save();

        return Response::jsonSuccess(__('response.create.success'));
    }

    public function destroy($id)
    {
        $config = Config::findOrFail($id);

        $config->delete();

        return Response::jsonSuccess(__('response.destroy.success'));
    }
}
