<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\PricingRequest;
use App\Models\PricingPackage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class PricingController extends Controller
{
    public function index()
    {
        $list = PricingPackage::paginate();

        return view('backend.pricing.index', [
            'list' => $list
        ]);
    }

    public function create()
    {
        return view('backend.pricing.create');
    }

    public function store(PricingRequest $request)
    {
        $request->merge([
            'preset' => $request->has('preset') ? 1 : 0,
        ]);

        $post = $request->post();

        $pricingPackage = new PricingPackage;

        $pricingPackage->fill($post)->save();

        return Response::jsonSuccess('添加套餐成功！');
    }

    public function edit($id)
    {
        $data = PricingPackage::findOrFail($id);

        return view('backend.pricing.edit', [
            'data' => $data
        ]);
    }

    public function update(Request $request, $id)
    {
        $pricingPackage = PricingPackage::findOrFail($id);

        $request->merge([
            'preset' => $request->has('preset') ? 1 : 0,
        ]);

        $post = $request->post();

        $pricingPackage->fill($post)->save();

        return Response::jsonSuccess('修改套餐成功！');
    }

    public function destroy($id)
    {
        $pricingPackage = PricingPackage::findOrFail($id);

        $pricingPackage->delete();

        return Response::jsonSuccess('刪除套餐成功！');
    }
}
