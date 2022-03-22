@extends('layouts.contentLayout')

{{-- page Title --}}
@section('title','操作日志')

@section('vendor-styles')
    <link rel="stylesheet" type="text/css" href="{{ asset('vendors/css/pickers/daterange/daterangepicker.css') }}">
@endsection

@section('content')
    <section id="config-list home-fill">
        <div class="card">
            <div class="card-header">
                <div class="float-left">
                    <h4 class="card-title">@yield('title')</h4>
                </div>
                <div class="float-right d-flex flex-wrap">
                    <form id="search-form" class="form form-horizontal" method="get" action="{{ url()->current() }}" novalidate>
                        <div class="form-body">
                            <div class="d-flex align-items-center">
                                <div class="form-group mr-1">
                                    <div class="controls">
                                        <select class="form-control" id="select-causer" name="causer_id">
                                            <option value="">全部</option>
                                            @foreach ($admin_options as $admin)
                                                @if (request()->get('causer_id') == $admin->id)
                                                    <option value="{{ $admin->id }}" selected>{{ $admin->nickname }}</option>
                                                @else
                                                    <option value="{{ $admin->id }}">{{ $admin->nickname }}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group mr-1">
                                    <div class="controls">
                                        <input type="text" class="form-control" name="id" placeholder="查询ID" value="{{ request()->get('id') }}">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary">搜索</button>
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
                                <th>ID</th>
                                <th>日志类别</th>
                                <th>操作行为</th>
                                <th>操作者</th>
                                <th>日志时间</th>
                                <th>操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($logs as $row)
                                <tr>
                                    <td>{{ $row->id }}</td>
                                    <td>{{ $row->log_name }}</td>
                                    <td>
                                        @if($row->subject)
                                            <span class="badge badge-pill badge-light-primary">#{{ $row->subject_id }}</span>
                                        @endif
                                        {{ $row->description }}
                                    </td>
                                    <td>{{ $row->causer->nickname ?? '' }}</td>
                                    <td>{{ $row->created_at->diffForHumans() }}</td>
                                    <td>
                                        @if($row->subject_id)
                                            <a class="btn btn-primary btn-sm" data-modal href="{{ route('backend.activity.diff', $row->id) }}" title="查看变更">查看变更</a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="row">
                        <div class="col-md-6">总共 <b>{{ $logs->appends(request()->input())->total() }}</b> 条, 分为 <b>{{ $logs->lastPage() }}</b> 页</div>
                        <div class="col-md-6">{!! $logs->links() !!}</div>
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
                        <label for="input-id">ID</label>
                        <div class="controls">
                            <input type="text" id="input-id" class="form-control" name="id" placeholder="查询ID" value="{{ request()->get('id') }}">
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group">
                        <label for="input-name">日志类别</label>
                        <div class="controls">
                            <select class="form-control" id="input-name" name="log_name">
                                <option value="">全部</option>
                                @foreach ($name_options as $log_name)
                                    @if (request()->get('log_name') == $log_name)
                                        <option value="{{ $log_name }}" selected>{{ $log_name }}</option>
                                    @else
                                        <option value="{{ $log_name }}">{{ $log_name }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group">
                        <label for="select-causer">操作者</label>
                        <div class="controls">
                            <select class="form-control" id="select-causer" name="causer_id">
                                <option value="">全部</option>
                                @foreach ($admin_options as $admin)
                                    @if (request()->get('causer_id') == $admin->id)
                                        <option value="{{ $admin->id }}" selected>{{ $admin->nickname }}</option>
                                    @else
                                        <option value="{{ $admin->id }}">{{ $admin->nickname }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group">
                        <label for="input-created">操作时间</label>
                        <div class="controls">
                            <fieldset class="form-group position-relative has-icon-left">
                                <input type="text" id="input-created" class="form-control" name="created_at" placeholder="请选择操作时间" autocomplete="off" value="{{ request()->get('created_at') }}">
                                <div class="form-control-position">
                                    <i class='bx bx-calendar-check'></i>
                                </div>
                            </fieldset>
                        </div>
                    </div>
                </div>
                <div class="col-12 d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary">提交</button>
                </div>
            </div>
        </div>
    </form>
@endsection

@section('vendor-scripts')
    <script src="{{ asset('vendors/js/extensions/moment.min.js') }}"></script>
    <script src="{{ asset('vendors/js/pickers/daterange/daterangepicker.js') }}"></script>
    <script src="{{ asset('vendors/js/extensions/locale/zh-cn.js') }}"></script>
@endsection

{{-- page scripts --}}
@section('page-scripts')
    <script>
        $(document).ready(function () {
            let $created = $('#input-created');

            // Date Ranges Initially Empty
            $created.daterangepicker({
                timePicker: true,
                timePicker24Hour: true,
                timePickerSeconds: true,
                drops: "up",
                buttonClasses: "btn",
                applyClass: "btn-success",
                cancelClass: "btn-danger",
                autoUpdateInput: false,
                locale: {
                    format: 'YYYY-MM-DD HH:mm:ss'
                }
            });

            $created.on('apply.daterangepicker', function (ev, picker) {
                $(this).val(picker.startDate.format('YYYY-MM-DD HH:mm:ss') + ' - ' + picker.endDate.format('YYYY-MM-DD HH:mm:ss'));
            });

            $created.on('cancel.daterangepicker', function (ev, picker) {
                $(this).val('');
            });

            $('#search-form').submit(function (e) {
                e.preventDefault();

                let url = $(this).attr('action') + '?' + $(this).serialize();

                $.reloadIFrame({
                    reloadUrl: url
                });
            });
        });
    </script>
@endsection
