<?php

namespace App\Http\Controllers\Backend;

use App\Enums\PaymentOptions;
use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\PaymentRequest;
use App\Models\Payment;
use App\Models\Pricing;
use Illuminate\Support\Facades\Response;

class PaymentController extends Controller
{
    public function index()
    {
        $data = [
            'list' => Payment::with('packages')->paginate(),
        ];

        return view('backend.payment.index')->with($data);
    }

    public function create()
    {
        $data = [
            'pricing' => Pricing::where('status', 1)->orderByDesc('sort')->get(),
            'status_options' => PaymentOptions::STATUS_OPTIONS,
            'target_options' => PaymentOptions::TARGET_OPTIONS,
            'icon_options' => PaymentOptions::ICON_OPTIONS,
        ];

        return view('backend.payment.create')->with($data);
    }

    public function store(PaymentRequest $request)
    {
        $pay_options = collect($request->post('pay_options'))->reject(function ($item) {
            return $item['key'] == '';
        })->flatMap(function ($item) {
            return [$item['key'] => $item['value']];
        })->toArray();

        $order_options = collect($request->post('order_options'))->reject(function ($item) {
            return $item['key'] == '';
        })->flatMap(function ($item) {
            return [$item['key'] => $item['value']];
        })->toArray();

        $payment = new Payment;
        $payment->name = $request->post('name');
        $payment->url = $request->post('url');
        $payment->app_id = $request->post('app_id');
        $payment->app_key = $request->post('app_key');
        $payment->button_text = $request->post('button_text');
        $payment->button_icon = $request->post('button_icon');
        $payment->button_target = $request->post('button_target');
        $payment->fee_percentage = $request->post('fee_percentage');
        $payment->sdk = $request->post('sdk');
        $payment->business_hours = $request->post('business_hours');
        $payment->daily_limit = $request->post('daily_limit');
        $payment->pay_options = $pay_options;
        $payment->order_options = $order_options;
        $payment->status = $request->post('status');
        $payment->save();

        $payment->packages()->sync($request->post('packages'));

        return Response::jsonSuccess(__('response.create.success'));
    }

    public function edit($id)
    {
        $data = [
            'payment' => Payment::findOrFail($id),
            'pricing' => Pricing::where('status', 1)->orderByDesc('sort')->get(),
            'status_options' => PaymentOptions::STATUS_OPTIONS,
            'target_options' => PaymentOptions::TARGET_OPTIONS,
            'icon_options' => PaymentOptions::ICON_OPTIONS,
        ];

        return view('backend.payment.edit')->with($data);
    }

    public function update(PaymentRequest $request, $id)
    {
        $pay_options = collect($request->post('pay_options'))->reject(function ($item) {
            return $item['key'] == '';
        })->flatMap(function ($item) {
            return [$item['key'] => $item['value']];
        })->toArray();

        $order_options = collect($request->post('order_options'))->reject(function ($item) {
            return $item['key'] == '';
        })->flatMap(function ($item) {
            return [$item['key'] => $item['value']];
        })->toArray();

        $payment = Payment::findOrFail($id);
        $payment->name = $request->post('name');
        $payment->url = $request->post('url');
        $payment->app_id = $request->post('app_id');
        $payment->app_key = $request->post('app_key');
        $payment->button_text = $request->post('button_text');
        $payment->button_icon = $request->post('button_icon');
        $payment->button_target = $request->post('button_target');
        $payment->fee_percentage = $request->post('fee_percentage');
        $payment->sdk = $request->post('sdk');
        $payment->business_hours = $request->post('business_hours');
        $payment->daily_limit = $request->post('daily_limit');
        $payment->pay_options = $pay_options;
        $payment->order_options = $order_options;
        $payment->status = $request->post('status');
        $payment->save();

        $payment->packages()->sync($request->post('packages'));

        return Response::jsonSuccess(__('response.update.success'));
    }

    public function destroy($id)
    {
        $pack = Payment::findOrFail($id);

        $pack->delete();

        return Response::jsonSuccess(__('response.destroy.success'));
    }
}
