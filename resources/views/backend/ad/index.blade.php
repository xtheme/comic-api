@extends('layouts.iframePage')

{{-- page Title --}}
@section('title','广告列表')

{{-- vendor style --}}
@section('vendor-styles')
    <link rel="stylesheet" type="text/css" href="{{ asset('vendors/css/x-editable/bootstrap-editable.css') }}">
@endsection

@section('content')
    <section id="config-list">

        <div class="mb-1">
            <a href="{{ route('backend.ad.batch') }}" data-action="enable" title="上架" class="btn btn-success batch-action" role="button" aria-pressed="true">批量上架</a>
            <a href="{{ route('backend.ad.batch') }}" data-action="disable" title="下架" class="btn btn-light batch-action" role="button" aria-pressed="true">批量下架</a>
            <a href="{{ route('backend.ad.batch.destroy') }}" data-batch data-type="post"  title="刪除" class="btn btn-danger" role="button" aria-pressed="true">批量刪除</a>
            <a href=" {{ route('backend.ad.create') }}" data-modal data-size="lg" title="添加广告" class="btn btn-primary">添加广告</a>
        </div>
        <div class="card">
            <div class="card-header">
                <div class="float-left">
                    <h4 class="card-title">@yield('title')</h4>
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
                                <th>广告位</th>
                                <th>排序</th>
                                <th>名称</th>
                                <th>平台</th>
                                <th>广告图片</th>
                                <th>广告地址</th>
                                <th>显示时间</th>
                                <th>修改时间</th>
                                <th>状态</th>
                                <th>操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($list as $item)
                                <tr>
                                    <td>
                                        <div class="checkbox">
                                            <input type="checkbox" class="checkbox-input check-opt" id="check-{{ $item->id }}" name="ids[]" value="{{ $item->id }}">
                                            <label for="check-{{ $item->id }}"></label>
                                        </div>
                                    </td>
                                    <td>{{ $item->id }}</td>
                                    <td>{{ $item->ad_space->name }}</td>
                                    <td><span class="jeditable" data-pk="{{ $item->id }}" data-value="" > {{ $item->sort }}</td>
                                    <td>{{ $item->name }}</td>
                                    <td>
                                        @if($item->platform == 1)
                                            <i class="bx bxl-android font-medium-2"></i>
                                        @else
                                            <i class="bx bxl-apple font-medium-2"></i>
                                        @endif
                                    </td>
                                    <td><img src="{{ $item->image_thumb }}" class="cursor-pointer" width="50px" data-lightbox title="点击查看大图"></td>
                                    <td>{{ $item->url }}</td>
                                    <td>{{ $item->show_time }}</td>
                                    <td>@if($item->updated_at){{ $item->updated_at->diffForHumans()  }}@endif</td>
                                    <td>
                                        @if($item->status == 1)
                                            <a class="badge badge-pill badge-light-success" data-confirm href="{{ route('backend.ad.batch', ['action'=>'disable', 'ids' => $item->id]) }}" title="下架广告">上架</a>
                                        @else
                                            <a class="badge badge-pill badge-light-danger" data-confirm href="{{ route('backend.ad.batch', ['action'=>'enable', 'ids' => $item->id]) }}" title="上架广告">下架</a>
                                        @endif
                                    </td>
                                    <td @if($loop->count == 1)style="position: fixed;"@endif>
                                        <div class="@if(($loop->count - $loop->iteration) < 3){{'dropup'}}@else{{'dropdown'}}@endif">
                                            <span class="bx bx-dots-vertical-rounded font-medium-3 dropdown-toggle nav-hide-arrow cursor-pointer"
                                                  id="dropdownMenuButton{{ $item->id }}" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></span>
                                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton{{ $item->id }}">
                                                <a class="dropdown-item" data-modal href="{{ route('backend.ad.edit', $item->id) }}" title="修改广告"><i class="bx bx-edit-alt mr-1"></i> 修改</a>
                                                <a class="dropdown-item" data-destroy href="{{ route('backend.ad.destroy', $item->id) }}" title="刪除广告"><i class="bx bx-trash mr-1"></i> 删除</a>
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
                        <div class="col-md-6">{!! $list->links() !!}</div>
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
                        <label for="input-id">广告名称</label>
                        <div class="controls">
                            <input type="text" id="input-id" class="form-control"
                                   name="name" value="{{ request()->get('name') }}"
                                   placeholder="">
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group">
                        <label for="input-nickname">广告位</label>
                        <div class="controls">
                            <select id="select-type" class="form-control" name="space_id">
                                <option value="" >全部</option>
                                @foreach($ad_spaces as $key => $item)
                                    <option value="{{$item->id}}" @if(request()->get('space_id') == $item->id) selected @endif >{{$item->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group">
                        <label for="input-nickname">跳转类型</label>
                        <div class="controls">
                            <select id="jump-type" class="form-control" name="jump_type">
                                <option value="">全部</option>
                                @foreach ($jump_type as $key => $val)
                                    <option value="{{ $key }}" @if(request()->get('jump_type') == $key) selected @endif >{{ $val }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group">
                        <label for="input-mobile">平台</label>
                        <div class="controls">
                            <select id="select-platform" class="form-control" name="platform">
                                <option value="">全部</option>
                                <option value="1" @if(request()->get('platform') == 1) selected @endif>安卓</option>
                                <option value="2" @if(request()->get('platform') == 2) selected @endif>IOS</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group">
                        <label for="select-status">状态</label>
                        <select id="jump-type" class="form-control" name="status">
                            <option value="">全部</option>
                            <option value="1" @if(request()->get('status') == 1) selected @endif>开启</option>
                            <option value="-1" @if(request()->get('status') == -1) selected @endif>关闭</option>
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
                url: '{{ route('backend.ad.sort') }}',
                success: function (res, newValue) {
                    console.log(res);
                    parent.$.toast({
                        title: '提交成功',
                        message: res.msg
                    });
                }
            });


            $('[data-lightbox]').on('click', function (e) {
                e.preventDefault();
                let $this = $(this);
                $.openImage({
                    size: $this.data('size') || '',
                    height: $this.data('height') || '30vh',
                    title: '检视图片',
                    image: $this.attr('src')
                });
            });


            $('.batch-action').on('click', function (e) {
                e.preventDefault();

                let $this = $(this);
                let ids   = $.checkedIds();
                let url   = $this.attr('href') + '/' + $this.data('action');

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
                                parent.$.reloadIFrame();
                            }
                        });
                    }
                });
            });
        });
    </script>
@endsection
