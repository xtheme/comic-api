@extends('layouts.iframePage')

{{-- page Title --}}
@section('title','导航推荐')

{{-- vendor style --}}
@section('vendor-styles')
@endsection

@section('content')
    <section id="config-list">
        <div class="mb-1">
            <a href="{{ route('backend.navigation.create') }}" class="btn btn-primary glow" data-modal title="添加导航" role="button" aria-pressed="true">添加导航</a>
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
                                <th>编号</th>
                                <th>导航名称</th>
                                <th>排序</th>
                                <th>图片</th>
                                <th>跳转类型</th>
                                <th>链接</th>
                                <th>跳转方式</th>
                                <th>状态</th>
                                <th>操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($list as $item)
                                <tr>
                                    <td>{{ $item->id }}</td>
                                    <td>{{ $item->title }}</td>
                                    <td>{{ $item->sort }}</td>
                                    <td><img src="{{ $item->icon }}" class="rounded-circle" width="60" height="60"></td>
                                    <td>{{ $item->type }}</td>
                                    <td>{{ $item->link }}</td>
                                    <td>{{ $item->target }}</td>
                                    <td>{{ $item->active }}</td>
                                    <td @if($loop->count == 1)style="position: fixed;"@endif>
                                        <div class="@if(($loop->count - $loop->iteration) < 3){{'dropup'}}@else{{'dropdown'}}@endif">
                                            <span class="bx bx-dots-vertical-rounded font-medium-3 dropdown-toggle nav-hide-arrow cursor-pointer"
                                                  id="dropdownMenuButton{{ $item->id }}" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></span>
                                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton{{ $item->id }}">
                                                <a class="dropdown-item" data-modal href="{{ route('backend.navigation.edit', $item->id) }}" title="修改导航"><i class="bx bx-edit-alt mr-1"></i> 修改</a>
                                                <a class="dropdown-item" data-destroy type="delete" href="{{ route('backend.navigation.destroy', $item->id) }}" title="刪除导航"><i class="bx bx-trash mr-1"></i>刪除</a>
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

