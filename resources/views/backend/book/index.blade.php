@extends('layouts.iframePage')

{{-- page Title --}}
@section('title','漫画列表')

{{-- vendor style --}}
@section('vendor-styles')
    <link rel="stylesheet" type="text/css" href="{{ asset('vendors/css/bootstrap-multiselect/bootstrap-multiselect.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('vendors/css/x-editable/bootstrap-editable.css') }}">
@endsection

@section('content')
    <section>
        <div class="mb-1">
            <a href="{{ route('backend.book.create') }}" class="btn btn-primary" data-modal title="添加漫画" data-height="55vh" role="button" aria-pressed="true">添加漫画</a>
{{--            <a href="{{ route('rbac.content.tag.add', request()->input()) }}" class="btn btn-primary glow" data-modal title="添加标签" data-size="full" data-height="70vh" role="button" aria-pressed="true">添加标签</a>--}}
{{--            <a href="{{ route('rbac.content.tag.remove', request()->input()) }}" class="btn btn-danger glow" data-modal title="移除标签" data-size="full" data-height="70vh" role="button" aria-pressed="true">移除标签</a>--}}
        </div>
        <div class="card">
            <div class="card-header">
                <div class="float-left">
                    <h4 class="card-title">@yield('title')</h4>
                </div>
                <div class="float-right d-flex flex-wrap">
                    <form id="batch-action" class="form form-vertical" method="get" action="{{ route('backend.book.batch') }}" novalidate>
                        <div class="form-body">
                            <div class="d-flex align-items-center">
                                <div class="form-group mr-1">
                                    <select class="form-control" name="action">
{{--                                        <option value="review-1">待审核</option>--}}
{{--                                        <option value="review-2">审核成功</option>--}}
{{--                                        <option value="review-3">审核未通过</option>--}}
{{--                                        <option value="review-4">屏蔽</option>--}}
{{--                                        <option value="review-5">未审核</option>--}}
                                        <option value="charge">章节收费</option>
                                        <option value="free">章节免费</option>
                                        <option value="enable">上架</option>
                                        <option value="disable">下架</option>
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
                                <th>名称</th>
                                <th>封面图</th>
                                <th>作者</th>
                                <th>类型</th>
                                <th>是否收费</th>
                                <th>章节数</th>
                                <th>发布时间</th>
                                <th>连载状态</th>
                                <th>采集</th>
                                <th>阅读数</th>
                                <th>收藏数</th>
{{--                                <th>审核状态</th>--}}
                                <th>上架状态</th>
                                <th>操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($list as $book)
                                <tr>
                                    <td>
                                        <div class="checkbox">
                                            <input type="checkbox" class="checkbox-input check-opt" id="check-{{ $book->id }}" name="ids[]" value="{{ $book->id }}">
                                            <label for="check-{{ $book->id }}"></label>
                                        </div>
                                    </td>
                                    <td>{{ $book->id }}</td>
                                    <td style="max-width: 300px;">
                                        <span data-toggle="tooltip" data-placement="top" data-original-title="{{ $book->title }}">
                                            {{ Str::limit($book->title, 50, '...') }}
                                        </span>
                                        @if(!empty($book->tagged))
                                        <div class="d-flex align-content-center flex-wrap" style="margin-top: 5px;">
                                            @foreach($book->tagged as $tagged)
                                                <span class="badge badge-pill badge-light-primary" style="margin-right: 3px; margin-bottom: 3px;">{{ $tagged->tag_name }}</span>
                                            @endforeach
                                        </div>
                                        @endif
                                    </td>
                                    <td>
                                        <img src="{{ $book->vertical_cover }}" alt="" width="38" height="50">
                                    </td>
                                    <td>{{ $book->author }}</td>
                                    <td>{{ $book->type }}</td>
                                    <td>
                                        @if($book->latest_chapter->charge == -1)
                                            <span class="badge badge-pill badge-light-primary">免费</span>
                                        @else
                                            <span class="badge badge-pill badge-light-danger">收费</span>
                                        @endif
                                    </td>
                                    <td>{{ $book->chapters_count }}</td>
                                    <td>{{ $book->latest_chapter->created_at }}</td>
                                    <td>
                                        <span class="badge badge-pill badge-light-{{ $book->release_status_style }}">{{ $book->release_status }}</span>
                                    </td>
                                    <td>@if($book->operating == 1){{'人工'}}@else{{'自动'}}@endif</td>
                                    <td>{{ shortenNumber($book->visits) }}</td>
                                    <td>{{ $book->favorite_histories_count }}</td>
{{--                                    <td>--}}
{{--                                        @switch($book->review)--}}
{{--                                            @case(1)--}}
{{--                                            <span class="badge badge-pill badge-light-secondary">待审核</span>--}}
{{--                                            @break--}}
{{--                                            @case(2)--}}
{{--                                            <span class="badge badge-pill badge-light-success">审核成功</span>--}}
{{--                                            @break--}}
{{--                                            @case(3)--}}
{{--                                            <span class="badge badge-pill badge-light-warning">审核未通过</span>--}}
{{--                                            @break--}}
{{--                                            @case(4)--}}
{{--                                            <span class="badge badge-pill badge-light-danger">屏蔽</span>--}}
{{--                                            @break--}}
{{--                                            @case(5)--}}
{{--                                            <span class="badge badge-pill badge-light-secondary">未审核</span>--}}
{{--                                            @break--}}
{{--                                        @endswitch--}}
{{--                                    </td>--}}
                                    <td>
                                        @if($book->status == 1)
                                            <a class="badge badge-pill badge-light-success" data-confirm href="{{ route('backend.book.batch', ['action'=>'disable', 'ids' => $book->id]) }}" title="下架该作品">上架</a>
                                        @else
                                            <a class="badge badge-pill badge-light-danger" data-confirm href="{{ route('backend.book.batch', ['action'=>'enable', 'ids' => $book->id]) }}" title="上架该作品">下架</a>
                                        @endif
                                    </td>
                                    <td @if($loop->count == 1)style="position: fixed;"@endif>
                                        <div class="@if(($loop->count - $loop->iteration) < 3){{'dropup'}}@else{{'dropdown'}}@endif">
                                            <span class="bx bx-dots-vertical-rounded font-medium-3 dropdown-toggle nav-hide-arrow cursor-pointer"
                                                  id="dropdownMenuButton{{ $book->id }}" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></span>
                                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton{{ $book->id }}">
{{--                                                <a class="dropdown-item" data-modal href="" title="推荐设置"><i class="bx bxs-bookmark-star mr-1"></i>推荐设置</a>--}}
                                                <a class="dropdown-item" data-modal data-size="full" href="{{ route('backend.book_chapter.index', $book->id) }}" title="章节列表"><i class="bx bx-list-ol mr-1"></i>章节列表</a>
                                                <a class="dropdown-item" data-modal href="{{ route('backend.book.edit', $book->id) }}" title="编辑漫画"><i class="bx bx-edit-alt mr-1"></i>编辑漫画</a>
                                                <a class="dropdown-item" data-destroy href="{{ route('backend.book.destroy', $book->id) }}" title="删除漫画"><i class="bx bx-trash mr-1"></i>删除漫画</a>
                                                <a class="dropdown-item" data-modal data-size="sm" data-height="20vh" href="{{ route('backend.book.review', $book->id) }}" title="漫画审核"><i class="bx bxs-check-shield mr-1"></i>漫画审核</a>
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

@section('search-form')
    <h4 class="text-uppercase mb-0">查询</h4>
    <small></small>
    <hr>
    <form id="search-form" class="form form-vertical" method="get" action="{{ url()->current() }}" novalidate>
        <div class="form-body">
            <div class="row">
                <div class="col-12">
                    <div class="form-group">
                        <label>ID</label>
                        <div class="controls">
                            <input type="text" class="form-control"
                                   name="id" value="{{ request()->get('id') }}"
                                   placeholder="">
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group">
                        <label>漫画名称</label>
                        <div class="controls">
                            <input type="text" class="form-control"
                                   name="title" value="{{ request()->get('title') }}"
                                   placeholder="">
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
                        <label>审核状态</label>
                        <select class="form-control" name="review">
                            <option value="">全部</option>
                            @foreach ($review_options as $key => $val)
                                <option value="{{ $key }}" @if(request()->get('review') == $key){{'selected'}}@endif>{{ $val }}</option>
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
                        <label>收费状态</label>
                        <select class="form-control" name="charge">
                            <option value="">全部</option>
                            @foreach ($charge_options as $key => $val)
                                <option value="{{ $key }}" @if(request()->get('charge') == $key){{'selected'}}@endif>{{ $val }}</option>
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

