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
                                <th>编号</th>
                                <th>公告标题</th>
                                <th>公告关键字</th>
                                <th>新旧版</th>
                                <th>公告内容</th>
                                <th>发布时间</th>
                                <th>操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($list as $item)
                                <tr>
                                    <td>{{ $item->id }}</td>
                                    <td>{{ $item->notice_title }}</td>
                                    <td>{{ $item->notice_keyword }}</td>
                                    <td>{!! $item->type !!}</td>
                                    <td>
                                        <a data-toggle="modal" data-content="{{$item->notice_content}}" data-copy_content="{{$item->copy_content}}"  class="onshowbtn" data-target="#primary" href="javascript:return;">查看公告</a>
                                    </td>
                                    <td>{{ date('Y-m-d H:i:s',$item->time)}}</td>
                                    <td @if($loop->count == 1)style="position: fixed;"@endif>
                                        <div class="@if(($loop->count - $loop->iteration) < 3){{'dropup'}}@else{{'dropdown'}}@endif">
                                            <span class="bx bx-dots-vertical-rounded font-medium-3 dropdown-toggle nav-hide-arrow cursor-pointer"
                                                  id="dropdownMenuButton{{ $item->id }}" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></span>
                                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton{{ $item->id }}">
                                                <a class="dropdown-item" data-modal href="{{ route('backend.notice.edit', $item->id) }}" title="修改公告"><i class="bx bx-edit-alt mr-1"></i> 修改</a>
                                                <a class="dropdown-item" data-confirm type="delete" href="{{ route('backend.notice.destroy', $item->id) }}" title="刪除公告"><i class="bx bx-trash mr-1"></i>刪除</a>
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

<div class="modal fade text-left" id="primary" tabindex="-1" role="dialog"
     aria-labelledby="myModalLabel160" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-body"></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light-secondary" data-dismiss="modal">
                    <i class="bx bx-x d-block d-sm-none"></i>
                    <span class="d-none d-sm-block">關閉</span>
                </button>
            </div>
        </div>
    </div>
</div>



{{-- vendor scripts --}}
@section('vendor-scripts')
    <script src="{{ asset('vendors/js/extensions/moment.min.js') }}"></script>
    <script src="{{ asset('vendors/js/extensions/locale-all.js') }}"></script>
@endsection

{{-- page scripts --}}
@section('page-scripts')
    <script>

        // onShow event
        $('.onshowbtn').on('click', function () {
            var content = $(this).data('content')
            var copy_content = $(this).data('copy_content')

            var data = '';
            data = '<h4 class="card-title">公告內容</h4>' + content;
            if (copy_content && copy_content != '' ){
                data += '<br><br><h4 class="card-title">复制内容</h4>' + copy_content;
            }
            $("#primary .modal-body").html(data);
        });

    </script>
@endsection

