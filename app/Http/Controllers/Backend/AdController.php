<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\AdRequest;
use App\Models\Ad;
use App\Models\AdSpace;
use App\Repositories\Contracts\AdRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use Upload;

class AdController extends Controller
{

    private $repository;

    const JUMP_TYPE = [
        1 => '内置浏览器',
        2 => '站內功能跳转',
        3 => '外部浏览器',
        5 => '不跳转'
    ];

    const URL_TYPE = [
        'deposit' => '存款页',
        'book' => '漫画页',
        'video' => '动漫页'
    ];

    public function __construct(AdRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function index(Request $request)
    {
        $data = [
            'list' => $this->repository->filter($request)->paginate(),
            'ad_spaces' => AdSpace::orderBy('id')->get(),
            'pageConfigs' => ['hasSearchForm' => true],
        ];

        return view('backend.ad.index')->with($data);
    }

    public function create()
    {
        $data = [
            'ad_spaces' => AdSpace::orderBy('id')->get(),
        ];

        return view('backend.ad.create')->with($data);
    }

    public function store(AdRequest $request)
    {
        $ad = new Ad;

        $ad->fill($request->post())->save();

        return Response::jsonSuccess(__('response.create.success'));
    }

    public function edit($id)
    {

        $data = [
            'data' => Ad::findOrFail($id),
            'ad_spaces' => AdSpace::orderBy('id')->get(),
            'pageConfigs' => ['hasSearchForm' => true],
        ];

        return view('backend.ad.edit')->with($data);
    }

    public function update(AdRequest $request, $id)
    {
        $ad = Ad::findOrFail($id);

        $image = $ad->getRawOriginal('banner');
        Storage::delete($image);

        $ad->fill($request->post())->save();

        return Response::jsonSuccess(__('response.update.success'));
    }

    public function destroy($id)
    {
        $bookReportType = Ad::findOrFail($id);

        $bookReportType->delete();

        return Response::jsonSuccess(__('response.destroy.success'));
    }

    public function sort(Request $request)
    {
        $post = $request->post();

        Ad::where('id', $post['pk'])->update(['sort' => $post['value']]);

        return Response::jsonSuccess(__('response.update.success'));
    }

    public function batch(Request $request, $action)
    {
        $ids = explode(',', $request->input('ids'));

        switch ($action) {
            case 'enable':
                $text = '上架';
                $data = ['status' => 1];
                Ad::whereIn('id', $ids)->update($data);
                break;
            case 'disable':
                $text = '下架';
                $data = ['status' => -1];
                Ad::whereIn('id', $ids)->update($data);
                break;
            case 'destroy':
                $text = '删除';
                Ad::destroy($ids);
                break;
            default:
        }

        return Response::jsonSuccess($text . '成功！');
    }
}
