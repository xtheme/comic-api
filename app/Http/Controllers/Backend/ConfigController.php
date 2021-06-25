<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Config;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class ConfigController extends Controller
{

    const GROUP_OPTIONS = [
        'app' => '基础配置',
        'service' => '客服配置',
        'payment' => '支付配置',
        'comment' => '评论配置',
    ];

    public function index(Request $request)
    {
        $request->merge([
            'group' => $request->input('group') ?: 'app',
        ]);

        $group = $request->input('group');
        $keyword = $request->input('keyword');

        $configs = Config::group($group)->keyword($keyword)->paginate();

        return view('backend.config.index', [
            'groups' => self::GROUP_OPTIONS,
            'list' => $configs,
            'pageConfigs' => ['hasSearchForm' => true],
        ]);
    }

    public function create()
    {
        return view('backend.config.create', [
            'groups' => self::GROUP_OPTIONS
        ]);
    }

    public function store(Request $request)
    {
        $post = $request->post();

        $code_exist = Config::where('code', $post['code'])->count();

        if ($code_exist) {
            return Response::jsonError('配置代码冲突，请变更配置代码！');
        }

        $config = new Config;

        $config->fill($post)->save();

        return Response::jsonSuccess(__('response.create.success'));
    }

    public function edit($id)
    {
        $config = Config::findOrFail($id);

        return view('backend.config.edit', [
            'config' => $config,
            'groups'   => self::GROUP_OPTIONS,
            'pageConfigs' => ['hasSearchForm' => false],
        ]);
    }

    public function update(Request $request, $id)
    {
        $post = $request->post();

        $code_exist = Config::where('id', '!=', $id)->where('code', $post['code'])->count();

        if ($code_exist) {
            return Response::jsonError('配置代码冲突，请变更配置代码！');
        }

        $config = Config::findOrFail($id);

        $config->fill($post)->save();

        return Response::jsonSuccess(__('response.create.success'));
    }

    public function destroy($id)
    {
        $config = Config::findOrFail($id);

        $config->delete();

        return Response::jsonSuccess(__('response.destroy.success'));
    }
}
