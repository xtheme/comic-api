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

class ResumeController extends Controller
{
    public function index()
    {
        $data = [
            'list' => Resume::paginate(),
        ];

        return view('backend.resume.index')->with($data);
    }

    public function create()
    {
        $cache_key = 'china:provinces';

        $provinces = Cache::remember($cache_key, 28800, function () {
            $provinces = ChinaProvince::get();

            return $provinces->mapWithKeys(function ($row) {
                return [$row->province_id => $row->province_name];
            });
        });

        $data = [
            'body_shape' => ResumeOptions::BODY_SHAPE,
            'service_type' => ResumeOptions::SERVICE_TYPE,
            'provinces' => $provinces,
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
}
