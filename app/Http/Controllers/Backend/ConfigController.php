<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Config;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class ConfigController extends Controller
{
    private $tags;

    public function __construct()
    {
        $this->tags = [
            'base' => '基础设置',
            'service' => '客服配置',
            'payment' => '支付配置',
        ];
    }

    public function index(Request $request)
    {
        $group = $request->input('group');
        $keyword = $request->input('keyword');

        $configs = Config::group($group)->keyword($keyword)->paginate();

        return view('backend.config.index', [
            'tags' => $this->tags,
            'list' => $configs,
            'pageConfigs' => ['hasSearchForm' => true],
        ]);
    }

    public function create()
    {
        return view('backend.config.create', [
            'tags' => $this->tags
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

        return Response::jsonSuccess('添加资料成功！');
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        $config = Config::findOrFail($id);

        return view('backend.config.edit', [
            'config' => $config,
            'tags'   => $this->tags,
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

        return Response::jsonSuccess('更新资料成功！');
    }

    public function destroy($id)
    {
        $config = Config::findOrFail($id);

        $config->delete();

        return Response::jsonSuccess('操作成功！');
    }
}
