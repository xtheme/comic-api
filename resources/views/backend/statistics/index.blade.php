@extends('layouts.iframePage')

{{-- page Title --}}
@section('title','播放统计')

{{-- vendor style --}}
@section('vendor-styles')
    <link rel="stylesheet" type="text/css" href="{{ asset('vendors/css/x-editable/bootstrap-editable.css') }}">
@endsection

@section('content')
    <section>
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
                                <th>作品ID</th>
                                <th>作品标题</th>
                                <th>点击量</th>
                                <th>播放总量</th>
                                <th>操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($data as $item)
                                <tr>
                                    <td>{{ $item->id }}</td>
                                    <td>
                                        {{ $item->title }}

                                        @if(!empty($item->tagged))
                                            <div class="d-flex align-content-center flex-wrap" style="margin-top: 5px;">
                                                @foreach($item->tagged as $tagged)
                                                    <span class="badge badge-pill badge-light-primary" style="margin-right: 3px; margin-bottom: 3px;">{{ $tagged->tag_name }}</span>
                                                @endforeach
                                            </div>
                                        @endif
                                    </td>
                                    <td>{{ $item->visit_histories_count }}</td>
                                    <td>{{ $item->play_histories_count }}</td>
                                    <td @if($loop->count == 1)style="position: fixed;"@endif>
                                        <div class="@if(($loop->count - $loop->iteration) < 3){{'dropup'}}@else{{'dropdown'}}@endif">
                                            <span class="bx bx-dots-vertical-rounded font-medium-3 dropdown-toggle nav-hide-arrow cursor-pointer"
                                                  id="dropdownMenuButton{{ $item->id }}" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></span>
                                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton{{ $item->id }}">
                                                <a class="dropdown-item" data-modal href="{{ route('backend.statistics.series', $item->id) }}" title="分集统计"><i class="bx bx-list-ol mr-1"></i>分集统计</a>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="row">
                        <div class="col-md-6">总共 <b>{{ $data->appends(request()->input())->total() }}</b> 条, 分为 <b>{{ $data->lastPage() }}</b> 页</div>
                        <div class="col-md-6">{!! $data->appends(request()->input())->links() !!}</div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('search-form')
    <h4 class="text-uppercase mb-0">查询</h4>
    <small></small>
    <hr>
    <form id="search-form" class="form form-vertical" method="get" action="{{ url()->current() }}" novalidate>
        <div class="form-body">
            <div class="row">
                <div class="col-12">
                    <div class="form-group">
                        <label>作品名称</label>
                        <div class="controls">
                            <input type="text" class="form-control" name="title" value="{{ request()->get('title') }}" placeholder="">
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group">
                        <label>作者</label>
                        <div class="controls">
                            <input type="text" class="form-control" name="author" value="{{ request()->get('author') }}" placeholder="">
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group">
                        <label>标签分类</label>
                        <select id="tags-selector" class="form-control" name="tag[]" multiple="multiple">
                            @foreach($tags as $tag)
                                <option value="{{ $tag }}" @if(in_array($tag, request()->get('tag') ?? [])){{'selected'}}@endif>{{ $tag }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group">
                        <label>状态</label>
                        <select class="form-control" name="status">
                            <option value="">全部</option>
                            @foreach ($status_options as $key => $val)
                                <option value="{{ $key }}" @if(request()->get('status') == $key){{'selected'}}@endif>{{ $val }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group">
                        <label>排序顺序</label>
                        <select class="form-control" name="order">
                            @foreach ($order_by_options as $key => $val)
                                <option value="{{ $key }}" @if(request()->get('order') == $key){{'selected'}}@endif>{{ $val }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-12 d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary mr-1 mb-1">搜索</button>
                    <button type="reset" class="btn btn-light-secondary mr-1 mb-1">重置</button>
                </div>
            </div>
        </div>
    </form>
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
                                $.reloadIFrame({
                                    title  : '提交成功',
                                    message: '请稍后数据刷新'
                                });
                            }
                        });
                    }
                });
            });

            $('#search-form').submit(function(e) {
                e.preventDefault();

                let url = $(this).attr('action') + '?' + $(this).serialize();
                console.log(url);
                $.reloadIFrame({
                    reloadUrl: url
                });
            });
        });
    </script>
@endsection

