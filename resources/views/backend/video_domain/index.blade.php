@extends('layouts.iframePage')

{{-- page Title --}}
@section('title','动画域名配置')

{{-- vendor style --}}
@section('vendor-styles')
    <link rel="stylesheet" type="text/css" href="{{ asset('vendors/css/x-editable/bootstrap-editable.css') }}">
@endsection

@section('content')
    <section id="config-list">
        <div class="mb-1">
            <a href=" {{ route('backend.video_domain.create') }}" data-modal data-size="lg" title="添加 CDN 域名" class="btn btn-primary glow">添加域名</a>
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
{{--                                <th>
                                    <div class="checkbox">
                                        <input type="checkbox" class="checkbox-input check-all" id="check-all">
                                        <label for="check-all"></label>
                                    </div>
                                </th>--}}
                                <th>ID</th>
                                <th>域名名称</th>
                                <th>动画数</th>
                                <th>未加密域名</th>
                                <th>加密域名</th>
                                <th>排序</th>
                                <th>备注</th>
                                <th>状态</th>
                                <th>更新时间</th>
                                <th>操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($domains as $domain)
                                <tr>
{{--                                    <td>
                                        <div class="checkbox">
                                            <input type="checkbox" class="checkbox-input check-opt" id="check-{{ $domain->id }}" name="ids[]" value="{{ $domain->id }}">
                                            <label for="check-{{ $domain->id }}"></label>
                                        </div>
                                    </td>--}}
                                    <td>{{ $domain->id }}</td>
                                    <td>{{ $domain->title }}</td>
                                    <td>{{ $domain->series_count }}</td>
                                    <td>{{ $domain->domain }}</td>
                                    <td>{{ $domain->encrypt_domain }}</td>
                                    <td>
                                        <span data-type="text" data-pk="{{ $domain->id }}" data-title="修改排序" class="editable editable-click" data-url="{{ route('backend.video_domain.editable', 'sort') }}">{{ $domain->sort }}</span>
                                    </td>
                                    <td>{{ $domain->remark }}</td>
                                    <td>@if($domain->status ==1)
                                            <span class="badge badge-pill badge-light-success">启用</span>
                                        @else
                                            <span class="badge badge-pill badge-light-danger">禁用</span>
                                        @endif
                                    </td>
                                    <td>{{ optional($domain->updated_at)->diffForHumans() }}</td>
                                    <td @if($loop->count == 1)style="position: fixed;"@endif>
                                        <div class="@if(($loop->count - $loop->iteration) < 3){{'dropup'}}@else{{'dropdown'}}@endif">
                                            <span class="bx bx-dots-vertical-rounded font-medium-3 dropdown-toggle nav-hide-arrow cursor-pointer"
                                                  id="dropdownMenuButton{{ $domain->id }}" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></span>
                                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton{{ $domain->id }}">
                                                <a class="dropdown-item" data-modal href="{{ route('backend.video_domain.series', $domain->id) }}" title="关联动画"><i class="bx bx-edit-alt mr-1"></i>关联动画</a>
                                                <a class="dropdown-item" data-modal href="{{ route('backend.video_domain.edit', $domain->id) }}" title="修改域名"><i class="bx bx-edit-alt mr-1"></i>修改域名</a>
                                                <a class="dropdown-item" data-destroy href="{{ route('backend.video_domain.destroy', $domain->id) }}" title="刪除域名"><i class="bx bx-trash mr-1"></i>刪除域名</a>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="row">
                        <div class="col-md-6">总共 <b>{{ $domains->appends(request()->input())->total() }}</b> 条, 分为 <b>{{ $domains->lastPage() }}</b> 页</div>
                        <div class="col-md-6">{!! $domains->appends(request()->input())->links() !!}</div>
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
                        <label>域名</label>
                        <div class="controls">
                            <input type="text" class="form-control"
                                   name="domain" value="{{ request()->get('domain') }}"
                                   placeholder="">
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group">
                        <label for="select-status">状态</label>
                        <select class="form-control" name="status">
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
                    <button type="submit" class="btn btn-primary">搜索</button>
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

            /*$('#batch-action').submit(function (e) {
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
                                parent.$.reloadIFrame({
                                    title  : '提交成功',
                                    message: '请稍后数据刷新'
                                });
                            }
                        });
                    }
                });
            });*/

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

