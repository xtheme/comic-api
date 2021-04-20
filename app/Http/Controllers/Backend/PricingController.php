<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\PricingpackageRequest;
use App\Models\PricingPackage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class PricingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $list = PricingPackage::paginate();

        return view('backend.pricing.index', [
            'list' => $list
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('backend.pricing.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(PricingpackageRequest $request)
    {
        $post = $request->post();

        $pricingPackage = new PricingPackage;

        $pricingPackage->fill($post)->save();

        return Response::jsonSuccess('添加套餐成功！');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

        $data = PricingPackage::findOrFail($id);

        return view('backend.pricing.edit', [
            'data' => $data
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $pricingPackage = PricingPackage::findOrFail($id);

        $post = $request->post();

        $pricingPackage->fill($post)->save();

        return Response::jsonSuccess('修改套餐成功！');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $pricingPackage = PricingPackage::findOrFail($id);

        $pricingPackage->delete();

        return Response::jsonSuccess('刪除套餐成功！');
    }
}
