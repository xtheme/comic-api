@extends('layouts.iframePage')

{{-- page Title --}}
@section('title','支付方案')

{{-- vendor style --}}
@section('vendor-styles')
@endsection

@section('content')
    <section id="config-list">
        <div class="mb-1">
            <a href="{{ route('backend.pricing.create') }}" class="btn btn-primary glow" data-modal title="添加方案" role="button" aria-pressed="true">添加方案</a>
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
                                <th>方案类型</th>
                                <th>方案名称</th>
                                <th>方案描述</th>
                                <th>标签</th>
                                <th>充值金额</th>
                                <th>VIP天数</th>
                                <th>金币</th>
                                <th>目标客群</th>
                                <th>状态</th>
                                <th>排序</th>
                                <th>操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($list as $item)
                                <tr>
                                    <td>{{ $item->id }}</td>
                                    <td>
                                        @if($item->type == 'charge')充值金币@endif
                                        @if($item->type == 'vip')VIP方案@endif
                                    </td>
                                    <td>{{ $item->name }}</td>
                                    <td>{{ $item->description }}</td>
                                    <td>{{ $item->label }}</td>
                                    <td>@if($item->list_price)<del>{{ $item->list_price }}</del> @endif<span class="text-primary">{{ $item->price }}</span></td>
                                    <td>{{ $item->days }}@if($item->gift_days) (+{{ $item->gift_days }})@endif</td>
                                    <td>{{ $item->coin }}@if($item->gift_coin) (+{{ $item->gift_coin }})@endif</td>
                                    <td>
                                        @if($item->target == 0)全用户@endif
                                        @if($item->target == 1)首存用戶@endif
                                        @if($item->target == 2)续约用户@endif
                                    </td>
                                    <td>
                                        @if(!$item->status)
                                            <span class="badge badge-pill badge-light-danger">禁用</span>
                                        @else
                                            <span class="badge badge-pill badge-light-primary">啟用</span>
                                        @endif
                                    </td>
                                    <td>{{ $item->sort }}</td>
                                    <td @if($loop->count == 1)style="position: fixed;"@endif>
                                        <div class="@if(($loop->count - $loop->iteration) < 3){{'dropup'}}@else{{'dropdown'}}@endif">
                                            <span class="bx bx-dots-vertical-rounded font-medium-3 dropdown-toggle nav-hide-arrow cursor-pointer"
                                                  id="dropdownMenuButton{{ $item->id }}" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></span>
                                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton{{ $item->id }}">
                                                <a class="dropdown-item" data-modal href="{{ route('backend.pricing.edit', $item->id) }}" title="修改套餐"><i class="bx bx-edit-alt mr-1"></i> 修改</a>
                                                <a class="dropdown-item" data-destroy href="{{ route('backend.pricing.destroy', $item->id) }}" title="刪除套餐"><i class="bx bx-trash mr-1"></i>刪除</a>
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

