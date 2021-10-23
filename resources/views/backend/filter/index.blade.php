@extends('layouts.iframePage')

{{-- page Title --}}
@section('title','筛选条件')

{{-- vendor style --}}
@section('vendor-styles')
    <link rel="stylesheet" type="text/css" href="{{ asset('vendors/css/x-editable/bootstrap-editable.css') }}">
@endsection

@section('content')
    <section id="config-list">
        <div class="mb-1">
            <a href="{{ route('backend.filter.create', ['book']) }}" data-modal class="btn btn-primary" title="添加漫画筛选条件" role="button" aria-pressed="true">添加漫画筛选条件</a>
            <a href="{{ route('backend.filter.create', ['video']) }}" data-modal class="btn btn-primary" title="添加视频筛选条件" role="button" aria-pressed="true">添加视频筛选条件</a>
        </div>
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
                                <th>#ID</th>
                                <th>类型</th>
                                <th>条件备注</th>
                                <th>查询标签</th>
                                <th>查询条件</th>
                                <th>操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($list as $item)
                                <tr>
                                    <td>{{ $item->id }}</td>
                                    <td>{{ $item->type }}</td>
                                    <td>{{ $item->title }}</td>
                                    <td>
                                        @forelse($item->tags as $key => $value)
                                            <dl class="row">
                                                <dt class="col-sm-3">{{ $key }}</dt>
                                                <dd class="col-sm-9">
                                                    {{ implode(', ', $value) }}
                                                </dd>
                                            </dl>
                                        @empty
                                        @endforelse
                                    </td>
                                    <td>
                                        @forelse($item->params as $key => $value)
                                            @if($value)
                                            <dl class="row">
                                                <dt class="col-sm-4">{{ $key }}</dt>
                                                <dd class="col-sm-8">{{ $value }}</dd>
                                            </dl>
                                            @endif
                                        @empty
                                        @endforelse
                                    </td>
                                    <td>
                                        <div class="@if(($loop->count - $loop->iteration) < 3){{'dropup'}}@else{{'dropdown'}}@endif">
                                            <span class="bx bx-dots-vertical-rounded font-medium-3 dropdown-toggle nav-hide-arrow cursor-pointer"
                                                  id="dropdownMenuButton{{ $item->id }}" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></span>
                                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton{{ $item->id }}">
                                                <a class="dropdown-item" data-modal href="{{ route('backend.filter.edit', [$item->type, $item->id]) }}" title="修改筛选条件"><i class="bx bx-edit-alt mr-1"></i>修改</a>
                                                <a class="dropdown-item" data-destroy href="{{ route('backend.filter.destroy', $item->id) }}" title="刪除筛选条件"><i class="bx bx-trash mr-1"></i>刪除</a>
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
                url: '{{ route('backend.topic.sort') }}',
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
                            data: {'ids': ids},
                            debug: true,
                            callback: function (res) {
                                $.reloadIFrame({
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

