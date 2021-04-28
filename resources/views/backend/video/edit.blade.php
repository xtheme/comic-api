@extends('layouts.modal')

@section('vendor-styles')
    <link rel="stylesheet" type="text/css" href="{{ asset('vendors/css/bootstrap-multiselect/bootstrap-multiselect.css') }}">
@endsection

@section('page-styles')
@endsection

@section('content')
    <form id="form" class="form" method="post" action="{{ route('backend.video.update', $video->id) }}" novalidate>
        <div class="form-body">
            <div class="row">
                <div class="col-12">
                    <div class="form-group">
                        <label><span class="danger">*</span> 作品名称</label>
                        <div class="controls">
                            <input type="text" class="form-control" name="title" value="{{ $video->title }}" placeholder="">
                        </div>
                    </div>
                </div>
                <div class="col-4">
                    <div class="form-group">
                        <label>作者</label>
                        <div class="controls">
                            <input type="text" class="form-control" name="author" value="{{ $video->author }}" placeholder="">
                        </div>
                    </div>
                </div>
                <div class="col-4">
                    <div class="form-group">
                        <label>角标</label>
                        <div class="controls">
                            <select class="form-control" name="ribbon">
                                <option value="0">无</option>
                                @foreach ($ribbon_options as $key => $val)
                                    <option value="{{ $key }}" @if($key == $video->ribbon){{'selected'}}@endif>{{ $val }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-4">
                    <div class="form-group">
                        <label>状态</label>
                        <div class="controls">
                            <select class="form-control" name="status">
                                @foreach ($status_options as $key => $val)
                                    <option value="{{ $key }}" @if($key == $video->status){{'selected'}}@endif>{{ $val }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group">
                        <label>标签分类</label>
                        <div class="controls">
                            <select id="tags-selector" class="form-control" name="tag[]" multiple="multiple">
                                @foreach($tags as $tag)
                                    <option value="{{ $tag->name }}" @if(in_array($tag->name, $video->tagged_arr)){{'selected'}}@endif>{{ $tag->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group">
                        <label><span class="danger">*</span> 封面图</label>
                        <div class="input-group">
                            <div class="input-group">
                                <input type="text" class="form-control image-path" name="cover" autocomplete="off" value="{{ $video->cover }}">
                                <input type="file" class="hidden-file-upload" data-path="video/{{ $video->id }}">
                                <div class="input-group-append" id="input-file-addon">
                                    <button class="btn btn-primary upload-image" type="button">上传</button>
                                </div>
                            </div>
{{--                            <div class="custom-file">--}}
{{--                                <input type="file" class="custom-file-input" id="vertical-thumb" name="cover" value="{{ $video->cover }}">--}}
{{--                                <label class="custom-file-label" for="vertical-thumb">请选择文件</label>--}}
{{--                            </div>--}}
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group">
                        <label>简介</label>
                        <textarea name="description" class="form-control" rows="5" placeholder="内容简介">{{ $video->description }}</textarea>
                    </div>
                </div>
                <div class="col-12 d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary mr-1">提交</button>
                    <button type="reset" class="btn btn-light-secondary">还原</button>
                </div>
            </div>
        </div>
    </form>
@endsection

{{-- vendor scripts --}}
@section('vendor-scripts')
    <script src="{{ asset('vendors/js/bootstrap-multiselect/bootstrap-multiselect.js') }}"></script>
@endsection

{{-- page scripts --}}
@section('page-scripts')
    <script>
        $(document).ready(function () {
            $('#tags-selector').multiselect({
                buttonWidth: '100%',
                buttonTextAlignment: 'left',
                buttonText: function(options, select) {
                    if (options.length === 0) {
                        return '请选择标签';
                    }
                    else {
                        var labels = [];
                        options.each(function() {
                            if ($(this).attr('label') !== undefined) {
                                labels.push($(this).attr('label'));
                            }
                            else {
                                labels.push($(this).html());
                            }
                        });
                        return labels.join(', ') + '';
                    }
                }
            });

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
                            parent.parent.$.reloadIFrame({
                                title  : '提交成功',
                                message: '请稍后数据刷新'
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
            });
        });
    </script>
@endsection
