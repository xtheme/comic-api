@extends('layouts.iframePage')

{{-- page Title --}}
@section('title','定點管理')

{{-- vendor style --}}
@section('vendor-styles')
    <link rel="stylesheet" type="text/css" href="{{ asset('vendors/css/pickers/daterange/daterangepicker.css') }}">
@endsection

@section('content')
    <section id="config-list">
        <div class="mb-1">
            <a href="{{ route('backend.location.create') }}" data-modal class="btn btn-primary glow" title="新增地點資訊">新增地點</a>
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
                                <th>ID</th>
                                <th>地點</th>
                                <th>電話</th>
                                <th>Email</th>
                                <th>地址</th>
                                {{--                                <th>描述</th>--}}
                                <th>排序</th>
                                <th>更新時間</th>
                                <th>操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($list as $item)
                                <tr>
                                    <td>{{ $item->id }}</td>
                                    <td>{{ $item->name }}</td>
                                    <td>{{ $item->phone }}</td>
                                    <td>{{ $item->email }}</td>
                                    <td>{{ $item->address }}</td>
                                    {{--                                    <td>{{ $item->description }}</td>--}}
                                    <td>{{ $item->sort }}</td>
                                    <td>@if($item->updated_at){{ $item->updated_at->diffForHumans() }}@else<span class="text-light">N/A</span>@endif</td>
                                    <td @if($loop->count == 1)style="position: fixed;"@endif>
                                        <div class="@if(($loop->count - $loop->iteration) < 3){{'dropup'}}@else{{'dropdown'}}@endif">
                                            <span class="bx bx-dots-vertical-rounded font-medium-3 dropdown-toggle nav-hide-arrow cursor-pointer"
                                                  id="dropdownMenuButton{{ $item->id }}" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></span>
                                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton{{ $item->id }}">
                                                <a class="dropdown-item" data-modal href="{{ route('backend.location.edit', $item->id) }}" title="修改地點資訊"><i class="bx bx-edit-alt mr-1"></i>修改</a>
                                                <a class="dropdown-item" data-destroy href="{{ route('backend.location.destroy', $item->id) }}" title="刪除地點資訊"><i class="bx bx-edit-alt mr-1"></i>刪除</a>
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
                        <select class="form-control" id="select-status" name="status">
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
    <script src="{{ asset('vendors/js/extensions/locale-all.js') }}"></script>
    <script src="{{ asset('vendors/js/pickers/daterange/daterangepicker.js') }}"></script>
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
            parent.$.reloadIFrame({
                reloadUrl: url
            });
        });
    </script>
@endsection

