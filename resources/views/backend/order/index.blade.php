@extends('layouts.iframePage')

{{-- page Title --}}
@section('title','订单列表')

{{-- vendor style --}}
@section('vendor-styles')
    <link rel="stylesheet" type="text/css" href="{{ asset('vendors/css/pickers/daterange/daterangepicker.css') }}">
@endsection

@section('content')
    <section id="config-list">
        <div class="mb-1">
            <a href="{{ route('backend.order.export', request()->input()) }}" class="btn btn-primary glow" role="button">导出表格</a>
        </div>
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">@yield('title')</h4>
            </div>
            <div class="card-content">
                <div class="card-body">
                    <div class="row bg-primary bg-lighten-5 rounded mb-2 mx-25 text-center text-lg-left">
                        <div class="col-12 col-sm-2 p-1">
                            <h6 class="text-primary mb-0">总订单数：<span class="font-medium-3 align-middle">{{ $orders_count }}</span></h6>
                        </div>
                        <div class="col-12 col-sm-2 p-1">
                            <h6 class="text-primary mb-0">总金额：<span class="font-medium-3 align-middle">{{ $orders_amount }}</span></h6>
                        </div>
                        <div class="col-12 col-sm-2 p-1">
                            <h6 class="text-primary mb-0">续费订单数：<span class="font-medium-3 align-middle">{{ $renew_orders_count }}</span></h6>
                        </div>
                        <div class="col-12 col-sm-2 p-1">
                            <h6 class="text-primary mb-0">续费总金额：<span class="font-medium-3 align-middle">{{ $renew_orders_amount }}</span></h6>
                        </div>
                    </div>
                    <!-- Table with outer spacing -->
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                            <tr>
                                <th>订单号</th>
                                <th>付款金额</th>
                                <th>套餐类型</th>
                                <th>套餐标题</th>
                                <th>用户</th>
                                <th>IP</th>
                                <th>购买次数</th>
                                <th>充值成功次数</th>
                                <th>手机系统</th>
                                <th>版本号</th>
                                <th>注册时间</th>
                                <th>订单创建时间</th>
                                <th>更新时间</th>
                                <th>状态</th>
                                <th>操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($list as $order)
                                <tr>
                                    <td>{{ $order->id }}</td>
                                    <td>{{ $order->amount }}</td>
                                    <td>{{ $order->type }}</td>
                                    <td>{{ $order->name }}</td>
                                    <td><a data-modal href="{{ route('backend.user.edit', $order->user_id) }}" title="修改用户信息">{{ $order->user_id }}</a></td>
                                    <td>{{ $order->ip }}</td>
                                    <td>{{ $order->user->orders_count->count ?? 0 }}</td>
                                    <td>{{ $order->user->orders_success_count->count ?? 0 }}</td>
                                    <td>@if($order->platform == 1)<label class="badge badge-primary badge-pill">安卓</label>@else<label class="badge badge-danger badge-pill">苹果</label>@endif</td>
                                    <td>{{ $order->app_version }}</td>
                                    <td>{{ optional($order->user->created_at)->diffForHumans() ?? '' }}</td>
                                    <td>{{ optional($order->created_at)->diffForHumans() ?? '' }}</td>
                                    <td>{{ optional($order->updated_at)->diffForHumans() ?? '' }}</td>
                                    <td>@if($order->status == 1)<label class="badge badge-success badge-pill">已付款</label>@else<label class="badge badge-light badge-pill">未付款</label>@endif</td>
                                    <td>
                                        @if($order->status != 1)
                                        <a class="btn btn-warning btn-sm" data-confirm href="{{ route('backend.order.callback', $order->id) }}" title="回调订单为已付款">回調</a>
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
                        <label>订单ID</label>
                        <div class="controls">
                            <input type="text" class="form-control"
                                   name="id" value="{{ request()->get('id') }}"
                                   placeholder="">
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group">
                        <label>手机系统</label>
                        <select class="form-control" name="platform">
                            <option value="">全部</option>
                            <option value="1" @if(request()->get('platform') == 1){{'selected'}}@endif>Android</option>
                            <option value="2" @if(request()->get('platform') == 2){{'selected'}}@endif>iOS</option>
                        </select>
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group">
                        <label>APP版本号</label>
                        <div class="controls">
                            <input type="text" class="form-control"
                                   name="app_version" value="{{ request()->get('app_version') }}"
                                   placeholder="1.3.0">
                        </div>
                    </div>
                </div>
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
                            @foreach ($status_options as $key => $val)
                                @if (request()->get('status') == $key)
                                    <option value="{{ $key }}" selected>{{ $val }}</option>
                                @else
                                    <option value="{{ $key }}">{{ $val }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-12 d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary mr-1 mb-1">搜索</button>
                    <button type="reset" class="btn btn-light-secondary mr-1 mb-1">重置</button>
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

