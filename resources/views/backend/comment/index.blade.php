@extends('layouts.iframePage')

{{-- page Title --}}
@section('title','漫画评论列表')

{{-- vendor style --}}
@section('vendor-styles')
    <link rel="stylesheet" type="text/css" href="{{ asset('vendors/css/pickers/daterange/daterangepicker.css') }}">
@endsection

@section('content')
    <section id="config-list">
        <div class="mb-1">
            <a href="{{ route('backend.comment.batch.destroy') }}" data-confirm-comment data-type="post" class="btn btn-danger glow" role="button" aria-pressed="true">批量刪除</a>
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
                                <th>
                                    <div class="checkbox">
                                        <input type="checkbox" class="checkbox-input check-all" id="check-all">
                                        <label for="check-all"></label>
                                    </div>
                                </th>
                                <th>编号</th>
                                <th>昵称</th>
                                <th class="text-center">头像</th>
                                <th>漫画名字</th>
                                <th>章节名</th>
                                <th width="20%">评论内容</th>
                                <th>评论时间</th>
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
                                    <td>{{ $item->user->username }}</td>
                                    <td class="text-center">
                                        @if($item->user->avatar)
                                        <img src="{{$api_url . $item->user->avatar}}" alt="" width="50" height="50">
                                        @else
                                        <i class="bx bx-user-circle"></i>
                                        @endif
                                    </td>
                                    <td>{{ $item->bookchapter->book->book_name }}</td>
                                    <td>{{ $item->bookchapter->title }}</td>
                                    <td>{{ $item->content }}</td>
                                    <td>{{ date('Y-m-d H:i:s',$item->createtime) }}</td>
                                    <td>{!! $item->status_text !!}</td>
                                    <td @if($loop->count == 1)style="position: fixed;"@endif>
                                        <div class="@if(($loop->count - $loop->iteration) < 3){{'dropup'}}@else{{'dropdown'}}@endif">
                                            <span class="bx bx-dots-vertical-rounded font-medium-3 dropdown-toggle nav-hide-arrow cursor-pointer"
                                                  id="dropdownMenuButton{{ $item->id }}" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></span>
                                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton{{ $item->id }}">
                                                <a class="dropdown-item" data-confirm type="delete" href="{{ route('backend.comment.destroy', $item->id) }}" title="刪除"><i class="bx bx-trash mr-1"></i>刪除</a>
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
                        <label for="input-nickname">用戶昵称</label>
                        <div class="controls">
                            <input type="text" id="input-nickname" class="form-control"
                                   name="username" value="{{ request()->get('username') }}"
                                   placeholder="">
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group">
                        <label for="input-id">漫画ID或者名称</label>
                        <div class="controls">
                            <input type="text" id="input-id" class="form-control"
                                   name="id" value="{{ request()->get('id') }}"
                                   placeholder="">
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group">
                        <label for="input-mobile">审核状态</label>
                        <div class="controls">
                            <select name="status" id="status" class="form-control">
                                <option value="">全部</option>
                                <option value="0">待审核</option>
                                <option value="1">已通过</option>
                                <option value="2">已拒绝</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group">
                        <label for="input-date-register">评论时间</label>
                        <div class="controls">
                            <fieldset class="form-group position-relative has-icon-left">
                                <input type="text" id="input-date-register" class="form-control date-picker"
                                       name="date_register" value="{{ request()->get('date_register') }}"
                                       autocomplete="off">
                                <div class="form-control-position">
                                    <i class='bx bx-calendar-check'></i>
                                </div>
                            </fieldset>
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
    <script src="{{ asset('vendors/js/extensions/moment.min.js') }}"></script>
    <script src="{{ asset('vendors/js/extensions/locale-all.js') }}"></script>
    <script src="{{ asset('vendors/js/pickers/daterange/daterangepicker.js') }}"></script>
@endsection

{{-- page scripts --}}
@section('page-scripts')
    <script>
        $('[data-confirm-comment]').on('click', function (e) {
            e.preventDefault();

            let flag = $(this).data('flag');
            let type = $(this).data('type');
            let href = $(this).attr('href');

            let data = '';

            msg          = '是否确认删除所勾选的评论？';
            var $checked = $('.check-opt:checked');

            if ($checked.length == 0) {
                $.toast({
                    type: 'error',
                    title: '提交失败',
                    message: '请选择要删除的评论'
                });
                return false;
            }

            data = $checked.serialize();

            msg = '删除操作不可逆, 确定是否继续?';

            $.confirm({
                text: msg,
                callback: function () {
                    $.request({
                        url: href,
                        type: type,
                        data: data,
                        // debug: true,
                        callback: function (res) {
                            if (res.code == 200) {
                                // iframe.blade.php
                                parent.$.hideModal();

                                // iframeLayoutMaster.blade.php
                                parent.parent.$.reloadIFrame({
                                    title: '提交成功',
                                    message: '数据已刷新'
                                });
                            } else {
                                parent.$.toast({
                                    type: 'error',
                                    title: '提交失败',
                                    message: res.msg
                                });
                            }
                        }
                    });
                }
            });
        });

        let $datePicker = $('.date-picker');

        $datePicker.daterangepicker({
            autoUpdateInput: false,
            startDate: moment().subtract(7, 'days').calendar(),
        });

        $datePicker.on('apply.daterangepicker', function(ev, picker) {
            $(this).val(picker.startDate.format(moment.localeData().longDateFormat('L')) + ' - ' + picker.endDate.format(moment.localeData().longDateFormat('L')));
        });

        $datePicker.on('cancel.daterangepicker', function(ev, picker) {
            $(this).val('');
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

