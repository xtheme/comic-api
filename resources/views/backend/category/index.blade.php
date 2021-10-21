@extends('layouts.iframePage')

{{-- page Title --}}
@section('title','标签分类')

{{-- vendor style --}}
@section('vendor-styles')
@endsection

@section('content')
    <section id="config-list">
        <div class="mb-1">
            <a href="{{ route('backend.category.create') }}" data-size="sm" data-height="30vh" class="btn btn-primary glow" data-modal title="添加分类" role="button" aria-pressed="true">添加分类</a>
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
                                <th>标签分类</th>
                                <th>标签分类代号</th>
                                <th>狀態</th>
                                <th>发布时间</th>
                                <th>操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($list as $item)
                                <tr>
                                    <td>{{ $item->id }}</td>
                                    <td>{{ $item->name }}</td>
                                    <td>{{ $item->type }}</td>
                                    <td>
                                        @if(!$item->status)
                                            <span class="badge badge-pill badge-light-danger">{{ $status_options[$item->status] }}</span>
                                        @else
                                            <span class="badge badge-pill badge-light-primary">{{ $status_options[$item->status] }}</span>
                                        @endif
                                    </td>
                                    <td>{{ $item->created_at }}</td>
                                    <td @if($loop->count == 1)style="position: fixed;"@endif>
                                        <div class="@if(($loop->count - $loop->iteration) < 3){{'dropup'}}@else{{'dropdown'}}@endif">
                                            <span class="bx bx-dots-vertical-rounded font-medium-3 dropdown-toggle nav-hide-arrow cursor-pointer"
                                                  id="dropdownMenuButton{{ $item->id }}" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></span>
                                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton{{ $item->id }}">
                                                <a class="dropdown-item" data-modal data-size="full" href="{{ route('backend.category.editTags', $item->id) }}" title="修改标签"><i class="bx bx-edit-alt mr-1"></i> 标签</a>
                                                <a class="dropdown-item" data-modal data-size="sm" data-height="30vh" href="{{ route('backend.category.edit', $item->id) }}" title="修改分类"><i class="bx bx-edit-alt mr-1"></i> 修改</a>
                                                <a class="dropdown-item" data-destroy type="delete" href="{{ route('backend.category.destroy', $item->id) }}" title="刪除分类"><i class="bx bx-trash mr-1"></i>刪除</a>
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

