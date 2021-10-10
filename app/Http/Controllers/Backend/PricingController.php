<?php

namespace App\Http\Controllers\Backend;

use App\Enums\PricingOptions;
use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\PricingRequest;
use App\Models\Pricing;
use Illuminate\Support\Facades\Response;

class PricingController extends Controller
{
    public function index()
    {
        $data = [
            'list' => Pricing::latest('sort')->paginate()
        ];

        return view('backend.pricing.index')->with($data);
    }

    public function create()
    {
        $data = [
            'type_options' => PricingOptions::TYPE_OPTIONS,
            'target_options' => PricingOptions::TARGET_OPTIONS,
            'status_options' => PricingOptions::STATUS_OPTIONS,
        ];

        return view('backend.pricing.create')->with($data);
    }

    public function store(PricingRequest $request)
    {
        $post = $request->post();

        $pack = new Pricing;

        $pack->fill($post)->save();

        return Response::jsonSuccess(__('response.create.success'));
    }

    public function edit($id)
    {
        $data = [
            'pack' => Pricing::findOrFail($id),
            'type_options' => PricingOptions::TYPE_OPTIONS,
            'target_options' => PricingOptions::TARGET_OPTIONS,
            'status_options' => PricingOptions::STATUS_OPTIONS,
        ];

        return view('backend.pricing.edit')->with($data);
    }

    public function update(PricingRequest $request, $id)
    {
        $post = $request->post();

        $pack = Pricing::findOrFail($id);

        $pack->fill($post)->save();

        return Response::jsonSuccess(__('response.update.success'));
    }

    public function destroy($id)
    {
        $pack = Pricing::findOrFail($id);

        $pack->delete();

        return Response::jsonSuccess(__('response.destroy.success'));
    }
}
