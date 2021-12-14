@extends('layouts.iframePage')

{{-- page Title --}}
@section('title','請求分析')

{{-- vendor style --}}
@section('vendor-styles')
    <link rel="stylesheet" type="text/css" href="{{ asset('vendors/css/pickers/pickadate/pickadate.css') }}">
@endsection

@section('content')
    <section>
        <div class="card">
            <div class="card-header">
                <div class="float-left">
                    <h4 class="card-title">@yield('title')</h4>
                </div>
                <div class="float-right">
                    <form id="search-form" class="form form-vertical" method="get" action="{{ route('backend.analysis.request_report') }}" novalidate>
                        <div class="form-body">
                            <div class="d-flex align-items-center">
                                <div class="form-group mr-1">
                                    <input type="text" name="ip" class="form-control" value="{{ request()->input('ip') }}" placeholder="IP">
                                </div>
                                <div class="form-group mr-1">
                                    <input type="text" name="date" class="form-control date-picker" value="{{ request()->input('date') }}" placeholder="选择日期">
                                </div>
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary">查询</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="card-content">
                <div class="card-body">
                    <div class="row bg-primary bg-lighten-5 rounded mb-2 mx-25 text-center text-lg-left">
                        <div class="col-12 col-sm-2 p-1">
                            <h6 class="text-primary mb-0">总请求数：<span class="font-medium-3 align-middle">{{ $total_request }}</span></h6>
                        </div>
                        <div class="col-12 col-sm-2 p-1">
                            <h6 class="text-primary mb-0">平均耗时：<span class="font-medium-3 align-middle">{{ $avg_request_time }}</span></h6>
                        </div>
                        <div class="col-12 col-sm-2 p-1">
                            <h6 class="text-primary mb-0">最高耗时：<span class="font-medium-3 align-middle">{{ $max_request_time }}</span></h6>
                        </div>
                        <div class="col-12 col-sm-2 p-1">
                            <h6 class="text-primary mb-0">最低耗时：<span class="font-medium-3 align-middle">{{ $min_request_time }}</span></h6>
                        </div>
                        <div class="col-12 col-sm-2 p-1">
                            <h6 class="text-primary mb-0">不重复IP数：<span class="font-medium-3 align-middle">{{ $ip_count }}</span></h6>
                        </div>
                    </div>
                    <!-- Table with outer spacing -->
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                            <tr>
                                <th>IP</th>
                                <th>站點</th>
                                <th>接口</th>
                                <th>耗時(秒)</th>
                                <th>請求时间</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse ($slow_logs as $log)
                                <tr>
                                    <td>{{ $log->ip }}</td>
                                    <td>{{ $log->host }}</td>
                                    <td>{{ $log->path }}</td>
                                    <td>{{ $log->times }}</td>
                                    <td>{{ $log->created_at }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center font-medium-1">没有数据，请选择其他日期</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="row">
                        <div class="col-md-6">总共 <b>{{ $slow_logs->appends(request()->input())->total() }}</b> 条, 分为 <b>{{ $slow_logs->lastPage() }}</b> 页</div>
                        <div class="col-md-6">{!! $slow_logs->links() !!}</div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('search-form')
@endsection

{{-- vendor scripts --}}
@section('vendor-scripts')
    <script src="{{ asset('vendors/js/pickers/pickadate/picker.js') }}"></script>
    <script src="{{ asset('vendors/js/pickers/pickadate/picker.date.js') }}"></script>
@endsection

{{-- page scripts --}}
@section('page-scripts')
    <script>
        $(document).ready(function () {
            $('.date-picker').pickadate({
                firstDay: 1,
                format: 'yyyy-mm-dd',
                monthsFull: ['一月', '二月', '三月', '四月', '五月', '六月', '七月', '八月', '九月', '十月', '十一月', '十二月'],
                monthsShort: ['一', '二', '三', '四', '五', '六', '七', '八', '九', '十', '十一', '十二'],
                weekdaysShort: ['日', '一', '二', '三', '四', '五', '六'],
                today: '今天',
                clear: '清除',
                close: '关闭'
            });

            $('#search-form').submit(function (e) {
                e.preventDefault();

                let url = $(this).attr('action') + '?' + $(this).serialize();
                console.log(url);
                $.reloadIFrame({
                    reloadUrl: url
                });
            });
        });
    </script>
@endsection

