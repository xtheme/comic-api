@extends('layouts.iframePage')

{{-- page Title --}}
@section('title','订单列表')

{{-- vendor style --}}
@section('vendor-styles')
    <link rel="stylesheet" type="text/css" href="{{ asset('vendors/css/pickers/daterange/daterangepicker.css') }}">
@endsection

@section('content')
    <section id="config-list">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">@yield('title')</h4>
            </div>
            <div class="card-content">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                            <tr>
                                <th>订单号</th>
                                <th>方案类型</th>
                                <th>平台</th>
                                <th>充值金额</th>
                                <th>用户ID</th>
                                <th>订单创建时间</th>
                                <th>支付渠道</th>
                                <th>渠道訂單號</th>
                                <th>渠道回調时间</th>
                                <th>状态</th>
                                <th>操作</th>
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
                                            <label class="badge badge-light-warning badge-pill">{{ $type_options[$order->type] }}</label>
                                        @endif
                                    </td>
                                    <td>{{ $order->platform }}</td>
                                    <td>{{ $order->amount }}</td>
                                    <td>{{ $order->user_id }}</td>
                                    <td>{{ optional($order->created_at)->diffForHumans() ?? '' }}</td>
                                    <td>{{ $order->payment_id }} : {{ $order->payment->name }}</td>
                                    <td>{{ $order->transaction_id }}</td>
                                    <td>{{ $order->transaction_at }}</td>
                                    <td>
                                        @if($order->status == 1)
                                            <label class="badge badge-light-success badge-pill">支付成功</label>
                                        @else
                                            <label class="badge badge-light-secondary badge-pill">待支付</label>
                                        @endif
                                    </td>
                                    <td>
                                        <a class="btn btn-primary btn-sm" data-modal href="{{ route('backend.order.detail', $order->id) }}" title="查看">查看</a>
                                        @if($order->status == 0 && $order->can_manual_callback)
                                        <a class="btn btn-warning btn-sm" data-confirm href="{{ route('backend.order.callback', $order->id) }}" title="手动上分">補單</a>
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
                </div>
            </div>
        </div>
    </section>
@endsection

@section('search-form')
    <h4 class="text-uppercase mb-0">查询</h4>
    <small></small>
    <hr>
    <form id="search-form" class="form form-vertical" method="get" action="{{ url()->current() }}" novalidate>
        <div class="form-body">
            <div class="row">
                <div class="col-12">
                    <div class="form-group">
                        <label>用户ID</label>
                        <div class="controls">
                            <input type="text" class="form-control"
                                   name="user_id" value="{{ request()->get('user_id') }}"
                                   placeholder="">
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group">
                        <label>订单号</label>
                        <div class="controls">
                            <input type="text" class="form-control"
                                   name="order_no" value="{{ request()->get('order_no') }}"
                                   placeholder="">
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group">
                        <label>支付渠道ID</label>
                        <div class="controls">
                            <input type="text" class="form-control"
                                   name="payment_id" value="{{ request()->get('payment_id') }}"
                                   placeholder="">
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group">
                        <label>渠道訂單號</label>
                        <div class="controls">
                            <input type="text" class="form-control"
                                   name="transaction_id" value="{{ request()->get('transaction_id') }}"
                                   placeholder="">
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group">
                        <label>方案类型</label>
                        <select class="form-control" name="type">
                            <option value="">全部</option>
                            @foreach ($type_options as $key => $value)
                                <option value="{{ $key }}" @if(request()->get('type') == $key){{'selected'}}@endif>{{ $value }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group">
                        <label>平台</label>
                        <select class="form-control" name="platform">
                            <option value="">全部</option>
                            <option value="wap" @if(request()->get('platform') == 'wap'){{'selected'}}@endif>wap</option>
                            <option value="app" @if(request()->get('platform') == 'app'){{'selected'}}@endif>app</option>
                        </select>
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group">
                        <label>建立时间</label>
                        <div class="controls">
                            <fieldset class="form-group position-relative has-icon-left">
                                <input type="text" class="form-control" id="input-created" placeholder="请选择建立时间" name="created_at" autocomplete="off" value="{{ request()->get('created_at') }}">
                                <div class="form-control-position">
                                    <i class='bx bx-calendar-check'></i>
                                </div>
                            </fieldset>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group">
                        <label>订单状态</label>
                        <select class="form-control" name="status">
                            <option value="">全部</option>
                            <option value="1" @if(request()->get('status') == 1){{'selected'}}@endif>支付成功</option>
                            <option value="2" @if(request()->get('status') == 2){{'selected'}}@endif>支付失敗</option>
                            <option value="3" @if(request()->get('status') == 3){{'selected'}}@endif>補單</option>
                        </select>
                    </div>
                </div>
                <div class="col-12 d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary">搜索</button>
                </div>
            </div>
        </div>
    </form>
@endsection

{{-- vendor scripts --}}
@section('vendor-scripts')
    <script src="{{ asset('vendors/js/extensions/moment.min.js') }}"></script>
    <script src="{{ asset('vendors/js/pickers/daterange/daterangepicker.js') }}"></script>
    <script src="{{ asset('vendors/js/extensions/locale/zh-cn.js') }}"></script>
@endsection

{{-- page scripts --}}
@section('page-scripts')
    <script>
        let $created = $('#input-created');

        // Date Ranges Initially Empty
        $created.daterangepicker({
            timePicker: true,
            timePicker24Hour: true,
            timePickerSeconds: true,
            drops: 'up',
            buttonClasses: 'btn',
            applyClass: 'btn-success',
            cancelClass: 'btn-danger',
            autoUpdateInput: false,
            locale: {
                format: 'YYYY-MM-DD HH:mm:ss'
            }
        });

        $created.on('apply.daterangepicker', function(ev, picker) {
            $(this).val(picker.startDate.format('YYYY-MM-DD HH:mm:ss') + ' - ' + picker.endDate.format('YYYY-MM-DD HH:mm:ss'));
        });

        $created.on('cancel.daterangepicker', function(ev, picker) {
            $(this).val('');
        });

	    $('#search-form').submit(function(e) {
		    e.preventDefault();

		    let url = $(this).attr('action') + '?' + $(this).serialize();
            console.log(url);

            $.reloadIFrame({
			    reloadUrl: url
            });
	    });
    </script>
@endsection

