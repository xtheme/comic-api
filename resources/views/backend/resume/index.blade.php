@extends('layouts.iframePage')

{{-- page Title --}}
@section('title','楼凤履历')

{{-- vendor style --}}
@section('vendor-styles')
    <link rel="stylesheet" type="text/css" href="{{ asset('vendors/css/x-editable/bootstrap-editable.css') }}">
@endsection

@section('content')
    <section id="config-list">
        <div class="mb-1">
            <a href="{{ route('backend.resume.create') }}" data-modal data-size="full" class="btn btn-primary" title="新增履历" role="button" aria-pressed="true">新增履历</a>
        </div>
        <div class="card">
            <div class="card-header">
                <div class="float-left">
                    <h4 class="card-title">@yield('title')</h4>
                </div>
                <div class="float-right d-flex flex-wrap">
                    <form id="batch-action" class="form form-vertical" method="get" action="{{ route('backend.topic.batch') }}" novalidate>
                        <div class="form-body">
                            <div class="d-flex align-items-center">
                                <div class="form-group mr-1">
                                    <select class="form-control" name="action">
                                        <option value="enable">启用</option>
                                        <option value="disable">隐藏</option>
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
                                <th>昵称</th>
                                <th>封面</th>
                                <th>省份</th>
                                <th>城市</th>
                                <th>区县</th>
                                <th>价位</th>
                                <th>销售量</th>
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
                                    <td><span class="jeditable" data-pk="{{ $item->id }}" data-url="{{ route('backend.resume.editable', 'nickname') }}">{{ $item->nickname }}</td>
                                    <td>
                                        @if($item->cover)
                                            <img src="{{ $item->cover }}" class="cursor-pointer" width="50px" data-lightbox title="点击查看大图">
                                        @endif
                                    </td>
                                    <td>{{ $item->province->province_name ?? '' }}</td>
                                    <td>{{ $item->city->city_name ?? '' }}</td>
                                    <td>{{ $item->area->area_name ?? '' }}</td>
                                    <td><span class="jeditable" data-pk="{{ $item->id }}" data-url="{{ route('backend.resume.editable', 'price') }}">{{ $item->price }}</td>
                                    <td>{{ $item->sales_volume }}</td>
                                    <td>
                                        @if($item->status == 1)
                                            <a class="badge badge-pill badge-light-success" data-confirm href="{{ route('backend.resume.batch', ['action'=>'disable', 'ids' => $item->id]) }}" title="下架">上架</a>
                                        @else
                                            <a class="badge badge-pill badge-light-danger" data-confirm href="{{ route('backend.resume.batch', ['action'=>'enable', 'ids' => $item->id]) }}" title="上架">下架</a>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="@if(($loop->count - $loop->iteration) < 3){{'dropup'}}@else{{'dropdown'}}@endif">
                                            <span class="bx bx-dots-vertical-rounded font-medium-3 dropdown-toggle nav-hide-arrow cursor-pointer"
                                                  id="dropdownMenuButton{{ $item->id }}" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></span>
                                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton{{ $item->id }}">
                                                <a class="dropdown-item" data-modal data-height="35vh" href="{{ route('backend.resume.edit', $item->id) }}" title="修改履历"><i class="bx bx-edit-alt mr-1"></i>修改</a>
                                                <a class="dropdown-item" data-destroy href="{{ route('backend.resume.destroy', $item->id) }}" title="刪除履历"><i class="bx bx-trash mr-1"></i>刪除</a>
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
                        <label><span class="danger">*</span> 省份</label>
                        <div class="controls">
                            <select class="form-control" name="province_id" id="province_id">
                                <option value="">---</option>
                                @foreach($provinces as $province_id => $province_name)
                                    <option value="{{ $province_id }}" @if(request()->get('province_id') == $province_id){{'selected'}}@endif>{{ $province_name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group">
                        <label><span class="danger">*</span> 城市</label>
                        <div class="controls">
                            <select class="form-control" name="city_id" id="city_id">
                                <option value="">---</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group">
                        <label><span class="danger">*</span> 区县</label>
                        <div class="controls">
                            <select class="form-control" name="area_id" id="area_id">
                                <option value="">---</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-2">
                    <div class="form-group">
                        <label>状态</label>
                        <select class="form-control" name="status">
                            <option value="">全部</option>
                            <option value="2" @if(request()->get('status') == 2){{'selected'}}@endif>上架</option>
                            <option value="1" @if(request()->get('status') == 1){{'selected'}}@endif>下架</option>
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

            $('.jeditable').editable({
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

            $('#batch-action').submit(function (e) {
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

            function chainSelect(current, target) {
                $('#' + current + '_id').on('change', function () {
                    let $id = '';
                    if ($(this).find(':selected').val() != '') {
                        $(target).removeAttr('disabled');
                        $id = $(this).find(':selected').val();
                    }
                    console.log($id);
                    if ($id) {
                        let $url = '/backend/location/' + target + '/' + $id;
                        console.log($url);
                        $.ajax({
                            url: $url,
                            type: 'GET',
                            dataType: 'json',
                            success: function (data) {
                                let newTarget = $('#' + target + '_id');
                                newTarget.empty();
                                newTarget.append('<option value=""> --- </option>');
                                $.each(data, function (key, value) {
                                    newTarget.append('<option value="' + key + '">' + value + '</option>');
                                });
                            }
                        });
                    } else {
                        $('select[name="cities"]').empty();
                    }
                });
            }

            chainSelect('province', 'city');
            chainSelect('city', 'area');
        });
    </script>
@endsection

