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

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory
     */
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

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory
     */
    public function create()
    {

        return view('backend.config.create', [
            'tags' => $this->tags
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $post = $request->post();

        $config = new Config;

        $config->fill($post)->save();

        return Response::jsonSuccess('添加资料成功！');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory
     */
    public function edit($id)
    {
        $config = Config::findOrFail($id);

        return view('backend.config.edit', [
            'config' => $config,
            'tags'   => $this->tags,
            'pageConfigs' => ['hasSearchForm' => false],
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $post = $request->post();

        $config = Config::findOrFail($id);

        $config->fill($post)->save();

        return Response::jsonSuccess('更新资料成功！');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $config = Config::findOrFail($id);

        $config->delete();

        return Response::jsonSuccess('操作成功！');
    }
}
