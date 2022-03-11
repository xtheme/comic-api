<?php

namespace App\Http\Controllers\Backend;

use App\Enums\ResumeOptions;
use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\ResumeRequest;
use App\Models\ChinaProvince;
use App\Models\Resume;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class ResumeController extends Controller
{
    public function index()
    {
        $filter = [];

        if (!isSuperAdmin()) {
            $filter = [
                'agent_type' => 'admin',
                'agent_id' => auth()->id(),
            ];
        }

        $data = [
            'list' => Resume::with(['province', 'city', 'area'])->where($filter)->paginate(),
        ];

        return view('backend.resume.index')->with($data);
    }

    public function create()
    {
        $data = [
            'body_shape' => ResumeOptions::BODY_SHAPE,
            'service_type' => ResumeOptions::SERVICE_TYPE,
            'provinces' => $this->getProvinces(),
        ];

        return view('backend.resume.create')->with($data);
    }

    private function getProvinces()
    {
        $cache_key = 'china:provinces';

        return Cache::remember($cache_key, 28800, function () {
            $provinces = ChinaProvince::get();

            return $provinces->mapWithKeys(function ($row) {
                return [$row->province_id => $row->province_name];
            });
        });
    }

    public function store(ResumeRequest $request)
    {
        Resume::create($request->post());

        return Response::jsonSuccess(__('response.create.success'));
    }

    public function edit($id)
    {
        $resume = Resume::findOrFail($id);

        $data = [
            'resume' => $resume,
        ];

        return view('backend.resume.edit')->with($data);
    }

    public function update(Request $request, $id)
    {
        $resume = Resume::findOrFail($id);
        $resume->name = $request->input('name');
        $resume->save();

        return Response::jsonSuccess(__('response.update.success'));
    }

    public function destroy($id)
    {
        Resume::destroy($id);

        return Response::jsonSuccess(__('response.destroy.success'));
    }

    /**
     * 批次更新
     */
    public function batch(Request $request, $action)
    {
        $ids = explode(',', $request->input('ids'));

        switch ($action) {
            case 'disable':
                $text = '批量下架';
                $data = ['status' => 0];
                break;
            default:
            case 'enable':
                $text = '批量上架';
                $data = ['status' => 1];
                break;
        }

        Resume::whereIn('id', $ids)->update($data);

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
            return Response::jsonError($validator->errors()->first());
        }

        $record = Resume::findOrFail($data['pk']);

        $record->update([
            $field => $data['value']
        ]);

        return Response::jsonSuccess(__('response.update.success'));
    }
}
