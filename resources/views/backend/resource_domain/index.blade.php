@extends('layouts.contentLayout')

{{-- page Title --}}
@section('title','资源域名')

{{-- vendor style --}}
@section('vendor-styles')
@endsection

@section('content')
    <section>
        <div class="mb-1">
            <a href="{{ route('backend.resource_domain.create') }}" class="btn btn-primary" data-modal data-size="md" data-height="50vh" title="添加域名" role="button" aria-pressed="true">添加域名</a>
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
                                <th>类型</th>
                                <th>域名</th>
                                <th>备注</th>
                                <th>状态</th>
                                <th>创建时间</th>
                                <th>到期时间</th>
                                <th>操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse ($list as $item)
                                <tr>
                                    <td>{{ $item->id }}</td>
                                    <td>{{ $type_options[$item->type] }}</td>
                                    <td>{{ $item->domain }}</td>
                                    <td>{{ $item->desc }}</td>
                                    <td>{{ $status_options[$item->status] }}</td>
                                    <td>{{ $item->created_at }}</td>
                                    <td>{{ $item->expire_at }}</td>
                                    <td>
                                        <div class="@if(($loop->count - $loop->iteration) < 3){{'dropup'}}@else{{'dropdown'}}@endif">
                                            <span class="bx bx-dots-vertical-rounded font-medium-3 dropdown-toggle nav-hide-arrow cursor-pointer"
                                                  id="dropdownMenuButton{{ $item->id }}" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></span>
                                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton{{ $item->id }}">
                                                <a class="dropdown-item" data-modal data-size="md" data-height="50vh" href="{{ route('backend.resource_domain.edit', $item->id) }}" title="修改域名"><i class="bx bx-edit-alt mr-1"></i>修改</a>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center font-medium-1">暂无数据</td>
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

