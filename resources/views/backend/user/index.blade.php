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
                                        <option value="enable">启用</option>
                                        <option value="disable">封禁</option>
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
                                <th>昵称</th>
                                <th class="text-center">帐号类型</th>
                                <th class="text-center">VIP</th>
                                <th class="text-center">VIP到期时间</th>
                                <th class="text-center">版本号</th>
                                <th class="text-center">平台</th>
                                <th class="text-center">性别</th>
                                <th class="text-center">积分</th>
{{--                                <th>手机号/UUID</th>--}}
                                <th>状态</th>
                                <th>注册时间</th>
                                <th>最近登陆</th>
                                <th>操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($list as $item)
                                <tr>
                                    <td>
                                        <div class="checkbox">
                                            <input type="checkbox" class="checkbox-input check-opt" id="check-{{ $item->id }}" name="ids[]" value="{{ $item->id }}">
                                            <label for="check-{{ $item->id }}"></label>
                                        </div>
                                    </td>
                                    <td>{{ $item->id }}</td>
                                    <td>{{ $item->username }}</td>
                                    <td class="text-center">
                                        @if($item->mobile)
                                            <span data-toggle="tooltip" data-placement="top" data-original-title="{{ $item->phone }}">
                                                <span class="badge badge-pill badge-light-primary">电话</span>
                                            </span>
                                        @else
                                            <span data-toggle="tooltip" data-placement="top" data-original-title="{{ $item->device_id }}">
                                                <span class="badge badge-pill badge-light-light">设备</span>
                                            </span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if($item->subscribed_status)
                                            <span class="badge badge-pill badge-light-primary">VIP</span>
                                        @else
                                            <span class="badge badge-pill badge-light-light">普通</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if($item->subscribed_at)
                                            <span data-toggle="tooltip" data-placement="top" data-original-title="{{ $item->subscribed_at }}">
                                            {{ $item->subscribed_at->diffForHumans() }}
                                            </span>
                                        @else
                                            <span class="text-light">N/A</span>
                                        @endif
                                    </td>
                                    <td class="text-center">{{ $item->version }}</td>
                                    <td class="text-center">
                                        @if($item->platform == 1)
                                            安卓
                                        @else
                                            iOS
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @switch($item->sex)
                                            @case(1)
                                            男
                                            @break
                                            @case(2)
                                            女
                                            @break
                                            @default
                                            未知
                                            @break
                                        @endswitch
                                    </td>
                                    <td class="text-right">{{ $item->score }}</td>
                                    {{--<td>
                                        @if(!$item->mobile)
                                            {{ $item->device_id }}
                                        @else
                                            {{ $item->phone }}
                                        @endif
                                    </td>--}}
                                    <td>
                                        @if(!$item->status)
                                            <span class="badge badge-pill badge-light-danger">禁用</span>
                                        @else
                                            <span class="badge badge-pill badge-light-primary">正常</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($item->created_at)
                                            <span data-toggle="tooltip" data-placement="top" data-original-title="{{ $item->created_at}}">
                                            {{ $item->created_at->diffForHumans() }}
                                            </span>
                                        @else
                                            <span class="text-light">N/A</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($item->last_login_at)
                                            <span data-toggle="tooltip" data-placement="top" data-original-title="{{ $item->last_login_at}}">
                                            {{ $item->last_login_at->diffForHumans() }}
                                            </span>
                                        @else
                                            <span class="text-light">N/A</span>
                                        @endif
                                    </td>
                                    <td @if($loop->count == 1)style="position: fixed;"@endif>
                                        <div class="@if(($loop->count - $loop->iteration) < 3){{'dropup'}}@else{{'dropdown'}}@endif">
                                            <span class="bx bx-dots-vertical-rounded font-medium-3 dropdown-toggle nav-hide-arrow cursor-pointer"
                                                  id="dropdownMenuButton{{ $item->id }}" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></span>
                                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton{{ $item->id }}">
                                                <a class="dropdown-item" data-modal href="{{ route('backend.user.edit', $item->id) }}" title="修改用户信息"><i class="bx bx-edit-alt mr-1"></i>修改</a>
                                                <a class="dropdown-item" href="{{ route('backend.order.index') }}?user_id={{ $item->id }}" target="_blank"><i class="bx bxs-cart mr-1"></i>查看订单</a>
                                                @if ($item->status == '1')
                                                    <a class="dropdown-item" data-modal data-size="sm" data-height="10vh" href="{{ route('backend.user.edit.vip', $item->id) }}" title="开通 VIP"><i class="bx bxs-gift mr-1"></i>开通 VIP</a>
                                                    @if($item->subscribed_status)
                                                    <a class="dropdown-item" data-modal data-size="sm" data-height="20vh" href="{{ route('backend.user.transfer.vip', $item->id) }}" title="转让 VIP"><i class="bx bx-transfer mr-1"></i>转让 VIP</a>
                                                    @endif
                                                    <a class="dropdown-item" data-confirm href="{{ route('backend.user.block', $item->id) }}" title="封禁该账号"><i class="bx bx-lock mr-1"></i>封禁</a>
                                                @else
                                                    <a class="dropdown-item" data-confirm href="{{ route('backend.user.block', $item->id) }}" title="启用该账号"><i class="bx bx-lock-open mr-1"></i>启用</a>
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
                                   name="nickname" value="{{ request()->get('nickname') }}"
                                   placeholder="">
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group">
                        <label>装置 UUID</label>
                        <div class="controls">
                            <input type="text" class="form-control"
                                   name="uuid" value="{{ request()->get('uuid') }}"
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
                            <option value="2" @if(request()->get('status') == 2){{'selected'}}@endif>是</option>
                            <option value="1" @if(request()->get('subscribed') == 1){{'selected'}}@endif>否</option>
                        </select>
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group">
                        <label>状态</label>
                        <select class="form-control" name="status">
                            <option value="">全部</option>
                            <option value="1" @if(request()->get('status') == 1){{'selected'}}@endif>禁用</option>
                            <option value="2" @if(request()->get('status') == 2){{'selected'}}@endif>正常</option>
                        </select>
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group">
                        <label>版本号</label>
                        <div class="controls">
                            <input type="text" class="form-control"
                                   name="version" value="{{ request()->get('version') }}"
                                   placeholder="1.3.0">
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group">
                        <label>平台类型</label>
                        <select class="form-control" name="platform">
                            <option value="">全部</option>
                            <option value="1" @if(request()->get('platform') == 1){{'selected'}}@endif>安卓</option>
                            <option value="2" @if(request()->get('platform') == 2){{'selected'}}@endif>iOS</option>
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

