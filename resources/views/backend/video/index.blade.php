@extends('layouts.iframePage')

{{-- page Title --}}
@section('title','动画列表')

{{-- vendor style --}}
@section('vendor-styles')
    <link rel="stylesheet" type="text/css" href="{{ asset('vendors/css/bootstrap-multiselect/bootstrap-multiselect.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('vendors/css/x-editable/bootstrap-editable.css') }}">
@endsection

@section('content')
    <section id="config-list">
        <div class="mb-1">
            <a href=" {{ route('backend.video.create') }}" data-modal data-height="55vh" title="添加动画" class="btn btn-primary glow">添加动画</a>
        </div>
        <div class="card">
            <div class="card-header">
                <div class="float-left">
                    <h4 class="card-title">@yield('title')</h4>
                </div>
                <div class="float-right">
                    <form id="batch-action" class="form form-vertical" method="get" action="{{ route('backend.video.batch') }}" novalidate>
                        <div class="form-body">
                            <div class="d-flex align-items-center">
                                <div class="form-group mr-1">
                                    <select class="form-control" name="action">
                                        <option value="enable">上架</option>
                                        <option value="disable">下架</option>
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
                                <th>作品名称</th>
                                <th>封面图</th>
                                <th>作者</th>
                                <th>集数</th>
                                <th>角标</th>
                                <th>状态</th>
                                <th>更新时间</th>
                                <th>操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($videos as $video)
                                <tr>
                                    <td>
                                        <div class="checkbox">
                                            <input type="checkbox" class="checkbox-input check-opt" id="check-{{ $video->id }}" name="ids[]" value="{{ $video->id }}">
                                            <label for="check-{{ $video->id }}"></label>
                                        </div>
                                    </td>
                                    <td>{{ $video->id }}</td>
                                    <td>
                                        {{ $video->title }}

                                        @if(!empty($video->tagged))
                                            <div class="d-flex align-content-center flex-wrap" style="margin-top: 5px;">
                                                @foreach($video->tags->sortByDesc('suggest') as $tag)
                                                    <span class="badge badge-pill @if($tag->suggest == 1){{'badge-light-primary'}}@else{{'badge-light-secondary'}}@endif" style="margin-right: 3px; margin-bottom: 3px;">{{ $tag->name }}</span>
                                                @endforeach
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        <img src="{{ $video->cover_thumb }}" alt="" class="cursor-pointer" height="60px" data-lightbox title="点击查看大图">
                                    </td>
                                    <td>{{ $video->author }}</td>
                                    <td>{{ $video->series_count }}</td>
                                    <td>@if($video->ribbon)<span class="badge badge-pill badge-light-primary">{{ $ribbon_options[$video->ribbon] ?? '' }}</span>@endif</td>
                                    <td>
                                        @if($video->status == 1)
                                            <a class="badge badge-pill badge-light-success" data-confirm href="{{ route('backend.video.batch', ['action'=>'disable', 'ids' => $video->id]) }}" title="下架该作品">上架</a>
                                        @else
                                            <a class="badge badge-pill badge-light-danger" data-confirm href="{{ route('backend.video.batch', ['action'=>'enable', 'ids' => $video->id]) }}" title="上架该作品">下架</a>
                                        @endif
                                    </td>
                                    <td>
                                        @if($video->updated_at)
                                            <span data-toggle="tooltip" data-placement="top" data-original-title="{{ $video->updated_at }}">
                                            {{ $video->updated_at->diffForHumans() }}
                                            </span>
                                        @else
                                            <span class="text-light">N/A</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="@if(($loop->count - $loop->iteration) < 3){{'dropup'}}@else{{'dropdown'}}@endif">
                                            <span class="bx bx-dots-vertical-rounded font-medium-3 dropdown-toggle nav-hide-arrow cursor-pointer"
                                                  id="dropdownMenuButton{{ $video->id }}" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></span>
                                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton{{ $video->id }}">
                                                <a class="dropdown-item" data-modal href="{{ route('backend.video.edit', $video->id) }}" title="编辑动画"><i class="bx bx-edit-alt mr-1"></i>编辑动画</a>
                                                <a class="dropdown-item" data-modal data-size="full" href="{{ route('backend.video_series.index', $video->id) }}" title="影集列表"><i class="bx
bxs-videos mr-1"></i>影集列表</a>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="row">
                        <div class="col-md-6">总共 <b>{{ $videos->appends(request()->input())->total() }}</b> 条, 分为 <b>{{ $videos->lastPage() }}</b> 页</div>
                        <div class="col-md-6">{!! $videos->appends(request()->input())->links() !!}</div>
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
                                <option value="{{ $tag->name }}" @if(in_array($tag->name, request()->get('tag') ?? [])){{'selected'}}@endif>{{ $tag->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group">
                        <label>角标</label>
                        <select class="form-control" name="ribbon">
                            <option value="">全部</option>
                            @foreach ($ribbon_options as $key => $val)
                                <option value="{{ $key }}" @if(request()->get('ribbon') == $key){{'selected'}}@endif>{{ $val }}</option>
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
    <script src="{{ asset('vendors/js/bootstrap-multiselect/bootstrap-multiselect.js') }}"></script>
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
            $.fn.editableform.buttons          = '<button type="submit" class="btn btn-primary editable-submit">确认</button>';

            $('.editable-click').editable({
                inputclass: 'form-control',
                emptyclass: 'text-light',
                emptytext: 'N/A',
                success: function (res, newValue) {
                    console.log(res);
                    parent.$.toast({
                        title: '提交成功',
                        message: res.msg
                    });
                }
            });

            $('#tags-selector').multiselect({
                buttonWidth: '100%',
                buttonTextAlignment: 'left',
                buttonText: function(options, select) {
                    if (options.length === 0) {
                        return '请选择标签';
                    }
                    else {
                        var labels = [];
                        options.each(function() {
                            if ($(this).attr('label') !== undefined) {
                                labels.push($(this).attr('label'));
                            }
                            else {
                                labels.push($(this).html());
                            }
                        });
                        return labels.join(', ') + '';
                    }
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

            $('#search-form').submit(function (e) {
                e.preventDefault();

                let url = $(this).attr('action') + '?' + $(this).serialize();
                console.log(url);
                parent.$.reloadIFrame({
                    reloadUrl: url
                });
            });
        });
    </script>
@endsection

