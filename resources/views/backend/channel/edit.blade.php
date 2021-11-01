@extends('layouts.modal')

@section('vendor-styles')
@endsection

@section('page-styles')
@endsection

@section('content')
    <form id="form" class="form" method="post" action="{{ route('backend.channel.update', $channel->id) }}">
        @method('put')
        <div class="form-body">
            <div class="row">
                <div class="col-12">
                    <div class="form-group">
                        <label><span class="danger">*</span> 渠道备注</label>
                        <div class="controls">
                            <input type="text" class="form-control" name="description" value="{{ $channel->description }}">
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group">
                        <label>安全落地頁</label>
                        <div class="controls">
                            <select class="form-control" name="safe_landing">
                                <option value="0" @if($channel->safe_landing ==0){{'selected'}}@endif>否</option>
                                <option value="1" @if($channel->safe_landing ==1){{'selected'}}@endif>是</option>
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
@endsection

{{-- page scripts --}}
@section('page-scripts')
    <script>
        $(document).ready(function () {
            $('#form').submit(function (e) {
                e.preventDefault();

                $.request({
                    url     : $(this).attr('action'),
                    type    : $(this).attr('method'),
                    data    : $(this).serialize(),
                    debug: true,
                    callback: function (res) {
                        if (res.code == 200) {
                            // iframe.blade.php
                            parent.$.hideModal();

                            // iframeLayoutMaster.blade.php
                            parent.$.reloadIFrame({
                                title  : '提交成功',
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
