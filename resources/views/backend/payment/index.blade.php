@extends('layouts.iframePage')

{{-- page Title --}}
@section('title','支付渠道')

{{-- vendor style --}}
@section('vendor-styles')
@endsection

@section('content')
    <section id="config-list">
        <div class="mb-1">
            <a href="{{ route('backend.payment.create') }}" class="btn btn-primary glow" data-modal data-height="80vh" title="添加渠道" role="button" aria-pressed="true">添加渠道</a>
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
                                <th>渠道名稱</th>
                                <th>优先级</th>
                                <th>手續費%</th>
                                <th>每日限額</th>
                                <th>按钮文字</th>
                                <th>支付方案</th>
                                <th>SDK</th>
                                <th>状态</th>
                                <th>操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($list as $item)
                                <tr>
                                    <td>{{ $item->id }}</td>
                                    <td>{{ $item->name }}</td>
                                    <td>{{ $item->priority }}</td>
                                    <td>{{ $item->fee_percentage }}</td>
                                    <td>{{ $item->daily_recharge }} / {{ $item->daily_limit }}</td>
                                    <td>{{ $item->button_icon }}</td>
                                    <td>
                                        @foreach($item->packages as $package)
                                            <span class="badge badge-light-primary p-50">￥{{ floatval($package->price) }}</span>
                                        @endforeach
                                    </td>
                                    <td>{{ $item->sdk }}</td>
                                    <td>
                                        @if(!$item->status)
                                            <span class="badge badge-pill badge-light-danger">禁用</span>
                                        @else
                                            <span class="badge badge-pill badge-light-success">啟用</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="@if(($loop->count - $loop->iteration) < 3){{'dropup'}}@else{{'dropdown'}}@endif">
                                            <span class="bx bx-dots-vertical-rounded font-medium-3 dropdown-toggle nav-hide-arrow cursor-pointer"
                                                  id="dropdownMenuButton{{ $item->id }}" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></span>
                                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton{{ $item->id }}">
                                                <a class="dropdown-item" data-modal data-height="80vh" href="{{ route('backend.payment.edit', $item->id) }}" title="修改套餐"><i class="bx bx-edit-alt mr-1"></i> 修改</a>
                                                <a class="dropdown-item" data-destroy href="{{ route('backend.payment.destroy', $item->id) }}" title="刪除套餐"><i class="bx bx-trash mr-1"></i>刪除</a>
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


{{-- vendor scripts --}}
@section('vendor-scripts')
@endsection

{{-- page scripts --}}
@section('page-scripts')
@endsection

