@extends('layouts.iframePage')

{{-- page Title --}}
@section('title','主题模块')

{{-- vendor style --}}
@section('vendor-styles')
    <link rel="stylesheet" type="text/css" href="{{ asset('vendors/css/x-editable/bootstrap-editable.css') }}">
@endsection

@section('content')
    <section id="config-list">
        <div class="mb-1">
            <a href="{{ route('backend.block.create') }}" data-modal class="btn btn-primary" title="添加主题模块" role="button" aria-pressed="true">添加主题模块</a>
        </div>
        <div class="card">
            <div class="card-header">
                <div class="float-left">
                    <h4 class="card-title">@yield('title')</h4>
                </div>
                <div class="float-right d-flex flex-wrap">
                    <form id="batch-action" class="form form-vertical" method="get" action="{{ route('backend.block.batch') }}" novalidate>
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
{{--                    <form id="search-form" class="form form-horizontal" method="get" action="{{ url()->current() }}" novalidate>--}}
{{--                        <div class="form-body">--}}
{{--                            <div class="d-flex align-items-center">--}}
{{--                                <div class="form-group mr-1">--}}
{{--                                    <div class="controls">--}}
{{--                                        <select id="class-type" class="form-control" name="causer">--}}
{{--                                            <option value="" >全部</option>--}}
{{--                                            <option value="video" @if(request()->get('causer') == 'video') selected @endif >动画</option>--}}
{{--                                            <option value="comic" @if(request()->get('causer') == 'comic') selected @endif >漫画</option>--}}
{{--                                        </select>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                                <div class="form-group mr-1">--}}
{{--                                    <div class="controls">--}}
{{--                                        <input type="text" class="form-control" name="title"--}}
{{--                                               placeholder="请输入标题"--}}
{{--                                               value="{{ request()->get('title') }}">--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                                <div class="form-group">--}}
{{--                                    <button type="submit" class="btn btn-primary">搜索</button>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </form>--}}
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
                                <th>标题</th>
                                <th>排序</th>
                                <th>展示风格</th>
{{--                                <th>聚焦数</th>--}}
{{--                                <th>每行数</th>--}}
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
                                        @if($item->causer == 'video')
                                            <span class="badge badge-pill badge-primary">动画</span>
                                        @else
                                            <span class="badge badge-pill badge-success">漫画</span>
                                        @endif
                                    </td>
                                    <td>
                                        {{ $item->title }}
                                        @if(!empty($item->properties['tag'] ))
                                            <div class="d-flex align-content-center flex-wrap" style="margin-top: 5px;">
                                                @foreach($item->properties['tag'] as $tagged)
                                                    <span class="badge badge-pill badge-light-primary" style="margin-right: 3px; margin-bottom: 3px;">{{ $tagged }}</span>
                                                @endforeach
                                            </div>
                                        @endif
                                    </td>
                                    <td><span class="jeditable" data-pk="{{ $item->id }}" data-value="" > {{ $item->sort }}</td>
                                    <td>{{ $item->style_alias }}</td>
{{--                                    <td>{{ $item->spotlight }}</td>--}}
{{--                                    <td>{{ $item->row }}</td>--}}
                                    <td>{{ $item->created_at->diffForHumans()  }}</td>
                                    <td>
                                        @if($item->status == 1)
                                            <a class="badge badge-pill badge-light-success" data-confirm href="{{ route('backend.block.batch', ['action'=>'disable', 'ids' => $item->id]) }}" title="隐藏该模块">启用</a>
                                        @else
                                            <a class="badge badge-pill badge-light-danger" data-confirm href="{{ route('backend.block.batch', ['action'=>'enable', 'ids' => $item->id]) }}" title="启用该模块">隐藏</a>
                                        @endif
                                    </td>
                                    <td>{{ $item->query_count }}</td>
                                    <td @if($loop->count == 1)style="position: fixed;"@endif>
                                        <div class="@if(($loop->count - $loop->iteration) < 3){{'dropup'}}@else{{'dropdown'}}@endif">
                                            <span class="bx bx-dots-vertical-rounded font-medium-3 dropdown-toggle nav-hide-arrow cursor-pointer"
                                                  id="dropdownMenuButton{{ $item->id }}" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></span>
                                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton{{ $item->id }}">
                                                <a class="dropdown-item" href="{{ $item->query_url }}" target="_blank"><i class="bx bx-link-external mr-1"></i>查看匹配</a>
                                                <a class="dropdown-item" data-modal href="{{ route('backend.block.edit', $item->id) }}" title="修改首页模块"><i class="bx bx-edit-alt mr-1"></i>修改</a>
                                                <a class="dropdown-item" data-destroy href="{{ route('backend.block.destroy', $item->id) }}" title="刪除首页模块"><i class="bx bx-trash mr-1"></i>刪除</a>
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
                url: '{{ route('backend.block.sort') }}',
                success: function (res, newValue) {
                    console.log(res);
                    parent.$.toast({
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
                    parent.$.toast({
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
                                parent.$.reloadIFrame({
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

