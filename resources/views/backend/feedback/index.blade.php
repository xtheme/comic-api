@extends('layouts.iframePage')

{{-- page Title --}}
@section('title','意见反馈列表')

{{-- vendor style --}}
@section('vendor-styles')
    <link rel="stylesheet" type="text/css" href="{{ asset('vendors/css/pickers/daterange/daterangepicker.css') }}">
@endsection

@section('content')
    <section id="config-list">
        <div class="mb-1">
            <a href="{{ route('backend.feedback.batch_destroy') }}" data-confirm-feeback data-type="post" data-flag="batch" class="btn btn-danger glow" role="button" aria-pressed="true">批量刪除</a>
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
                                <th>联系方式</th>
                                <th>反馈内容</th>
                                <th>来源</th>
                                <th>版本号</th>
                                <th class="text-center">漫画名</th>
                                <th>添加时间</th>
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
                                    <td>{{ $item->contact }}</td>
                                    <td>{{ $item->content }}</td>
                                    <td>
                                        @switch($item->type)
                                            @case('1')
                                            <span class="badge bg-green b-r-5" style="cursor: pointer;">安卓</span>
                                            @break

                                            @case('2')
                                            <span class="badge bg-red b-r-5" style="cursor: pointer;">IOS</span>
                                            @break
                                            @case('3')
                                            <span class="badge bg-red b-r-5" style="cursor: pointer;">H5</span>
                                            @break
                                        @endswitch
                                    </td>
                                    <td class="text-center">{!! $item->version !!}</td>
                                    <td class="text-center">{{ $item->title }}</td>
                                    <td>{{date('Y-m-d H:i', $item->addtime)}}</td>
                                    <td @if($loop->count == 1)style="position: fixed;"@endif>
                                        <div class="@if(($loop->count - $loop->iteration) < 3){{'dropup'}}@else{{'dropdown'}}@endif">
                                            <span class="bx bx-dots-vertical-rounded font-medium-3 dropdown-toggle nav-hide-arrow cursor-pointer"
                                                  id="dropdownMenuButton{{ $item->id }}" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></span>
                                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton{{ $item->id }}">
                                                <a class="dropdown-item" data-confirm-feeback data-type="delete" href="{{ route('backend.feedback.destroy', $item->id) }}" title="刪除"><i class="bx bx-edit-alt mr-1"></i>刪除</a>
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
    <script src="{{ asset('vendors/js/extensions/moment.min.js') }}"></script>
    <script src="{{ asset('vendors/js/extensions/locale/zh-cn.js') }}"></script>
@endsection

{{-- page scripts --}}
@section('page-scripts')
    <script>
        $('[data-confirm-feeback]').on('click', function (e) {
            e.preventDefault();

            let flag = $(this).data('flag');
            let type = $(this).data('type');
            let href = $(this).attr('href');

            let data = '';
            if (flag == 'batch') {
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
            }

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
                                $.reloadIFrame({
                                    title: '提交成功',
                                    message: '请稍后数据刷新'
                                });
                            } else {
                                $.toast({
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
    </script>
@endsection

