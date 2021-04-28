<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\VideoAdRequest;
use App\Models\Ad;
use App\Models\AdSpace;
use App\Repositories\Contracts\AdRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Upload;

class AdController extends Controller
{

    private $repository;

    const JUMPTYPE = [
        1 => '内置浏览器',
        2 => 'App下载',
        3 => '外部浏览器',
        4 => '站内充值页',
        5 => '不跳转'

    ];

    public function __construct(AdRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $data = [
            'list' => $this->repository->filter($request)->paginate(),
            'pageConfigs' => ['hasSearchForm' => true],
            'ad_spaces' => AdSpace::orderBy('id')->get(),
            'jump_type' => self::JUMPTYPE,
        ];

        return view('backend.ad.index')->with($data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data = [
            'ad_spaces' => AdSpace::orderBy('id')->get(),
            'jump_type' => self::JUMPTYPE,
        ];

        return view('backend.ad.create')->with($data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param VideoAdRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(VideoAdRequest $request)
    {

        $post = $request->post();

        $video_ad = new Ad;

        $response = Upload::to('video_ads' , 1)->store($request->file('image'));

        if ($response['success'] == 1){
            $post['image'] = $response['path'];
        }
        $video_ad->fill($post)->save();

        return Response::jsonSuccess('新增资料成功！');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

        $data = [
            'data' => Ad::findOrFail($id),
            'pageConfigs' => ['hasSearchForm' => true],
            'ad_spaces' => AdSpace::orderBy('id')->get(),
            'jump_type' => self::JUMPTYPE,
        ];

        return view('backend.ad.edit')->with($data);;

    }

    /**
     * Update the specified resource in storage.
     *
     * @param VideoAdRequest $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(VideoAdRequest $request, $id)
    {

        $post = $request->post();

        $video_ad = Ad::findOrFail($id);

        if ($request->file('image')){
            $response = Upload::to('video_ads' , 1)->store($request->file('image'));

            if ($response['success'] == 1){
                $post['image'] = $response['path'];
            }
        }



        $video_ad->fill($post)->save();

        return Response::jsonSuccess('更新资料成功！');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $bookReportType = Ad::findOrFail($id);

        $bookReportType->delete();

        return Response::jsonSuccess('删除资料成功！');
    }

    /**
     * 修改順序
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function sort(Request $request)
    {
        $post = $request->post();

        Ad::where('id', $post['pk'])->update(['sort' => $post['value']]);

        return Response::jsonSuccess('更新资料成功！');
    }


    /**
     * 批次更新
     */
    public function batch(Request $request, $action)
    {
        $ids = explode(',', $request->post('ids'));

        switch ($action) {
            case 'enable':
                $text = '批量启用';
                $data = ['status' => 1];
                break;
            case 'disable':
                $text = '批量封禁';
                $data = ['status' => -1];
                break;
            default:
        }

        Ad::whereIn('id', $ids)->update($data);

        return Response::jsonSuccess($text . '成功！');
    }


    /**
     * 批量刪除
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function batchDestroy(Request $request, $ids)
    {
        $data = explode(',', $ids);
        Ad::destroy($data);
        return Response::jsonSuccess('删除成功！');
    }
}
