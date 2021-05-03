@extends('layouts.iframePage')

{{-- page Title --}}
@section('title','漫画分类')

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
                                <th>排序</th>
                                <th>分类名称</th>
{{--                                <th>关键字</th>--}}
                                <th>描述</th>
                                <th>状态</th>
                                <th>操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($list as $category)
                                <tr>
                                    <td>
                                        <span data-type="text" data-pk="{{ $category->id }}" data-title="修改排序" class="editable editable-click" data-url="{{ route('backend.tag.editable', 'priority') }}">{{ $category->priority }}</span>
                                    </td>
                                    <td>
                                        <span data-type="text" data-pk="{{ $category->id }}" data-title="修改名称" class="editable editable-click" data-url="{{ route('backend.tag.editable', 'name') }}">{{ $category->name }}</span>
                                    </td>
{{--                                    <td>--}}
{{--                                        <span data-type="text" data-pk="{{ $category->id }}" data-title="修改关键字" class="editable editable-click" data-url="{{ route('backend.tag.editable', 'keywords') }}">{{ $category->slug }}</span>--}}
{{--                                    </td>--}}
                                    <td>
                                        <span data-type="text" data-pk="{{ $category->id }}" data-title="修改描述" class="editable editable-click" data-url="{{ route('backend.tag.editable', 'description') }}">{{ $category->description }}</span>
                                    </td>
                                    <td>
                                        @switch($category->suggest)
                                            @case(0)
                                            <label class="badge badge-danger badge-pill">隐藏</label>
                                            @break
                                            @case(1)
                                            <label class="badge badge-success badge-pill">显示</label>
                                            @break
                                        @endswitch
                                    </td>
                                    <td></td>
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

