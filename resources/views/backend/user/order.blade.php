@extends('layouts.modal')

{{-- vendor style --}}
@section('vendor-styles')
@endsection

@section('content')
    <div class="table-responsive">
        <table class="table table-striped table-hover">
            <thead>
            <tr>
                <th>订单号</th>
                <th>方案类型</th>
                <th>平台</th>
                <th>订单金额</th>
                <th>订单创建时间</th>
                <th>支付渠道</th>
                <th>渠道訂單號</th>
                <th>渠道回調时间</th>
                <th>状态</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($list as $order)
                <tr>
                    <td>{{ $order->order_no }}</td>
                    <td>
                        @if($order->type == 'vip')
                            <label class="badge badge-light-primary badge-pill">{{ $type_options[$order->type] }}</label>
                        @else
                            <label class="badge badge-light-secondary badge-pill">{{ $type_options[$order->type] }}</label>
                        @endif
                    </td>
                    <td>{{ $order->platform }}</td>
                    <td>{{ $order->amount }}</td>
                    <td>{{ optional($order->created_at)->diffForHumans() ?? '' }}</td>
                    <td>{{ $order->payment->name }}</td>
                    <td>{{ $order->transaction_id }}</td>
                    <td>{{ $order->transaction_at }}</td>
                    <td>
                        @if($order->status == 1)
                            <label class="badge badge-light-success badge-pill">支付成功</label>
                        @else
                            <label class="badge badge-light-secondary badge-pill">待支付</label>
                        @endif
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    <div class="row">
        <div class="col-md-6">总共 <b>{{ $list->appends(request()->input())->total() }}</b> 条, 分为 <b>{{ $list->lastPage() }}</b> 页</div>
        <div class="col-md-6">{!! $list->appends(request()->input())->links() !!}</div>
    </div>
@endsection

@section('search-form')
@endsection

{{-- vendor scripts --}}
@section('vendor-scripts')
@endsection

{{-- page scripts --}}
@section('page-scripts')
@endsection

