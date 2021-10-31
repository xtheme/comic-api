@extends('layouts.iframePage')

{{-- page Title --}}
@section('title','渠道列表')

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
                                <th>渠道编号</th>
                                <th>注册数</th>
                                <th>订单数</th>
                                <th>总充值</th>
                                <th>WAP总充值</th>
                                <th>APP总充值</th>
                                <th>APP安装数</th>
                                <th class="text-center">操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($list as $item)
                                <tr>
                                    <td>{{ $item->id }}</td>
                                    <td>{{ $item->register_count }}</td>
                                    <td>{{ $item->recharge_count }}</td>
                                    <td>{{ $item->recharge_amount }}</td>
                                    <td>{{ $item->wap_amount }}</td>
                                    <td>{{ $item->app_amount }}</td>
                                    <td>{{ $item->app_download_count }}</td>
                                    <td></td>
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

