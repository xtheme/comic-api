@extends('layouts.iframePage')

{{-- page Title --}}
@section('title','主题区块')

{{-- vendor style --}}
@section('vendor-styles')
    <link rel="stylesheet" type="text/css" href="{{ asset('vendors/css/x-editable/bootstrap-editable.css') }}">
@endsection

@section('content')
    <section id="config-list">
        <div class="mb-1">
            <a href="{{ route('backend.topic.create') }}" data-modal data-height="35vh" class="btn btn-primary" title="添加主题区块" role="button" aria-pressed="true">添加主题区块</a>
        </div>
        <div class="card">
            <div class="card-header">
                <div class="float-left">
                    <h4 class="card-title">@yield('title')</h4>
                </div>
                <div class="float-right d-flex flex-wrap">
                    <form id="batch-action" class="form form-vertical" method="get" action="{{ route('backend.topic.batch') }}" novalidate>
                        <div class="form-body">
                            <div class="d-flex align-items-center">
                                <div class="form-group mr-1">
                                    <select class="form-control" name="action">
                                        <option value="enable">启用</option>
                                        <option value="disable">隐藏</option>
                                        <option value="destroy">删除</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary">批量操作</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="card-content">
                <div class="card-body">
                    <!-- Table with outer spacing -->
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
                                <th>类型</th>
                                <th>筛选器标题</th>
                                <th>排序</th>
                                <th>展示风格</th>
                                <th>添加时间</th>
                                <th>状态</th>
                                <th>匹配数</th>
                                <th>操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($list as $item)
                                <tr>
                                    <td>
                                        <div class="checkbox">
                                            <input type="checkbox" class="checkbox-input check-opt" id="check-{{ $item->id }}" name="ids[]" value="{{ $item->id }}">
                                            <label for="check-{{ $item->id }}"></label>
                                        </div>
                                    </td>
                                    <td>{{ $item->id }}</td>
                                    <td>
                                        @if($item->type == 'video')
                                            <span class="badge badge-pill badge-primary">动画</span>
                                        @else
                                            <span class="badge badge-pill badge-success">漫画</span>
                                        @endif
                                    </td>
                                    <td>{{ $item->filter->title }}</td>
                                    <td><span class="jeditable" data-pk="{{ $item->id }}" data-value="" > {{ $item->sort }}</td>
                                    <td>{{ $item->style_alias }}</td>
                                    <td>{{ $item->created_at->diffForHumans()  }}</td>
                                    <td>
                                        @if($item->status == 1)
                                            <a class="badge badge-pill badge-light-success" data-confirm href="{{ route('backend.topic.batch', ['action'=>'disable', 'ids' => $item->id]) }}" title="隐藏该区块">启用</a>
                                        @else
                                            <a class="badge badge-pill badge-light-danger" data-confirm href="{{ route('backend.topic.batch', ['action'=>'enable', 'ids' => $item->id]) }}" title="启用该区块">隐藏</a>
                                        @endif
                                    </td>
                                    <td>{{ $item->filter->query_count }}</td>
                                    <td>
                                        <div class="@if(($loop->count - $loop->iteration) < 3){{'dropup'}}@else{{'dropdown'}}@endif">
                                            <span class="bx bx-dots-vertical-rounded font-medium-3 dropdown-toggle nav-hide-arrow cursor-pointer"
                                                  id="dropdownMenuButton{{ $item->id }}" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></span>
                                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton{{ $item->id }}">
                                                <a class="dropdown-item" href="{{ $item->query_url }}" target="_blank"><i class="bx bx-link-external mr-1"></i>查看匹配</a>
                                                <a class="dropdown-item" data-modal data-height="35vh" href="{{ route('backend.topic.edit', $item->id) }}" title="修改首页区块"><i class="bx bx-edit-alt mr-1"></i>修改</a>
                                                <a class="dropdown-item" data-destroy href="{{ route('backend.topic.destroy', $item->id) }}" title="刪除首页区块"><i class="bx bx-trash mr-1"></i>刪除</a>
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

            $('.jeditable').editable({
                inputclass: 'form-control',
                emptyclass: 'text-light',
                emptytext: 'N/A',
                placeholder: '数字需大于0',
                url: '{{ route('backend.topic.sort') }}',
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
                            data: {'ids': ids},
                            debug: true,
                            callback: function (res) {
                                $.reloadIFrame({
                                    title: '提交成功',
                                    message: '请稍后数据刷新'
                                });
                            }
                        });
                    }
                });
            });
        });
    </script>
@endsection

