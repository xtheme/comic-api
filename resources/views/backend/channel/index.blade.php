@extends('layouts.contentLayout')

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
                                <th class="text-center">渠道编号</th>
                                <th>推广连结</th>
                                <th>注册数</th>
                                <th>订单数</th>
                                <th>总充值</th>
                                <th>WAP总充值</th>
                                <th>APP总充值</th>
                                <th>APP安装数</th>
                                <th>操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($list as $item)
                                <tr>
                                    <td>
                                        <p class="text-center font-medium-1">{{ $item->id }}</p>
                                        <p class="text-center">{{ $item->description }}</p>
                                        @if($item->safe_landing)
                                            <p class="text-center"><span class="badge badge-pill badge-light-danger">安全落地頁</span></p>
                                        @endif
                                    </td>
                                    <td>
                                        <ul class="list-unstyled">
                                            @foreach ($domains as $domain)
                                                <li class="d-flex justify-content-start">
                                                    <div class="d-inline p-50"><a data-modal data-size="sm" data-height="26vh" href="{{ route('backend.qrcode') }}?url={{ $domain->domain }}?ch={{ $item->id }}" title="QRCode"><i class="bx bx-barcode"></i></a></div>
                                                    <div class="d-inline p-50">{{ $domain->domain }}?ch={{ $item->id }}</div>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </td>
                                    <td>{{ $item->register_count }}</td>
                                    <td>{{ $item->recharge_count }}</td>
                                    <td>{{ $item->recharge_amount }}</td>
                                    <td>{{ $item->wap_amount }}</td>
                                    <td>{{ $item->app_amount }}</td>
                                    <td>{{ $item->app_download_count }}</td>
                                    <td>
                                        <div class="@if(($loop->count - $loop->iteration) < 3){{'dropup'}}@else{{'dropdown'}}@endif">
                                            <span class="bx bx-dots-vertical-rounded font-medium-3 dropdown-toggle nav-hide-arrow cursor-pointer"
                                                  id="dropdownMenuButton{{ $item->id }}" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></span>
                                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton{{ $item->id }}">
                                                <a class="dropdown-item" data-modal data-size="md" data-height="30vh" href="{{ route('backend.channel.edit', $item->id) }}" title="修改渠道"><i class="bx bx-edit-alt mr-1"></i>修改</a>
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
@endsection

{{-- vendor scripts --}}
@section('vendor-scripts')
@endsection

{{-- page scripts --}}
@section('page-scripts')
@endsection

