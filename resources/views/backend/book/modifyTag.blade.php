@extends('layouts.modal')

@section('vendor-styles')
@endsection

@section('content')
    <!-- Table with outer spacing -->
    <form id="form" class="form" method="post" action="{{ $url }}">
        @method('put')
        <div class="form-body">
            <div class="row">
                @foreach($tags as $tag)
                    <div class="col-2">
                        <div class="form-group">
                            <div class="controls">
                                <fieldset>
                                    <div class="checkbox mt-1">
                                        <input type="checkbox" name="tags[]" id="{{ $tag }}" value="{{ $tag }}">
                                        <label for="{{ $tag }}">{{ $tag }}</label>
                                    </div>
                                </fieldset>
                            </div>
                        </div>
                    </div>
                @endforeach
                <div class="col-12 d-flex justify-content-end">
                    <input type="hidden" name="ids" value="{{ $ids }}">
                    <button type="submit" class="btn btn-primary">添加</button>
                </div>
            </div>
        </div>
    </form>
@endsection

{{-- vendor scripts --}}
@section('vendor-scripts')
@endsection

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
                        if (res.code === 200) {
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