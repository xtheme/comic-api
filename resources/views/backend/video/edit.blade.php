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
                        <label><span class="danger">*</span> 名称</label>
                        <div class="controls">
                            <input type="text" class="form-control" name="title" value="{{ $video->title }}" placeholder="">
                        </div>
                    </div>
                </div>
                <div class="col-4">
                    <div class="form-group">
                        <label>番号</label>
                        <div class="controls">
                            <input type="text" class="form-control" name="number" value="{{ $video->number }}" placeholder="">
                        </div>
                    </div>
                </div>
                <div class="col-4">
                    <div class="form-group">
                        <label>片商</label>
                        <div class="controls">
                            <input type="text" class="form-control" name="producer" value="{{ $video->producer }}" placeholder="">
                        </div>
                    </div>
                </div>
                <div class="col-4">
                    <div class="form-group">
                        <label>女優</label>
                        <div class="controls">
                            <input type="text" class="form-control" name="actor" value="{{ join(',', $video->actor) }}" placeholder="">
                        </div>
                    </div>
                </div>
                <div class="col-4">
                    <div class="form-group">
                        <label>影片长度</label>
                        <div class="controls">
                            <input type="text" class="form-control" name="length" value="{{ $video->length }}" placeholder="">
                        </div>
                    </div>
                </div>
                <div class="col-4">
                    <div class="form-group">
                        <label>马赛克</label>
                        <div class="controls">
                            <select class="form-control" name="mosaic">
                                @foreach ($mosaic_options as $key => $val)
                                    <option value="{{ $key }}" @if($key == $video->mosaic){{'selected'}}@endif>{{ $val }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-4">
                    <div class="form-group">
                        <label>类型</label>
                        <div class="controls">
                            <select class="form-control" name="style">
                                @foreach ($style_options as $key => $val)
                                    <option value="{{ $key }}" @if($key == $video->style){{'selected'}}@endif>{{ $val }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-4">
                    <div class="form-group">
                        <label>字幕</label>
                        <div class="controls">
                            <select class="form-control" name="subtitle">
                                @foreach ($subtitle_options as $key => $val)
                                    <option value="{{ $key }}" @if($key == $video->subtitle){{'selected'}}@endif>{{ $val }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-4">
                    <div class="form-group">
                        <label>角标</label>
                        <div class="controls">
                            <select class="form-control" name="ribbon">
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
                <div class="col-6">
                    <div class="form-group">
                        <label>封面图路徑</label>
                        <div class="controls">
                            <input type="text" class="form-control" name="cover" value="{{ $video->getRawOriginal('cover') }}" placeholder="" readonly>
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group">
                        <label>串流路徑</label>
                        <div class="controls">
                            <input type="text" class="form-control" name="hls" value="{{ $video->getRawOriginal('hls') }}" placeholder="" readonly>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group">
                        <label>简介</label>
                        <textarea name="description" class="form-control" rows="5" placeholder="内容简介">{{ $video->description }}</textarea>
                    </div>
                </div>
                @foreach($categories as $title => $item)
                    <div class="col-12">
                        <div class="form-group">
                            <label>{{ $title }}</label>
                            <div class="controls">
                                <div class="row mt-1">
                                    @foreach($item['tags'] as $tag)
                                        <div class="col-2">
                                            <div class="form-group">
                                                <div class="controls">
                                                    <fieldset>
                                                        <div class="checkbox">
                                                            <input type="checkbox" name="tags[{{ $item['code'] }}][]" id="{{ $tag }}" value="{{ $tag }}" @if(in_array($tag, $video->keywords)){{'checked'}}@endif>
                                                            <label for="{{ $tag }}">{{ $tag }}</label>
                                                        </div>
                                                    </fieldset>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
                <div class="col-12 d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary">提交</button>
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
