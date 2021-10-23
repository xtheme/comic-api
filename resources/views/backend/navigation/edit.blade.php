@extends('layouts.modal')

{{-- page style --}}
@section('page-styles')
@endsection

@section('content')
    <form id="form" class="form" method="post" action="{{ route('backend.navigation.update', $data->id) }}" novalidate>
        @method('put')
        <div class="form-body">
            <div class="row">
                <div class="col-12">
                    <div class="form-group">
                        <label><span class="danger">*</span> 导航名称</label>
                        <div class="controls">
                            <input type="text" class="form-control" name="title" value="{{ $data->title }}">
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group">
                        <label>图标</label>
                        <div class="input-group">
                            <input type="text" class="form-control image-path" name="icon" value="{{ $data->getRawOriginal('icon') }}" autocomplete="off">
                            <input type="file" class="hidden-file-upload" data-path="navigation">
                            <div class="input-group-append" id="input-file-addon">
                                <button class="btn btn-primary upload-image" type="button">上传</button>
                            </div>
                        </div>
                        <div class="upload-image-callback">@if($data->getRawOriginal('icon'))<img src="{{ $data->icon }}" width="60" height="60">@endif</div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group">
                        <span class="float-right font-size-small text-light">(数字由大到小排序)</span>
                        <label>排序</label>
                        <div class="controls">
                            <input type="text" class="form-control" name="sort" value="{{ $data->sort }}">
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group">
                        <span class="float-right font-size-small text-light">(仅支持自定义链接以 http 开头之链接)</span>
                        <label>跳转方式</label>
                        <div class="controls">
                            <select class="form-control" name="target">
                                @foreach ($target_options as $key => $val)
                                    <option value="{{ $key }}" @if($data->target == $key){{'selected'}}@endif>{{ $val }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-12" id="target-filter" @if($data->target == 1)style="display: none;"@endif>
                    <div class="form-group">
                        <label>筛选条件</label>
                        <div class="controls">
                            <select  class="form-control" name="filter_id">
                                <option value="0">N/A</option>
                                @foreach($filters as $filter)
                                    <option value="{{ $filter->id }}" @if($data->filter_id == $filter->id ){{'selected'}}@endif>{{ $filter->title }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-12" id="target-link" @if($data->target == 1)style="display: none;"@endif>
                    <div class="form-group">
                        <label>链接</label>
                        <div class="controls">
                            <input type="text" class="form-control" name="link" placeholder="请填写前端链接" value="{{ $data->link }}">
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group">
                        <label>状态</label>
                        <div class="controls">
                            <select class="form-control" name="status">
                                @foreach ($status_options as $key => $val)
                                    <option value="{{ $key }}" @if($data->status == $key){{'selected'}}@endif>{{ $val }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-12 d-flex justify-content-end">
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
	        $('select[name="target"]').on('change', function (e) {
		        $val = $(this).val();
		        if ($val == 1) {
			        $('#target-filter').show();
			        $('#target-link').hide();
		        } else {
			        $('#target-filter').hide();
			        $('#target-link').show();
		        }
	        });

            $('#form').submit(function (e) {
                e.preventDefault();

                $.request({
                    url     : $(this).attr('action'),
                    type    : $(this).attr('method'),
                    data    : $(this).serialize(),
                    // debug: true,
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
