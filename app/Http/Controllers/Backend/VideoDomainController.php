<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\VideoDomain;
use App\Repositories\Contracts\VideoDomainRepositoryInterface;
use Illuminate\Http\Request;
use Response;
use Validator;

class VideoDomainController extends Controller
{
    private $repository;

    public function __construct(VideoDomainRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function index(Request $request)
    {
        $data = [
            'domains' => $this->repository->filter($request)->paginate(),
            'pageConfigs' => ['hasSearchForm' => true],
        ];

        return view('backend.video_domain.index')->with($data);
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
                $data = ['chapter_status' => 1];
                break;
            case 'disable':
                $text = '批量封禁';
                $data = ['chapter_status' => 0];
                break;
            case 'charge':
                $text = '批量收费';
                $data = ['isvip' => 1];
                break;
            default:
            case 'free':
                $text = '批量免费';
                $data = ['isvip' => 0];
                break;
        }

        VideoDomain::whereIn('id', $ids)->update($data);

        return Response::jsonSuccess($text . '成功！');
    }

    public function editable(Request $request, $field)
    {
        $data = [
            'pk' => $request->post('pk'),
            'value' => $request->post('value'),
        ];

        $validator = Validator::make($data, [
            'pk' => 'required',
            'value' => 'required',
        ]);

        if ($validator->fails()) {
            return Response::jsonError($validator->errors()->first(), 500);
        }

        $this->repository->editable($request->post('pk'), $field, $request->post('value'));

        return Response::jsonSuccess('数据已更新成功');
    }
}
