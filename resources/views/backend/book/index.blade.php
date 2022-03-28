@extends('layouts.contentLayout')

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
            <a id="add-tag" href="{{ route('backend.book.modifyTag', 'add') }}" class="btn btn-success" title="添加标签" role="button" aria-pressed="true">添加标签</a>
            <a id="remove-tag" href="{{ route('backend.book.modifyTag', 'remove') }}" class="btn btn-danger" title="移除标签" role="button" aria-pressed="true">移除标签</a>
        </div>
        <div class="card">
            <div class="card-header">
                <div class="float-left">
                    <h4 class="card-title">@yield('title')</h4>
                </div>
                <div class="float-right d-flex flex-wrap">
                    <form id="batch-price" class="form form-vertical mr-1" method="get" action="{{ route('backend.book.revise.price') }}">
                        <div class="form-body">
                            <div class="d-flex align-items-center">
                                <div class="form-group mr-1">
                                    <div class="controls">
                                        <input type="text" class="form-control" name="charge_chapter" placeholder="开始章节">
                                    </div>
                                </div>
                                <div class="form-group mr-1">
                                    <div class="controls">
                                        <input type="text" class="form-control" name="charge_price" placeholder="金币">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <button type="submit" class="btn btn-success">收费设置</button>
                                </div>
                            </div>
                        </div>
                    </form>
                    <form id="batch-review" class="form form-vertical mr-1" method="get" action="{{ route('backend.book.batch') }}">
                        <div class="form-body">
                            <div class="d-flex align-items-center">
                                <div class="form-group mr-1">
                                    <select class="form-control" name="action">
                                        @foreach($review_options as $key => $val)
                                            <option value="review-{{ $key }}">{{ $val }}</option>
                                        @endforeach
                                        <option value="enable">批量上架</option>
                                        <option value="disable">批量下架</option>
                                        <option value="destroy">批量删除</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <button type="submit" class="btn btn-warning">批量审核</button>
                                </div>
                            </div>
                        </div>
                    </form>
                    <form id="batch-action" class="form form-vertical" method="get" action="{{ route('backend.book.batch') }}">
                        <div class="form-body">
                            <div class="d-flex align-items-center">
                                <div class="form-group mr-1">
                                    <select class="form-control" name="action">
                                        <option value="korea">标记为韩漫</option>
                                        <option value="japan">标记为日漫</option>
                                        <option value="american">标记为美漫</option>
                                        <option value="album">标记为写真</option>
                                        <option value="cg">标记为CG</option>
                                        <option value="featured">标记为精选封面</option>
                                        <option value="end">标记为完结</option>
                                        <option value="syncPrice">套用预设收费设置</option>
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
                                <th>类型</th>
                                <th>收费</th>
                                <th class="text-center">章节数</th>
                                {{--                                <th>发布时间</th>--}}
                                <th class="text-center">连载状态</th>
                                {{--                                <th class="text-center">采集</th>--}}
                                <th class="text-center">阅读数</th>
                                <th class="text-center">收藏数</th>
                                <th>审核状态</th>
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
                                    <td>
                                        @if($book->last_chapter)
                                            <a href="{{ route('backend.book_chapter.preview', $book->last_chapter->id) }}" title="章节预览" data-modal data-size="full">
                                                {{ $book->id }}
                                            </a>
                                        @else
                                            {{ $book->id }}
                                        @endif
                                    </td>
                                    <td style="max-width: 300px;">
                                        <span data-toggle="tooltip" data-placement="top" data-original-title="{{ $book->title }}">
                                            <span class="text-bold-600">{{ Str::limit($book->title, 50, '...') }}</span>
                                        </span>
                                        <div class="d-flex align-content-center flex-wrap mt-50">
                                            @foreach($book->keywords as $tag)
                                                <span class="badge badge-pill badge-light-primary mr-25">{{ $tag }}</span>
                                            @endforeach
                                            @if ($book->author)
                                                <span class="badge badge-pill badge-light-warning mr-25">{{ $book->author }}</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        <img class="cursor-pointer" data-lightbox alt="点击查看大图" src="{{ $book->cover }}" height="60px">
                                    </td>
                                    <td>{{ $book->type }}</td>
                                    <td>
                                        @if($book->charge)
                                            <span class="badge badge-pill badge-light-danger">收费</span>
                                        @else
                                            <span class="badge badge-pill badge-light-primary">免费</span>
                                        @endif
                                    </td>
                                    <td class="text-center">{{ $book->chapters_count }}</td>
                                    {{--                                    <td>{{ $book->last_chapter->created_at->format('Y-m-d') }}</td>--}}
                                    <td class="text-center">
                                        <span class="badge badge-pill badge-light-{{ $book->release_status_style }}">{{ $book->release_status }}</span>
                                    </td>
                                    {{--                                    <td class="text-center">@if($book->operating == 1){{'人工'}}@else{{'爬虫'}}@endif</td>--}}
                                    <td class="text-center">{{ shortenNumber($book->view_counts) }}</td>
                                    <td class="text-center">{{ shortenNumber($book->collect_counts) }}</td>
                                    <td>
                                        @if($book->review == 1)
                                            <a class="badge badge-pill badge-light-warning" data-modal data-size="sm" data-height="240px" href="{{ route('backend.book.review', $book->id) }}" title="上架审查">{{ $review_options[$book->review] }}</a>
                                        @elseif($book->review == 2)
                                            <a class="badge badge-pill badge-light-success" data-modal data-size="sm" data-height="240px" href="{{ route('backend.book.review', $book->id) }}" title="上架审查">{{ $review_options[$book->review] }}</a>
                                        @else
                                            <a class="badge badge-pill badge-light-danger" data-modal data-size="sm" data-height="240px" href="{{ route('backend.book.review', $book->id) }}" title="上架审查">{{ $review_options[$book->review] }}</a>
                                        @endif
                                    </td>
                                    <td>
                                        @if($book->status == 1)
                                            <a class="badge badge-pill badge-light-success" data-confirm href="{{ route('backend.book.batch', ['action'=>'disable', 'ids' => $book->id]) }}" title="下架该作品">上架</a>
                                        @else
                                            <a class="badge badge-pill badge-light-danger" data-confirm href="{{ route('backend.book.batch', ['action'=>'enable', 'ids' => $book->id]) }}" title="上架该作品">下架</a>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="@if(($loop->count - $loop->iteration) < 3){{'dropup'}}@else{{'dropdown'}}@endif">
                                            <span class="bx bx-dots-vertical-rounded font-medium-3 dropdown-toggle nav-hide-arrow cursor-pointer"
                                                  id="dropdownMenuButton{{ $book->id }}" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></span>
                                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton{{ $book->id }}">
                                                <a class="dropdown-item" data-modal data-size="full" href="{{ route('backend.book_chapter.index', $book->id) }}" title="章节列表"><i class="bx bx-list-ol mr-1"></i>章节列表</a>
                                                <a class="dropdown-item" data-modal href="{{ route('backend.book.edit', $book->id) }}" title="编辑漫画"><i class="bx bx-edit-alt mr-1"></i>编辑漫画</a>
                                                <a class="dropdown-item" data-destroy href="{{ route('backend.book.destroy', $book->id) }}" title="删除漫画"><i class="bx bx-trash mr-1"></i>删除漫画</a>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="row">
                        <div class="col-md-4">总共 <b>{{ $list->appends(request()->input())->total() }}</b> 条, 分为 <b>{{ $list->lastPage() }}</b> 页</div>
                        <div class="col-md-6">{!! $list->appends(request()->input())->links() !!}</div>
                        <div class="col-md-2">
                            <form class="form" method="get" action="{{ route('backend.book.index') }}">
                                <div class="input-group">
                                    <input type="text" class="form-control" name="page" placeholder="前往页面">
                                    <div class="input-group-append">
                                        <button class="btn btn-primary" type="submit">Go</button>
                                    </div>
                                </div>
                            </form>
                        </div>
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
                        <label>类型</label>
                        <select class="form-control" name="type">
                            <option value="">全部</option>
                            @foreach ($type_options as $key => $val)
                                <option value="{{ $key }}" @if(request()->get('type') == $key){{'selected'}}@endif>{{ $val }}</option>
                            @endforeach
                        </select>
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
                @foreach($categories as $title => $item)
                    <div class="col-12">
                        <div class="form-group">
                            <label>{{ $title }}标签</label>
                            <div class="controls">
                                <select id="tags-selector" class="form-control" name="tags[{{ $item['code'] }}][]" multiple="multiple">
                                    @foreach($item['tags'] as $tag)
                                        <option value="{{ $tag }}" @if(request()->get('tags') && in_array($tag, request()->get('tags'))){{'selected'}}@endif>{{ $tag }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                @endforeach
                <div class="col-12">
                    <div class="form-group">
                        <label>審查状态</label>
                        <select class="form-control" name="review">
                            <option value="">全部</option>
                            @foreach ($review_options as $key => $val)
                                <option value="{{ $key + 1 }}" @if(request()->has('review') && request()->get('review') == $key + 1){{'selected'}}@endif>{{ $val }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group">
                        <label>上架状态</label>
                        <select class="form-control" name="status">
                            <option value="">全部</option>
                            @foreach ($status_options as $key => $val)
                                <option value="{{ $key + 1 }}" @if(request()->has('status') && request()->get('status') == $key + 1){{'selected'}}@endif>{{ $val }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-12 d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary">搜索</button>
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
                buttonText: function (options, select) {
                    if (options.length === 0) {
                        return '请选择标签';
                    } else {
                        var labels = [];
                        options.each(function () {
                            if ($(this).attr('label') !== undefined) {
                                labels.push($(this).attr('label'));
                            } else {
                                labels.push($(this).html());
                            }
                        });
                        return labels.join(', ') + '';
                    }
                }
            });

            $('#add-tag, #remove-tag').on('click', function (e) {
                e.preventDefault();

                let $this = $(this);
                let ids = $.checkedIds();
                let url = $this.attr('href') + '?ids=' + ids;
                console.log(url);

                if (!ids) {
                    $.toast({
                        type: 'error',
                        message: '请先选择要操作的数据'
                    });
                    return false;
                }

                $.openModal({
                    size: 'lg',
                    height: '50vh',
                    title: $this.attr('title'),
                    url: url
                });

            });

            $('#batch-review, #batch-action').submit(function (e) {
                e.preventDefault();

                let $this = $(this);
                let ids = $.checkedIds();
                let url = $this.attr('action') + '/' + $this.find('select[name="action"]').val();

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
                            callback: function (res) {
                                $.reloadIFrame({
                                    title: '提交成功',
                                    message: res.msg
                                });
                            }
                        });
                    }
                });
            });

            $('#batch-price').submit(function (e) {
                e.preventDefault();

                let $this = $(this);
                let ids = $.checkedIds();
                let url = $this.attr('action');

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
                            data: {'ids': ids, 'charge_chapter': $this.find('input[name="charge_chapter"]').val(), 'charge_price': $this.find('input[name="charge_price"]').val()},
                            debug: true,
                            callback: function (res) {
                                $.toast({
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
                $.reloadIFrame({
                    reloadUrl: url
                });
            });
        });
    </script>
@endsection

