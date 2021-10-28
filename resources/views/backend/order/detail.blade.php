@extends('layouts.modal')

{{-- page Title --}}
@section('title','订单详情')

{{-- page style --}}
@section('page-styles')
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <h5>用户信息</h5>
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <tbody>
                    <tr>
                        <td style="width: 15%;">用户昵称</td>
                        <td style="width: 35%;">{{ $order->user->name }} {{ $order->user->mobile }}</td>
                        <td style="width: 15%;">客户端版本</td>
                        <td style="width: 35%;">{{ $order->user->subscribed_until }}</td>
                    </tr>
                    <tr>
                        <td>钱包</td>
                        <td>{{ $order->user->wallet }}</td>
                        <td>最近登入时间</td>
                        <td>{{ $order->user->logged_at }}</td>
                    </tr>
                    <tr>
                        <td>用户手机号</td>
                        <td>{{ $order->user->mobile }}</td>
                        <td>注册时间</td>
                        <td>{{ $order->user->created_at }}</td>
                    </tr>
                    </tbody>
                </table>
            </div>
            <h5 class="mt-1">订单详情</h5>
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <tbody>
                    <tr>
                        <td style="width: 15%;">订单号</td>
                        <td style="width: 35%;">{{ $order->order_no }} @if($order->first)<span class="badge badge-light-success badge-pill">首储</span>@endif</td>
                        <td style="width: 15%;">订单创建时间</td>
                        <td style="width: 35%;">{{ $order->created_at }}</td>
                    </tr>
                    <tr>
                        <td>渠道訂單號</td>
                        <td>{{ $order->payment->name }} {{ $order->transaction_id }}</td>
                        <td>渠道回調时间</td>
                        <td>{{ $order->transaction_at }}</td>
                    </tr>
                    <tr>
                        <td>订单状态</td>
                        <td>
                            @if($order->status == 1)
                                <label class="badge badge-light-success badge-pill">支付成功</label>
                            @else
                                <label class="badge badge-light-secondary badge-pill">待支付</label>
                            @endif
                        </td>
                        <td>订单金额</td>
                        <td>{{ $order->amount }}</td>
                    </tr>
                    <tr>
                        <td>交易平台</td>
                        <td>{{ $order->platform }}</td>
                        <td>平台版本</td>
                        <td>{{ $order->version }}</td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
@endsection

{{-- page scripts --}}
@section('page-scripts')
@endsection
