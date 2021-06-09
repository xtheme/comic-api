@extends('layouts.iframePage')

{{-- page Title --}}
@section('title','标签分类')

{{-- vendor style --}}
@section('vendor-styles')
    <link rel="stylesheet" type="text/css" href="{{ asset('vendors/css/x-editable/bootstrap-editable.css') }}">
@endsection

@section('content')
    <section>
        <div class="mb-1">
            <a href=" {{ route('backend.tag.create') }}" data-modal data-size="sm" data-height="30vh" title="添加标签" class="btn btn-primary glow">添加标签</a>
        </div>
        <div class="card">
            <div class="card-header">
                <div class="float-left">
                    <h4 class="card-title">@yield('title')</h4>
                </div>
                <div class="float-right d-flex flex-wrap">
                    <form id="batch-action" class="form form-vertical" method="get" action="{{ route('backend.tag.batch') }}" novalidate>
                        <div class="form-body">
                            <div class="d-flex align-items-center">
                                <div class="form-group mr-1">
                                    <select class="form-control" name="action">
                                        <option value="dismiss_book">解除关联的漫画</option>
                                        <option value="dismiss_video">解除关联的动画</option>
                                        <option value="enable">在前端显示</option>
                                        <option value="disable">在前端隐藏</option>
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
                                <th>排序</th>
                                <th>分类名称</th>
                                <th>描述</th>
                                <th class="text-right">查询次数</th>
                                <th class="text-center">前端显示</th>
                                <th class="text-center">关联漫画数</th>
                                <th class="text-center">关联动画数</th>
                                <th>操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($tags as $tag)
                                <tr>
                                    <td>
                                        <div class="checkbox">
                                            <input type="checkbox" class="checkbox-input check-opt" id="check-{{ $tag->id }}" name="ids[]" value="{{ $tag->id }}">
                                            <label for="check-{{ $tag->id }}"></label>
                                        </div>
                                    </td>
                                    <td>
                                        <span data-type="text" data-pk="{{ $tag->id }}" data-title="修改排序" class="editable editable-click" data-url="{{ route('backend.tag.editable', 'priority') }}">{{ $tag->priority }}</span>
                                    </td>
                                    <td>
                                        <span data-type="text" data-pk="{{ $tag->id }}" data-title="修改名称" class="editable editable-click" data-url="{{ route('backend.tag.editable', 'name') }}">{{ $tag->name }}</span>
                                    </td>
                                    <td>
                                        <span data-type="text" data-pk="{{ $tag->id }}" data-title="修改描述" class="editable editable-click" data-url="{{ route('backend.tag.editable', 'description') }}">{{ $tag->description }}</span>
                                    </td>
                                    <td class="text-right">{{ shortenNumber($tag->queries) }}</td>
                                    <td class="text-center">
                                        @switch($tag->suggest)
                                            @case(1)
                                                <a class="badge badge-pill badge-light-primary" data-confirm href="{{ route('backend.tag.batch', ['action'=>'disable', 'ids' => $tag->id]) }}" title="在前端隐藏">显示</a>
                                            @break
                                            @case(0)
                                                <a class="badge badge-pill badge-light-danger" data-confirm href="{{ route('backend.tag.batch', ['action'=>'enable', 'ids' => $tag->id]) }}" title="在前端显示">隐藏</a>
                                            @break
                                        @endswitch
                                    </td>
                                    <td class="text-center">
                                        @if($tag->tagged_book_count > 0)
                                            <label class="badge badge-light-primary badge-pill">{{ $tag->tagged_book_count }}</label>
                                        @else
                                            {{ $tag->tagged_book_count }}
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if($tag->tagged_video_count > 0)
                                            <label class="badge badge-light-primary badge-pill">{{ $tag->tagged_video_count }}</label>
                                        @else
                                            {{ $tag->tagged_video_count }}
                                        @endif
                                    </td>
                                    <td>
                                        <div class="@if(($loop->count - $loop->iteration) < 3){{'dropup'}}@else{{'dropdown'}}@endif">
                                            <span class="bx bx-dots-vertical-rounded font-medium-3 dropdown-toggle nav-hide-arrow cursor-pointer"
                                                  id="dropdownMenuButton{{ $tag->id }}" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></span>
                                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton{{ $tag->id }}">
                                                <a class="dropdown-item" href="{{ route('backend.book.index', ['tag[]' => $tag->name]) }}"><i class="bx bx-book mr-1"></i>查看关联漫画</a>
                                                <a class="dropdown-item" href="{{ route('backend.video.index', ['tag[]' => $tag->name]) }}"><i class="bx bx-movie mr-1"></i>查看关联动画</a>
                                                <a class="dropdown-item" data-destroy href="{{ route('backend.tag.destroy', $tag->id) }}" title="删除标签"><i class="bx bx-trash mr-1"></i> 删除标签</a>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="row">
                        <div class="col-md-6">总共 <b>{{ $tags->appends(request()->input())->total() }}</b> 条, 分为 <b>{{ $tags->lastPage() }}</b> 页</div>
                        <div class="col-md-6">{!! $tags->appends(request()->input())->links() !!}</div>
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
                        <label>名称或描述</label>
                        <div class="controls">
                            <input type="text" class="form-control" name="keyword" placeholder="搜索词" value="{{ request()->get('keyword') }}">
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group">
                        <div class="float-right font-small-1 text-muted mt-1">推荐的标签会显示在前台供用户查询</div>
                        <div class="checkbox">
                            <input type="checkbox" class="checkbox-input" id="suggest" name="suggest" value="1" @if(request()->get('suggest')){{'checked'}}@endif>
                            <label for="suggest">前台显示</label>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group">
                        <div class="checkbox">
                            <input type="checkbox" class="checkbox-input" id="tagged_book" name="tagged_book" value="1" @if(request()->get('tagged_book')){{'checked'}}@endif>
                            <label for="tagged_book">有关联的漫画</label>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group">
                        <div class="checkbox">
                            <input type="checkbox" class="checkbox-input" id="tagged_video" name="tagged_video" value="1" @if(request()->get('tagged_video')){{'checked'}}@endif>
                            <label for="tagged_video">有关联的视频</label>
                        </div>
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
                },
                error: function (res, newValue) {
                    console.log(res);
                    parent.$.toast({
                        type: 'error',
                        title: '提交失败',
                        message: res.responseJSON.msg
                    });
                }
            });
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

