@extends('layouts.modal')

{{-- vendor style --}}
@section('vendor-styles')
    <link rel="stylesheet" type="text/css" href="{{ asset('vendors/css/x-editable/bootstrap-editable.css') }}">
@endsection

@section('content')
    <div class="float-left">
        <a data-modal data-size="full" href="{{ route('backend.book_chapter.create' , $book_id) }}" title="新增章节" class="btn btn-primary">添加章节</a>
    </div>
    <div class="float-right">
        <form id="batch-action" class="form form-vertical" method="get" action="{{ route('backend.book_chapter.batch') }}" novalidate>
            <div class="form-body">
                <div class="d-flex align-items-center">
                    <div class="form-group mr-1">
                        <select class="form-control" name="action">
                            <option value="enable">显示</option>
                            <option value="disable">隐藏</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-dark">批量操作</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <div class="table-responsive">
        <table class="table table-striped table-hover">
            <thead>
            <tr>
                <th>
                    <div class="checkbox">
                        <input type="checkbox" class="checkbox-input check-all" id="check-all">
                        <label for="check-all"></label>
                    </div>
                </th>
                <th>ID</th>
                <th>章节</th>
                <th>标题</th>
                <th>售价</th>
                <th>发布时间</th>
                <th>更新时间</th>
                <th>预览</th>
                <th>采集</th>
                <th>上架状态</th>
                <th>操作</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($list as $chapter)
                <tr>
                    <td>
                        <div class="checkbox">
                            <input type="checkbox" class="checkbox-input check-opt" id="check-{{ $chapter->id }}" name="ids[]" value="{{ $chapter->id }}">
                            <label for="check-{{ $chapter->id }}"></label>
                        </div>
                    </td>
                    <td>{{ $chapter->id }}</td>
                    <td>
                        <span data-type="text" data-pk="{{ $chapter->id }}" data-title="修改章节顺序" class="editable editable-click" data-url="{{ route('backend.book_chapter.editable', 'episode') }}">{{ $chapter->episode }}</span>
                    </td>
                    <td>{{ $chapter->title }}</td>
                    <td>{{ $chapter->price }}</td>
                    <td>{{ $chapter->created_at }}</td>
                    <td>{{ $chapter->updated_at }}</td>
                    <td>
                        <a href="{{ route('backend.book_chapter.preview', $chapter->id) }}" title="章节详情" target="_blank">查看</a>
                    </td>
                    <td>@if($chapter->operating == 1){{'人工'}}@else{{'爬虫'}}@endif</td>
                    <td>
                        @if($chapter->status == 1)
                            <a class="badge badge-pill badge-light-success" data-confirm href="{{ route('backend.book_chapter.batch', ['action'=>'disable', 'ids' => $chapter->id]) }}" title="下架该作品">上架</a>
                        @else
                            <a class="badge badge-pill badge-light-danger" data-confirm href="{{ route('backend.book_chapter.batch', ['action'=>'enable', 'ids' => $chapter->id]) }}" title="上架该作品">下架</a>
                        @endif
                    </td>
                    <td>
                        <div class="@if(($loop->count - $loop->iteration) < 3){{'dropup'}}@else{{'dropdown'}}@endif">
                            <span class="bx bx-dots-vertical-rounded font-medium-3 dropdown-toggle nav-hide-arrow cursor-pointer"
                                  id="dropdownMenuButton{{ $chapter->id }}" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></span>
                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton{{ $chapter->id }}">
                                <a class="dropdown-item" data-modal data-size="full" href="{{ route('backend.book_chapter.edit' , $chapter->id) }}" title="编辑章节"><i class="bx bx-edit-alt mr-1"></i>编辑章节</a>
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
@endsection

@section('search-form')
@endsection

{{-- vendor scripts --}}
@section('vendor-scripts')
    <script src="{{ asset('vendors/js/x-editable/bootstrap-editable.js') }}"></script>
@endsection

{{-- page scripts --}}
@section('page-scripts')
    <script>
        $(document).ready(function () {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            });
            $.fn.editable.defaults.ajaxOptions = {type: 'PUT'};
            $.fn.editableform.buttons = '<button type="submit" class="btn btn-primary editable-submit">确认</button>';

            $('.editable-click').editable({
                inputclass: 'form-control',
                emptyclass: 'text-light',
                emptytext: 'N/A',
                success: function (res, newValue) {
                    console.log(res);
                    $.toast({
                        title: '提交成功',
                        message: res.msg
                    });
                }
            });

            $('#batch-action').submit(function (e) {
                e.preventDefault();

                let $this = $(this);
                let ids   = $.checkedIds();
                let url   = $this.attr('action') + '/' + $this.find('select[name="action"]').val();

                if (!ids) {
                    $.toast({
                        type: 'error',
                        message: '请先选择要操作的数据'
                    });
                    return false;
                }

                $.confirm({
                    text: `请确认是否要继续批量操作?`,
                    callback: function () {
                        $.request({
                            url: url,
                            type: 'put',
                            data: {'ids' : ids},
                            debug   : true,
                            callback: function (res) {
                                //@todo 可封装更新弹窗方法到 extends.js
                                let $modal = parent.$('#global-modal');

                                $modal.find('.modal-body iframe').attr('src', '{{ route('backend.book_chapter.index', $book_id) }}');

                                $.toast({
                                    message: res.msg
                                });
                            }
                        });
                    }
                });
            });
        });
    </script>
@endsection

