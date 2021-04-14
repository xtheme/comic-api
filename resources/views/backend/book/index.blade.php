@extends('layouts.iframePage')

{{-- page Title --}}
@section('title','漫画列表')

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
                <div class="float-right d-flex flex-wrap">
                    <form class="form form-vertical form-search" method="get" action="{{ url()->current() }}" novalidate>
                        <div class="form-body">
                            <div class="d-flex align-items-center">
                                <div class="form-group mr-1">
                                    <div class="controls">
                                        <input type="text" class="form-control" name="keyword" placeholder="搜索词" value="{{ request()->get('keyword') }}">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary">搜索</button>
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
                                <th>今日推荐</th>
                                <th>人工/自动</th>
                                <th>热度</th>
                                <th>真实阅读量</th>
                                <th>收藏数量</th>
                                <th>最新章节</th>
                                <th>是否收费</th>
                                <th>连载状态</th>
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
                                    <td>
                                        <span data-toggle="tooltip" data-placement="top" data-original-title="{{ $book->book_name }}">
                                            {{ Str::limit($book->book_name, 50, '...') }}
                                        </span>

                                        @if(!empty($book->tagged))
                                        <div class="d-flex align-content-center flex-wrap" style="margin-top: 5px;">
                                            @foreach($book->tagged as $tagged)
                                                <span class="badge badge-light-primary" style="margin-right: 3px;">{{ $tagged->tag_name }}</span>
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
                                    <td>@if($book->daytj == 1){{'是'}}@else{{'否'}}@endif</td>
                                    <td>@if($book->operating == 1){{'人工'}}@else{{'自动'}}@endif</td>
                                    <td>{{ $book->view }}</td>
                                    <td>{{ $book->real_view }}</td>
                                    <td>{{ $book->collect }}</td>
                                    <td>{{ $book->chapters->last()->title ?? '暂无' }}</td>
                                    <td>
                                        @if($book->isfree == 0)
                                            <span class="badge badge-pill badge-light-primary">免费</span>
                                        @else
                                            <span class="badge badge-pill badge-light-danger">收费</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge badge-pill badge-light-{{ $book->release_status_style }}">{{ $book->release_status }}</span>
                                    </td>
                                    <td @if($loop->count == 1)style="position: fixed;"@endif>
                                        <div class="@if(($loop->count - $loop->iteration) < 3){{'dropup'}}@else{{'dropdown'}}@endif">
                                            <span class="bx bx-dots-vertical-rounded font-medium-3 dropdown-toggle nav-hide-arrow cursor-pointer"
                                                  id="dropdownMenuButton{{ $book->id }}" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></span>
                                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton{{ $book->id }}">
                                                <a class="dropdown-item" data-modal href="" title="推荐设置"><i class="bx bxs-bookmark-star mr-1"></i>推荐设置</a>
                                                <a class="dropdown-item" data-modal href="" title="章节列表"><i class="bx bx-list-ol mr-1"></i>章节列表</a>
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

        });

	    $('#search-form').submit(function(e) {
		    e.preventDefault();

		    let url = $(this).attr('action') + '?' + $(this).serialize();
            console.log(url);
            parent.$.reloadIFrame({
			    reloadUrl: url
            });
	    });
    </script>
@endsection

