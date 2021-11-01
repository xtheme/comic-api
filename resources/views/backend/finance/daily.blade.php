@extends('layouts.iframePage')

{{-- page Title --}}
@section('title','每日汇总')

{{-- vendor style --}}
@section('vendor-styles')
@endsection

@section('content')
    <section>
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
                                <th>日期</th>
                                <th>总充值</th>
                                <th>WAP总充值</th>
                                <th>APP总充值</th>
                                <th>新用户总充值</th>
                                <th>WAP新户充值</th>
                                <th>APP新户充值</th>
                                <th>老用户充值</th>
                                <th>APP安装数</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse ($list as $item)
                                <tr>
                                    <td>{{ $item->date }}</td>
                                    <td>{{ $item->recharge_amount }}</td>
                                    <td>{{ $item->wap_amount }}</td>
                                    <td>{{ $item->app_amount }}</td>
                                    <td>{{ $item->new_amount}}</td>
                                    <td>{{ $item->wap_new_amount }}</td>
                                    <td>{{ $item->app_new_amount }}</td>
                                    <td>{{ $item->renew_amount }}</td>
                                    <td>{{ $item->app_download_count }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="11" class="text-center font-medium-1">没有数据，请选择其他日期</td>
                                </tr>
                            @endforelse
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

