@extends('layouts.iframePage')

{{-- page Title --}}
@section('title','會員套餐')

{{-- vendor style --}}
@section('vendor-styles')
    <link rel="stylesheet" type="text/css" href="{{ asset('vendors/css/pickers/daterange/daterangepicker.css') }}">
@endsection

@section('content')
    <section id="config-list">
        <div class="mb-1">
            <a href="{{ route('backend.pricingpackage.create') }}" class="btn btn-primary glow" data-modal title="添加套餐" role="button" aria-pressed="true">添加套餐</a>
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
                                <th>套餐ID</th>
                                <th>套餐名称</th>
                                <th>天数</th>
                                <th>小标题</th>
                                <th>会员支付价/元</th>
                                <th>会员原价/元</th>
                                <th>标签</th>
                                <th>用户状态</th>
                                <th>显示顺序</th>
                                <th>操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($list as $item)
                                <tr>
                                    <td>{{ $item->id }}</td>
                                    <td>{{ $item->type }}</td>
                                    <td>{{ $item->days }}</td>
                                    <td>{{ $item->name }}</td>
                                    <td>{{ $item->price }}</td>
                                    <td>{{ $item->list_price}}</td>
                                    <td>{{ $item->label }}</td>
                                    <td>{{ $item->pack_status}}</td>
                                    <td>{{ $item->sort}}</td>
                                    <td @if($loop->count == 1)style="position: fixed;"@endif>
                                        <div class="@if(($loop->count - $loop->iteration) < 3){{'dropup'}}@else{{'dropdown'}}@endif">
                                            <span class="bx bx-dots-vertical-rounded font-medium-3 dropdown-toggle nav-hide-arrow cursor-pointer"
                                                  id="dropdownMenuButton{{ $item->id }}" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></span>
                                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton{{ $item->id }}">
                                                <a class="dropdown-item" data-modal href="{{ route('backend.pricingpackage.edit', $item->id) }}" title="修改套餐"><i class="bx bx-edit-alt mr-1"></i> 修改</a>
                                                <a class="dropdown-item" data-confirm type="delete" href="{{ route('backend.pricingpackage.destroy', $item->id) }}" title="刪除套餐"><i class="bx bx-edit-alt mr-1"></i>刪除</a>
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
    <script src="{{ asset('vendors/js/extensions/locale-all.js') }}"></script>
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
    </script>
@endsection

