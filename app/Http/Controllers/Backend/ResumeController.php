<?php

namespace App\Http\Controllers\Backend;

use App\Enums\ResumeOptions;
use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\ResumeRequest;
use App\Models\ChinaArea;
use App\Models\ChinaCity;
use App\Models\ChinaProvince;
use App\Models\Resume;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class ResumeController extends Controller
{
    /**
     * 查询DB
     *
     * @param  Request  $request
     *
     * @return Builder
     */
    private function filter(Request $request): Builder
    {
        $province_id = $request->input('province_id') ?? '';
        $city_id = $request->input('city_id') ?? '';
        $area_id = $request->input('area_id') ?? '';
        $status = $request->input('status') ?? '';

        $query = Resume::query()->with(
            ['province', 'city', 'area']
        );

        if (!isSuperAdmin()) {
            $query->where(agent_type, 'admin')->where(auth()->id());
        }

        $query->when($province_id, function (Builder $query, $province_id) {
            return $query->where('province_id', $province_id);
        })->when($city_id, function (Builder $query, $city_id) {
            return $query->where('city_id', $city_id);
        })->when($area_id, function (Builder $query, $area_id) {
            return $query->where('area_id', $area_id);
        })->when($status, function (Builder $query, $status) {
            return $query->where('status', $status - 1);
        });

        return $query;
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

    private function getCities($province_id)
    {
        $cache_key = 'china:cities:' . $province_id;

        return Cache::remember($cache_key, 28800, function () use ($province_id) {
            $cities = ChinaCity::where('province_id', $province_id)->get();

            return $cities->mapWithKeys(function ($row) {
                return [$row->city_id => $row->city_name];
            });
        });
    }

    private function getAreas($city_id)
    {
        $cache_key = 'china:areas:' . $city_id;

        return Cache::remember($cache_key, 28800, function () use ($city_id) {
            $areas = ChinaArea::where('city_id', $city_id)->get();

            return $areas->mapWithKeys(function ($row) {
                return [$row->area_id => $row->area_name];
            });
        });
    }

    private function syncTag(Request $request, Resume $resume)
    {
        // $resume->syncTagsWithType([], 'resume');

        $tags = [];
        $tags[] = $resume->province->province_name;
        $tags[] = $resume->city->city_name;
        $tags[] = $resume->area->area_name;

        foreach ($request->input('body_shape') as $tag) {
            $tags[] = $tag;
        }

        foreach ($request->input('service') as $tag) {
            $tags[] = $tag;
        }

        $resume->syncTagsWithType($tags, 'resume');
    }

    public function index(Request $request)
    {
        $data = [
            'list' => $this->filter($request)->paginate(),
            'provinces' => $this->getProvinces(),
            'pageConfigs' => ['hasSearchForm' => true],
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
            'body_shape' => ResumeOptions::BODY_SHAPE,
            'service_type' => ResumeOptions::SERVICE_TYPE,
            'provinces' => $this->getProvinces(),
            'cities' => $this->getCities($resume->province_id),
            'areas' => $this->getAreas($resume->city_id),
        ];

        return view('backend.resume.edit')->with($data);
    }

    public function update(ResumeRequest $request, $id)
    {
        $resume = Resume::findOrFail($id);
        $resume->fill($request->input());
        $resume->save();

        $this->syncTag($request, $resume);

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
