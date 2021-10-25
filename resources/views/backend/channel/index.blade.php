@extends('layouts.iframePage')

{{-- page Title --}}
@section('title','渠道统计')

{{-- vendor style --}}
@section('vendor-styles')
    <link rel="stylesheet" type="text/css" href="{{ asset('vendors/css/x-editable/bootstrap-editable.css') }}">
@endsection

@section('content')
    <section>
        <div class="mb-1">
            <a href="{{ route('backend.channel.create') }}" class="btn btn-primary" data-modal data-size="sm" data-height="20vh" title="添加渠道" role="button" aria-pressed="true">添加渠道</a>
        </div>
        <div class="card">
            <div class="card-header">
                <div class="float-left">
                    <h4 class="card-title">@yield('title')</h4>
                </div>
            </div>
            <div class="card-content">
                <div class="card-body">
                    <!-- Table with outer spacing -->
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                            <tr>
                                <th>渠道ID</th>
                                <th>注册总数</th>
                                <th>充值总数</th>
                                <th>充值金额</th>
                                <th class="text-center">操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($list as $item)
                                <tr>
                                    <td class="text-center">
                                        <p class="font-medium-3 text-bold-700">{{ $item->id }}</p>
                                        <p>{{ $item->description }}</p>
                                        @if($item->safe_landing)<span class="badge badge-light-danger">安全落地頁</span>@endif
                                    </td>
                                    <td>
                                        <p class="text-right"><span class="float-left badge badge-light-primary">WAP</span> {{ $item->register_wap_count }}</p>
                                        <p class="text-right clearfix"><span class="float-left badge badge-light-secondary">APP</span>+ {{ $item->register_app_count }}</p>
                                        <hr>
                                        <p class="text-right">= {{ $item->register_count }}</p>
                                    </td>
                                    <td>
                                        <p class="text-right"><span class="float-left badge badge-light-primary">WAP</span> {{ $item->recharge_wap_count }}</p>
                                        <p class="text-right clearfix"><span class="float-left badge badge-light-secondary">APP</span>+ {{ $item->recharge_app_count }}</p>
                                        <hr>
                                        <p class="text-right">= {{ $item->recharge_count }}</p>
                                    </td>
                                    <td>
                                        <p class="text-right"><span class="float-left badge badge-light-primary">WAP</span> {{ $item->recharge_wap_amount }}</p>
                                        <p class="text-right clearfix"><span class="float-left badge badge-light-secondary">APP</span>+ {{ $item->recharge_app_amount }}</p>
                                        <hr>
                                        <p class="text-right">= {{ $item->recharge_amount }}</p>
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group-vertical">
                                            <a class="btn btn-light-primary">日报表</a>
                                            <a class="btn btn-light-success">月报表</a>
                                            <a class="btn btn-light-danger">数据校正</a>
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
@endsection

{{-- vendor scripts --}}
@section('vendor-scripts')
    <script src="{{ asset('vendors/js/x-editable/bootstrap-editable.js') }}"></script>
@endsection

{{-- page scripts --}}
@section('page-scripts')
    <script>
        $(document).ready(function () {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            });
            $.fn.editable.defaults.ajaxOptions = {type: 'PUT'};
            $.fn.editableform.buttons = '<button type="submit" class="btn btn-primary editable-submit">确认</button>';

            $('.editable-click').editable({
                inputclass: 'form-control',
                emptyclass: 'text-light',
                emptytext: 'N/A',
                success: function (res, newValue) {
                    console.log(res);
                    $.toast({
                        title: '提交成功',
                        message: res.msg
                    });
                }
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
                            data: {'ids' : ids},
                            debug   : true,
                            callback: function (res) {
                                $.reloadIFrame({
                                    title  : '提交成功',
                                    message: '请稍后数据刷新'
                                });
                            }
                        });
                    }
                });
            });

            $('#search-form').submit(function(e) {
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

