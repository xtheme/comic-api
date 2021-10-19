@extends('layouts.iframePage')

{{-- page Title --}}
@section('title','用戶列表')

{{-- vendor style --}}
@section('vendor-styles')
    <link rel="stylesheet" type="text/css" href="{{ asset('vendors/css/pickers/daterange/daterangepicker.css') }}">
@endsection

@section('content')
    <section id="config-list">
{{--        <div class="mb-1">--}}
{{--            <a href="#" data-batch class="btn btn-primary" role="button" aria-pressed="true">批量启用</a>--}}
{{--            <a href="#" data-batch class="btn btn-danger" role="button" aria-pressed="true">批量封禁</a>--}}
{{--            <a href="{{ route('backend.user.create') }}" data-modal class="btn btn-primary" title="添加用户">添加用户</a>--}}
{{--        </div>--}}
        <div class="card">
            <div class="card-header">
                <div class="float-left">
                    <h4 class="card-title">@yield('title')</h4>
                </div>
                <div class="float-right">
                    <form id="batch-action" class="form form-vertical" method="get" action="{{ route('backend.user.batch') }}" novalidate>
                        <div class="form-body">
                            <div class="d-flex align-items-center">
                                <div class="form-group mr-1">
                                    <select class="form-control" name="action">
                                        <option value="active">启用</option>
                                        <option value="inactive">封禁</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary">批量操作</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="card-content">
                <div class="card-body">
                    <!-- Table with outer spacing -->
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                            <tr>
                                <th>
                                    <div class="checkbox">
                                        <input type="checkbox" class="checkbox-input check-all" id="check-all">
                                        <label for="check-all"></label>
                                    </div>
                                </th>
                                <th>ID</th>
                                <th>帐号</th>
                                <th>手机号</th>
                                <th class="text-center">VIP</th>
                                <th>VIP到期时间</th>
                                <th>钱包</th>
                                <th class="text-center">渠道ID</th>
                                <th class="text-center">状态</th>
                                <th class="text-center">黑名单</th>
                                <th class="text-center">注册时间</th>
                                <th class="text-center">操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($list as $user)
                                <tr>
                                    <td>
                                        <div class="checkbox">
                                            <input type="checkbox" class="checkbox-input check-opt" id="check-{{ $user->id }}" name="ids[]" value="{{ $user->id }}">
                                            <label for="check-{{ $user->id }}"></label>
                                        </div>
                                    </td>
                                    <td>{{ $user->id }}</td>
                                    <td>{{ $user->name }}</td>
                                    <td>
                                        @if($user->mobile)
                                            {{ $user->area }} {{ $user->mobile }}
                                        @else
                                            <span class="text-light">N/A</span>
                                        @endif</td>
                                    <td class="text-center">
                                        @if($user->is_vip)
                                            <span class="badge badge-pill badge-light-primary">VIP</span>
                                        @else
                                            <span class="badge badge-pill badge-light-light">普通</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($user->subscribed_until)
                                            {{ $user->subscribed_until }}
                                        @else
                                            <span class="text-light">N/A</span>
                                        @endif
                                    </td>
                                    <td>{{ $user->wallet }}</td>
                                    <td class="text-center">{{ $user->channel_id }}</td>
                                    <td class="text-center">
                                        @if(!$user->is_active)
                                            <a class="badge badge-pill badge-light-danger" data-confirm href="{{ route('backend.user.batch', ['action'=>'active', 'ids' => $user->id]) }}" title="启用">禁用</a>
                                        @else
                                            <a class="badge badge-pill badge-light-primary" data-confirm href="{{ route('backend.user.batch', ['action'=>'inactive', 'ids' => $user->id]) }}" title="禁用">正常</a>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if($user->is_ban)
                                            <a class="badge badge-pill badge-light-danger" data-confirm href="{{ route('backend.user.batch', ['action'=>'unblock', 'ids' => $user->id]) }}" title="解除黑单">黑名单</a>
                                        @else
                                            <a class="badge badge-pill badge-light-primary" data-confirm href="{{ route('backend.user.batch', ['action'=>'block', 'ids' => $user->id]) }}" title="标记黑单">正常</a>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if($user->created_at)
                                            <span data-toggle="tooltip" data-placement="top" data-original-title="{{ $user->created_at}}">
                                            {{ $user->created_at->diffForHumans() }}
                                            </span>
                                        @else
                                            <span class="text-light">N/A</span>
                                        @endif
                                    </td>
                                    <td class="text-center" @if($loop->count == 1)style="position: fixed;"@endif>
                                        <div class="@if(($loop->count - $loop->iteration) < 3){{'dropup'}}@else{{'dropdown'}}@endif">
                                            <span class="bx bx-dots-vertical-rounded font-medium-3 dropdown-toggle nav-hide-arrow cursor-pointer"
                                                  id="dropdownMenuButton{{ $user->id }}" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></span>
                                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton{{ $user->id }}">
                                                <a class="dropdown-item" data-modal href="{{ route('backend.user.edit', $user->id) }}" title="查看用户信息"><i class="bx bx-edit-alt mr-1"></i>用户信息</a>
                                                <a class="dropdown-item" data-modal href="{{ route('backend.user.order', $user->id) }}" title="订单记录"><i class="bx bxs-dollar-circle mr-1"></i>订单记录</a>
                                                <a class="dropdown-item" data-modal href="{{ route('backend.user.recharge', $user->id) }}" title="充值纪录"><i class="bx bxs-wallet mr-1"></i>充值纪录</a>
                                                <a class="dropdown-item" data-modal href="{{ route('backend.user.purchase', $user->id) }}" title="消费纪录"><i class="bx bxs-cart mr-1"></i>消费纪录</a>
                                                @if ($user->is_active)
                                                    <a class="dropdown-item" data-modal data-size="sm" data-height="20vh" href="{{ route('backend.user.gift', $user->id) }}" title="赠送用户"><i class="bx bxs-gift mr-1"></i>赠送</a>
                                                @endif
                                            </div>
                                        </div>
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
                                   name="id" value="{{ request()->get('id') }}"
                                   placeholder="">
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group">
                        <label>昵称</label>
                        <div class="controls">
                            <input type="text" class="form-control"
                                   name="name" value="{{ request()->get('name') }}"
                                   placeholder="">
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group">
                        <label>渠道ID</label>
                        <div class="controls">
                            <input type="text" class="form-control"
                                   name="channel_id" value="{{ request()->get('channel_id') }}"
                                   placeholder="">
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group">
                        <label>手机号</label>
                        <div class="controls">
                            <input type="text" class="form-control"
                                   name="mobile" value="{{ request()->get('mobile') }}"
                                   placeholder="">
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group">
                        <label>VIP</label>
                        <select class="form-control" name="subscribed">
                            <option value="">全部</option>
                            <option value="1" @if(request()->get('subscribed') == 1){{'selected'}}@endif>是</option>
                            <option value="2" @if(request()->get('subscribed') == 2){{'selected'}}@endif>否</option>
                        </select>
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group">
                        <label>状态</label>
                        <select class="form-control" name="is_active">
                            <option value="">全部</option>
                            <option value="1" @if(request()->get('is_active') == 1){{'selected'}}@endif>禁用</option>
                            <option value="2" @if(request()->get('is_active') == 2){{'selected'}}@endif>正常</option>
                        </select>
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group">
                        <label>黑名单</label>
                        <select class="form-control" name="is_ban">
                            <option value="">全部</option>
                            <option value="2" @if(request()->get('is_ban') == 2){{'selected'}}@endif>禁用</option>
                            <option value="1" @if(request()->get('is_ban') == 1){{'selected'}}@endif>正常</option>
                        </select>
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group">
                        <label for="input-date-register">注册时间</label>
                        <div class="controls">
                            <input type="text" id="input-date-register" class="form-control date-picker"
                                   name="date_register" value="{{ request()->get('date_register') }}"
                                   autocomplete="off">
                        </div>
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
        let $datePicker = $('.date-picker');

	    $datePicker.daterangepicker({
		    autoUpdateInput: false,
		    startDate: moment().subtract(7, 'days').calendar(),
		    minDate: '2019-11-15',
		    maxDate: moment().calendar()
	    });

	    $datePicker.on('apply.daterangepicker', function(ev, picker) {
		    $(this).val(picker.startDate.format(moment.localeData().longDateFormat('L')) + ' - ' + picker.endDate.format(moment.localeData().longDateFormat('L')));
	    });

	    $datePicker.on('cancel.daterangepicker', function(ev, picker) {
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

        $('#batch-action').submit(function (e) {
	        e.preventDefault();

	        let $this = $(this);
	        let ids   = $.checkedIds();
	        let url   = $this.attr('action') + '/' + $this.find('select[name="action"]').val();

	        if (!ids) {
		        $.toast({
			        type: 'error',
			        message: '请先选择要操作的数据'
		        });
		        return false;
	        }

	        $.confirm({
		        text: `请确认是否要继续批量操作?`,
		        callback: function () {
			        $.request({
				        url: url,
				        type: 'put',
				        data: {'ids': ids},
				        debug: true,
				        callback: function (res) {
					        $.reloadIFrame({
						        title: '提交成功',
						        message: '请稍后数据刷新'
					        });
				        }
			        });
		        }
	        });
        });
    </script>
@endsection

