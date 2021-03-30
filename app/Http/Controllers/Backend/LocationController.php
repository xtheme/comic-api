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
    /**
     * Display a listing of the resource.
     *
     * @return View|Factory
     */
    public function index()
    {
        $data = [
            'list' => Location::paginate()
        ];

        return view('backend.location.index')->with($data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return View|Factory
     */
    public function create()
    {
        return view('backend.location.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  LocationRequest  $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(LocationRequest $request)
    {
        $validated = $request->validated();

        Location::create($validated);

        return Response::jsonSuccess('資料已建立！');
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
     *
     * @return View|Factory
     */
    public function edit($id)
    {
        $data = [
            'location' => Location::findOrFail($id)
        ];

        return view('backend.location.edit')->with($data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  LocationRequest  $request
     * @param  int  $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update(LocationRequest $request, $id)
    {
        $validated = $request->validated();

        $model = Location::findOrFail($id);

        $model->fill($validated)->save();

        return Response::jsonSuccess('資料已更新！');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $model = Location::findOrFail($id);

        $model->delete();

        return Response::jsonSuccess('資料已刪除！');
    }
}
