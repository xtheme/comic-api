@extends('layouts.iframePage')

{{-- page Title --}}
@section('title','漫画列表')

{{-- vendor style --}}
@section('vendor-styles')
    <link rel="stylesheet" type="text/css" href="{{ asset('vendors/css/x-editable/bootstrap-editable.css') }}">
@endsection

@section('content')
    <section>
        <div class="mb-1">
            <a href="{{ route('backend.book.create') }}" class="btn btn-primary" data-modal title="添加漫画" data-size="full" data-height="70vh" role="button" aria-pressed="true">添加漫画</a>
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
                                        <option value="check_status-0">待审核</option>
                                        <option value="check_status-1">审核成功</option>
                                        <option value="check_status-2">审核未通过</option>
                                        <option value="check_status-3">屏蔽</option>
                                        <option value="check_status-4">未审核</option>
                                        <option value="charge">第10章节后收费</option>
                                        <option value="free">所有章节免费</option>
                                        <option value="destroy">删除</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <button type="submit" class="btn btn-dark">批量操作</button>
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
                                <th>发布时间</th>
{{--                                <th>今日推荐</th>--}}
                                <th>采集</th>
{{--                                <th>fake阅读数</th>--}}
                                <th>阅读数</th>
                                <th>收藏数</th>
                                <th>章节数</th>
                                <th>是否收费</th>
                                <th>连载状态</th>
                                <th>审核状态</th>
                                <th>删除状态</th>
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
                                        <span data-toggle="tooltip" data-placement="top" data-original-title="{{ $book->book_name }}">
                                            {{ Str::limit($book->book_name, 50, '...') }}
                                        </span>

                                        @if(!empty($book->tagged))
                                        <div class="d-flex align-content-center flex-wrap" style="margin-top: 5px;">
                                            @foreach($book->tagged as $tagged)
                                                <span class="badge badge-light-primary" style="margin-right: 3px; margin-bottom: 3px;">{{ $tagged->tag_name }}</span>
                                            @endforeach
                                        </div>
                                        @endif
                                    </td>
                                    <td>
                                        <img src="{{ $book->vertical_thumb }}" alt="" width="38" height="50">
                                    </td>
                                    <td>{{ $book->pen_name }}</td>
                                    <td>{{ $book->cartoon_type }}</td>
                                    <td>{{ $book->book_chaptertime }}</td>
{{--                                    <td>@if($book->daytj == 1){{'是'}}@else{{'否'}}@endif</td>--}}
                                    <td>@if($book->operating == 1){{'人工'}}@else{{'自动'}}@endif</td>
{{--                                    <td>--}}
{{--                                        <span data-type="text" data-pk="{{ $book->id }}" data-title="修改阅读数" class="editable editable-click" data-url="{{ route('backend.book.editable', 'view') }}">{{ $book->view }}</span>--}}
{{--                                    </td>--}}
                                    <td>{{ $book->real_view }}</td>
                                    <td>
                                        <span data-type="text" data-pk="{{ $book->id }}" data-title="修改收藏数" class="editable editable-click" data-url="{{ route('backend.book.editable', 'collect') }}">{{ $book->collect }}</span>
                                    </td>
{{--                                    <td>{{ $book->chapters->last()->title ?? '暂无' }}</td>--}}
                                    <td>{{ $book->chapters_count }}</td>
                                    <td>
                                        @if($book->charge_chapters_count == 0)
                                            <span class="badge badge-pill badge-light-primary">免费</span>
                                        @else
                                            <span class="badge badge-pill badge-light-danger">收费</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge badge-pill badge-light-{{ $book->release_status_style }}">{{ $book->release_status }}</span>
                                    </td>
                                    <td>
                                        @switch($book->check_status )
                                            @case(0)
                                            <span class="badge badge-pill badge-light-secondary">待审核</span>
                                            @break
                                            @case(1)
                                            <span class="badge badge-pill badge-light-success">审核成功</span>
                                            @break
                                            @case(2)
                                            <span class="badge badge-pill badge-light-warning">审核未通过</span>
                                            @break
                                            @case(3)
                                            <span class="badge badge-pill badge-light-danger">屏蔽</span>
                                            @break
                                            @case(4)
                                            <span class="badge badge-pill badge-light-secondary">未审核</span>
                                            @break
                                        @endswitch
                                    </td>
                                    <td>
                                        @if($book->book_status == 0)
                                            <span class="badge badge-pill badge-light-primary">正常</span>
                                        @else
                                            <span class="badge badge-pill badge-light-danger">删除</span>
                                        @endif
                                    </td>
                                    <td @if($loop->count == 1)style="position: fixed;"@endif>
                                        <div class="@if(($loop->count - $loop->iteration) < 3){{'dropup'}}@else{{'dropdown'}}@endif">
                                            <span class="bx bx-dots-vertical-rounded font-medium-3 dropdown-toggle nav-hide-arrow cursor-pointer"
                                                  id="dropdownMenuButton{{ $book->id }}" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></span>
                                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton{{ $book->id }}">
                                                <a class="dropdown-item" data-modal href="" title="推荐设置"><i class="bx bxs-bookmark-star mr-1"></i>推荐设置</a>
                                                <a class="dropdown-item" data-modal data-size="full" href="{{ route('backend.book_chapter.index', $book->id) }}" title="章节列表"><i class="bx bx-list-ol mr-1"></i>章节列表</a>
                                                <a class="dropdown-item" data-modal href="" title="编辑漫画"><i class="bx bx-edit-alt mr-1"></i>编辑漫画</a>
                                                <a class="dropdown-item" data-confirm href="" title="删除漫画"><i class="bx bx-trash mr-1"></i>删除漫画</a>
                                                <a class="dropdown-item" data-confirm href="" title="漫画审核"><i class="bx bxs-check-shield mr-1"></i>漫画审核</a>
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
                                   name="book_name" value="{{ request()->get('book_name') }}"
                                   placeholder="">
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group">
                        <label>标签分类</label>
                        <select class="form-control" name="tag">
                            <option value="">全部</option>
                            @foreach($tags as $tag)
                                <option value="{{ $tag->name }}" @if(request()->get('tag') == $tag->name){{'selected'}}@endif>{{ $tag->name }} ({{ $tag->count }})</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group">
                        <label>审核状态</label>
                        <select class="form-control" name="check_status">
                            <option value="">全部</option>
                            <option value="1" @if(request()->get('check_status') == 1){{'selected'}}@endif>待审核</option>
                            <option value="2" @if(request()->get('check_status') == 2){{'selected'}}@endif>审核成功</option>
                            <option value="3" @if(request()->get('check_status') == 3){{'selected'}}@endif>审核未通过</option>
                            <option value="4" @if(request()->get('check_status') == 4){{'selected'}}@endif>屏蔽</option>
                            <option value="5" @if(request()->get('check_status') == 5){{'selected'}}@endif>未审核</option>
                        </select>
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group">
                        <label>删除状态</label>
                        <select class="form-control" name="book_status">
                            <option value="">全部</option>
                            <option value="1" @if(request()->get('book_status') == 1){{'selected'}}@endif>漫画正常</option>
                            <option value="2" @if(request()->get('book_status') == 2){{'selected'}}@endif>漫画已删除</option>
                        </select>
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group">
                        <label>收费状态</label>
                        <select class="form-control" name="charge">
                            <option value="">全部</option>
                            <option value="1" @if(request()->get('charge') == 1){{'selected'}}@endif>免费</option>
                            <option value="2" @if(request()->get('charge') == 2){{'selected'}}@endif>收费</option>
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
                            data: {'ids' : ids},
                            debug   : true,
                            callback: function (res) {
                                parent.parent.$.reloadIFrame({
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
                parent.$.reloadIFrame({
                    reloadUrl: url
                });
            });
        });
    </script>
@endsection

