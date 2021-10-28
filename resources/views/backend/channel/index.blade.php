@extends('layouts.iframePage')

{{-- page Title --}}
@section('title','渠道统计')

{{-- vendor style --}}
@section('vendor-styles')
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
                                        <p class="text-right clearfix"><span class="float-left badge badge-light-warning">APP</span>+ {{ $item->register_app_count }}</p>
                                        <hr>
                                        <p class="text-right">= {{ $item->register_count }}</p>
                                    </td>
                                    <td>
                                        <p class="text-right"><span class="float-left badge badge-light-primary">WAP</span> {{ $item->recharge_wap_count }}</p>
                                        <p class="text-right clearfix"><span class="float-left badge badge-light-warning">APP</span>+ {{ $item->recharge_app_count }}</p>
                                        <hr>
                                        <p class="text-right">= {{ $item->recharge_count }}</p>
                                    </td>
                                    <td>
                                        <p class="text-right"><span class="float-left badge badge-light-primary">WAP</span> {{ $item->recharge_wap_amount }}</p>
                                        <p class="text-right clearfix"><span class="float-left badge badge-light-warning">APP</span>+ {{ $item->recharge_app_amount }}</p>
                                        <hr>
                                        <p class="text-right">= {{ $item->recharge_amount }}</p>
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group-vertical">
                                            <a class="btn btn-light-primary">日报表</a>
                                            <a class="btn btn-light-warning">月报表</a>
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
@endsection

{{-- page scripts --}}
@section('page-scripts')
@endsection
