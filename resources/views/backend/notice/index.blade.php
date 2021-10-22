@extends('layouts.iframePage')

{{-- page Title --}}
@section('title','公告列表')

{{-- vendor style --}}
@section('vendor-styles')
    <link rel="stylesheet" type="text/css" href="{{ asset('vendors/css/pickers/daterange/daterangepicker.css') }}">
@endsection

@section('content')
    <section id="config-list">
        <div class="mb-1">
            <a href="{{ route('backend.notice.create') }}" class="btn btn-primary glow" data-modal title="添加公告" role="button" aria-pressed="true">添加公告</a>
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
                                <th>公告标题</th>
                                <th>图片</th>
                                <th style="width: 50%;">公告内容</th>
                                <th>排序</th>
                                <th>狀態</th>
                                <th>发布时间</th>
                                <th>操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($list as $item)
                                <tr>
                                    <td>{{ $item->id }}</td>
                                    <td>{{ $item->title }}</td>
                                    <td>@if($item->image)<img src="{{ $item->image }}" alt="" class="cursor-pointer" height="60px" data-lightbox title="点击查看大图">@endif</td>
                                    <td>{{ $item->content }}</td>
                                    <td>{{ $item->sort }}</td>
                                    <td>{{ $status_options[$item->status] }}</td>
                                    <td>{{ $item->created_at }}</td>
                                    <td>
                                        <div class="@if(($loop->count - $loop->iteration) < 3){{'dropup'}}@else{{'dropdown'}}@endif">
                                            <span class="bx bx-dots-vertical-rounded font-medium-3 dropdown-toggle nav-hide-arrow cursor-pointer"
                                                  id="dropdownMenuButton{{ $item->id }}" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></span>
                                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton{{ $item->id }}">
                                                <a class="dropdown-item" data-modal href="{{ route('backend.notice.edit', $item->id) }}" title="修改公告"><i class="bx bx-edit-alt mr-1"></i> 修改</a>
                                                <a class="dropdown-item" data-destroy type="delete" href="{{ route('backend.notice.destroy', $item->id) }}" title="刪除公告"><i class="bx bx-trash mr-1"></i>刪除</a>
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

