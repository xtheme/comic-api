<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\LocationRequest;
use App\Models\Location;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Response;

class LocationController extends Controller
{
    public function index()
    {
        $data = [
            'list' => Location::paginate()
        ];

        return view('backend.location.index')->with($data);
    }

    public function create()
    {
        return view('backend.location.create');
    }

    public function store(LocationRequest $request)
    {
        $validated = $request->validated();

        Location::create($validated);

        return Response::jsonSuccess('資料已建立！');
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        $data = [
            'location' => Location::findOrFail($id)
        ];

        return view('backend.location.edit')->with($data);
    }

    public function update(LocationRequest $request, $id)
    {
        $validated = $request->validated();

        $model = Location::findOrFail($id);

        $model->fill($validated)->save();

        return Response::jsonSuccess('資料已更新！');
    }

    public function destroy($id)
    {
        $model = Location::findOrFail($id);

        $model->delete();

        return Response::jsonSuccess('資料已刪除！');
    }
}
