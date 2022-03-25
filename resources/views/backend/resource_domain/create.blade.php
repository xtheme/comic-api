@extends('layouts.modal')

@section('vendor-styles')
    <link rel="stylesheet" type="text/css" href="{{ asset('vendors/css/pickers/pickadate/pickadate.css') }}">
@endsection

@section('page-styles')
@endsection

@section('content')
    <form id="form" class="form" method="post" action="{{ route('backend.resource_domain.store') }}">
        <div class="form-body">
            <div class="row">
                <div class="col-12">
                    <div class="form-group">
                        <label>类型</label>
                        <div class="controls">
                            <select class="form-control" name="type">
                                @foreach ($type_options as $key => $val)
                                    <option value="{{ $key }}">{{ $val }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group">
                        <label>域名</label>
                        <div class="controls">
                            <input type="text" class="form-control" name="domain">
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group">
                        <label>备注</label>
                        <div class="controls">
                            <input type="text" class="form-control" name="desc">
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group">
                        <label>到期时间</label>
                        <div class="controls">
                            <input type="text" name="expire_at" class="form-control date-picker" value="" placeholder="选择日期">
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group">
                        <label>状态</label>
                        <div class="controls">
                            <select class="form-control" name="status">
                                @foreach ($status_options as $key => $val)
                                    <option value="{{ $key }}">{{ $val }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-12 d-flex justify-content-end">
                    <input type="hidden" name="operating" value="1">
                    <button type="submit" class="btn btn-primary">提交</button>
                </div>
            </div>
        </div>
    </form>
@endsection

{{-- vendor scripts --}}
@section('vendor-scripts')
    <script src="{{ asset('vendors/js/pickers/pickadate/picker.js') }}"></script>
    <script src="{{ asset('vendors/js/pickers/pickadate/picker.date.js') }}"></script>
@endsection

{{-- page scripts --}}
@section('page-scripts')
    <script>
        $(document).ready(function () {
            $('.date-picker').pickadate({
                firstDay: 1,
                format: 'yyyy-mm-dd',
                monthsFull: ['一月', '二月', '三月', '四月', '五月', '六月', '七月', '八月', '九月', '十月', '十一月', '十二月'],
                monthsShort: ['一', '二', '三', '四', '五', '六', '七', '八', '九', '十', '十一', '十二'],
                weekdaysShort: ['日', '一', '二', '三', '四', '五', '六'],
                today: '今天',
                clear: '清除',
                close: '关闭'
            });

            $('#search-form').submit(function (e) {
                e.preventDefault();

                let url = $(this).attr('action') + '?' + $(this).serialize();
                console.log(url);
                $.reloadIFrame({
                    reloadUrl: url
                });
            });

            $('#form').submit(function (e) {
                e.preventDefault();

                $.request({
                    url: $(this).attr('action'),
                    type: $(this).attr('method'),
                    data: $(this).serialize(),
                    debug: true,
                    callback: function (res) {
                        if (res.code == 200) {
                            // iframe.blade.php
                            parent.$.hideModal();

                            // iframeLayoutMaster.blade.php
                            parent.$.reloadIFrame({
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
            });
        });
    </script>
@endsection
