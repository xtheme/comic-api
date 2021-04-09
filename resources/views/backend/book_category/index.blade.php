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
                <h4 class="card-title">@yield('title')</h4>
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
                                <th>关键字</th>
                                <th>描述</th>
                                <th>状态</th>
                                <th>操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($list as $category)
                                <tr>
                                    <th>
                                        <span data-type="text" data-pk="{{ $category->id }}" data-title="修改排序" class="editable editable-click" data-url="{{ route('backend.book_category.editable', 'orders') }}">{{ $category->orders }}</span>
                                    </th>
                                    <th>
                                        <span data-type="text" data-pk="{{ $category->id }}" data-title="修改名称" class="editable editable-click" data-url="{{ route('backend.book_category.editable', 'name') }}">{{ $category->name }}</span>
                                    </th>
                                    <th>
                                        <span data-type="text" data-pk="{{ $category->id }}" data-title="修改关键字" class="editable editable-click" data-url="{{ route('backend.book_category.editable', 'keywords') }}">{{ $category->keywords }}</span>
                                    </th>
                                    <th>
                                        <span data-type="text" data-pk="{{ $category->id }}" data-title="修改描述" class="editable editable-click" data-url="{{ route('backend.book_category.editable', 'desc') }}">{{ $category->desc }}</span>
                                    </th>
                                    <th>{{ $category->status }}</th>
                                    <th></th>
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
                        <label>订单ID</label>
                        <div class="controls">
                            <input type="text" class="form-control"
                                   name="id" value="{{ request()->get('id') }}"
                                   placeholder="">
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group">
                        <label>用户ID</label>
                        <div class="controls">
                            <input type="text" class="form-control"
                                   name="user_id" value="{{ request()->get('user_id') }}"
                                   placeholder="">
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group">
                        <label for="input-created">建立时间</label>
                        <div class="controls">
                            <fieldset class="form-group position-relative has-icon-left">
                                <input type="text" class="form-control" id="input-created" placeholder="请选择建立时间" name="created_at" autocomplete="off" value="{{ request()->get('created_at') }}">
                                <div class="form-control-position">
                                    <i class='bx bx-calendar-check'></i>
                                </div>
                            </fieldset>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group">
                        <label for="select-status">订单状态</label>
                        <select class="form-control" id="select-status" name="status">
                            <option value="">全部</option>
                            @foreach ($status_options as $key => $val)
                                @if (request()->get('status') == $key)
                                    <option value="{{ $key }}" selected>{{ $val }}</option>
                                @else
                                    <option value="{{ $key }}">{{ $val }}</option>
                                @endif
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

