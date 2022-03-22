@extends('layouts.contentLayout')

{{-- page Title --}}
@section('title','用戶列表')

{{-- vendor style --}}
@section('vendor-styles')
    <link rel="stylesheet" type="text/css" href="{{ asset('vendors/css/pickers/daterange/daterangepicker.css') }}">
@endsection

@section('content')
    <section id="config-list">
        <div class="mb-1">
            <a href="#" data-batch class="btn btn-primary" role="button" aria-pressed="true">批量启用</a>
            <a href="#" data-batch class="btn btn-danger" role="button" aria-pressed="true">批量封禁</a>
            <a href="#" data-modal class="btn btn-success" role="button" aria-pressed="true">添加用户</a>
        </div>
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">@yield('title')</h4>
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
                                <th>用户类型</th>
                                <th>昵称</th>
                                <th>版本号</th>
                                <th class="text-center">平台</th>
                                {{--                                <th class="text-center">头像</th>--}}
                                <th class="text-center">性别</th>
                                <th class="text-center">金币</th>
                                <th>手机号</th>
                                <th>VIP</th>
                                <th class="text-center">VIP到期时间</th>
                                <th>状态</th>
                                <th>注册时间</th>
                                <th>最后登陆时间</th>
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
                                    <td>{{ $item->account_type }}</td>
                                    <td>{{ $item->username }}</td>
                                    <td>{{ $item->version }}</td>
                                    <td class="text-center">{!! $item->os !!}</td>
                                    {{--                                    <td class="text-center">头像</td>--}}
                                    <td class="text-center">{!! $item->gender !!}</td>
                                    <td class="text-right">{{ $item->score }}</td>
                                    <td>{{ $item->phone }}</td>
                                    <td>@if($item->is_vip)<span class="text-success">VIP</span>@else<span class="text-muted">普通</span>@endif</td>
                                    <td class="text-center">@if($item->subscribed_until){{ $item->subscribed_until->diffForHumans() }}@else<span class="text-light">N/A</span>@endif</td>
                                    <td>{!! $item->identity !!}</td>
                                    <td>{{ $item->created_at->diffForHumans() }}</td>
                                    <td>@if($item->last_login_at){{ $item->last_login_at->diffForHumans() }}@else<span class="text-light">N/A</span>@endif</td>
                                    <td>
                                        <div class="@if(($loop->count - $loop->iteration) < 3){{'dropup'}}@else{{'dropdown'}}@endif">
                                            <span class="bx bx-dots-vertical-rounded font-medium-3 dropdown-toggle nav-hide-arrow cursor-pointer"
                                                  id="dropdownMenuButton{{ $item->id }}" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></span>
                                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton{{ $item->id }}">
                                                <a class="dropdown-item" data-modal href="{{ route('backend.user.edit', $item->id) }}" title="修改用户信息"><i class="bx bx-edit-alt mr-1"></i>修改</a>
                                                @if ($item->status == '1')
                                                    <a class="dropdown-item" data-confirm href="{{ route('backend.user.block', $item->id) }}" title="封禁该帐号"><i class="bx bx-lock mr-1"></i>封禁</a>
                                                @else
                                                    <a class="dropdown-item" data-confirm href="{{ route('backend.user.block', $item->id) }}" title="启用该帐号"><i class="bx bx-lock-open mr-1"></i>启用</a>
                                                @endif
                                                {{--                                                @if (!$item->deleted_at)--}}
                                                {{--                                                    @if ($item->status == '1')--}}
                                                {{--                                                        <a class="dropdown-item" data-confirm href="{{ route('rbac.user.block', $item->id) }}" title="封禁该帐号"><i class="bx bx-lock mr-1"></i>封禁</a>--}}
                                                {{--                                                    @else--}}
                                                {{--                                                        <a class="dropdown-item" data-confirm href="{{ route('rbac.user.block', $item->id) }}" title="启用该帐号"><i class="bx bx-lock-open mr-1"></i>启用</a>--}}
                                                {{--                                                    @endif--}}
                                                {{--                                                    <a class="dropdown-item" data-modal data-size="full" data-height="70vh" href="{{ route('rbac.user.devices', $item->id) }}" title="用户设备列表"><i class="bx bx-mobile mr-1"></i>设备</a>--}}
                                                {{--                                                    <a class="dropdown-item" data-modal href="{{ route('rbac.user.edit', $item->id) }}" title="修改用户信息"><i class="bx bx-edit-alt mr-1"></i>修改</a>--}}
                                                {{--                                                    @if ($item->status != '3')--}}
                                                {{--                                                        <a class="dropdown-item" data-confirm href="{{ route('rbac.user.destroy', $item->id) }}" title="删除该用户"><i class="bx bxs-user-x mr-1"></i>删除</a>--}}
                                                {{--                                                    @endif--}}
                                                {{--                                                @else--}}
                                                {{--                                                    <a class="dropdown-item" data-confirm href="{{ route('rbac.user.restore', $item->id) }}" title="复权该用户"><i class="bx bxs-user-check mr-1"></i>恢复</a>--}}
                                                {{--                                                @endif--}}
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
                        <label for="input-id">用户ID</label>
                        <div class="controls">
                            <input type="text" id="input-id" class="form-control"
                                   name="id" value="{{ request()->get('id') }}"
                                   placeholder="">
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group">
                        <label for="input-nickname">昵称</label>
                        <div class="controls">
                            <input type="text" id="input-nickname" class="form-control"
                                   name="nickname" value="{{ request()->get('nickname') }}"
                                   placeholder="">
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group">
                        <label for="input-mobile">手机号</label>
                        <div class="controls">
                            <input type="text" id="input-mobile" class="form-control"
                                   name="mobile" value="{{ request()->get('mobile') }}"
                                   placeholder="">
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group">
                        <label for="select-status">状态</label>
                        <select class="form-control" name="status">
                            <option value="">全部</option>
                            {{--                            @foreach ($status_options as $key => $val)--}}
                            {{--                                @if (request()->get('status') == $key)--}}
                            {{--                                    <option value="{{ $key }}" selected>{{ $val }}</option>--}}
                            {{--                                @else--}}
                            {{--                                    <option value="{{ $key }}">{{ $val }}</option>--}}
                            {{--                                @endif--}}
                            {{--                            @endforeach--}}
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
                <div class="col-12">
                    <div class="form-group">
                        <label for="input-date-login">最后登录时间</label>
                        <div class="controls">
                            <input type="text" id="input-date-login" class="form-control date-picker"
                                   name="date_login" value="{{ request()->get('date_login') }}"
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

        $datePicker.on('apply.daterangepicker', function (ev, picker) {
            $(this).val(picker.startDate.format(moment.localeData().longDateFormat('L')) + ' - ' + picker.endDate.format(moment.localeData().longDateFormat('L')));
        });

        $datePicker.on('cancel.daterangepicker', function (ev, picker) {
            $(this).val('');
        });

        $('#search-form').submit(function (e) {
            e.preventDefault();

            let url = $(this).attr('action') + '?' + $(this).serialize();
            console.log(url);
            $.reloadIFrame({
                reloadUrl: url
            });
        });
    </script>
@endsection

