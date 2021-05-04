@extends('layouts.modal')

@section('vendor-styles')
@endsection

@section('page-styles')
@endsection

@section('content')
    <form id="form" class="form" method="post" action="{{ route('backend.video_series.update', [$series->video_id, $series->id]) }}" novalidate>
        <div class="form-body">
            <div class="row">
                <div class="col-4">
                    <div class="form-group">
                        <label>动画域名</label>
                        <div class="controls">
                            <select class="form-control" name="video_domain_id">
                                @foreach($domains as $domain)
                                    <option value="{{ $domain->id }}" @if($series->video_domain_id == $domain->id){{'selected'}}@endif>{{ $domain->title }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-8">
                    <div class="form-group">
                        <label>播放链接</label>
                        <div class="controls">
                            <div class="input-group">
                                <input type="text" class="form-control" name="link" value="{{ $series->link }}" placeholder="">
                                <div class="input-group-append">
                                    <span class="input-group-text">后缀必须为 m3u8</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-10">
                    <div class="form-group">
                        <label>动画标题</label>
                        <div class="controls">
                            <input type="text" class="form-control" name="title" value="{{ $series->title }}" placeholder="">
                        </div>
                    </div>
                </div>
                <div class="col-2">
                    <div class="form-group">
                        <label>集数</label>
                        <fieldset>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">第</span>
                                </div>
                                <input type="number" class="form-control" name="episode" value="{{ $series->episode }}">
                                <div class="input-group-append">
                                    <span class="input-group-text">集</span>
                                </div>
                            </div>
                        </fieldset>
                    </div>
                </div>
                <div class="col-3">
                    <div class="form-group">
                        <label>视频长度</label>
                        <div class="controls">
                            <div class="input-group">
                                <input type="text" class="form-control" name="length" value="{{ $series->length }}" placeholder="">
                                <div class="input-group-append">
                                    <span class="input-group-text">秒</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-1"></div>
                <div class="col-3">
                    <div class="form-group">
                        <label for="input-email">付费观看</label>
                        <div class="controls">
                            <ul class="list-unstyled mb-0">
                                <li class="d-inline-block mr-2 mb-1">
                                    <fieldset>
                                        <div class="radio">
                                            <input type="radio" name="vip" id="series-free-1" value="-1" @if($series->vip == -1){{'checked'}}@endif>
                                            <label for="series-free-1">免费</label>
                                        </div>
                                    </fieldset>
                                </li>
                                <li class="d-inline-block mr-2 mb-1">
                                    <fieldset>
                                        <div class="radio">
                                            <input type="radio" name="vip" id="series-free-2" value="1" @if($series->vip == 1){{'checked'}}@endif>
                                            <label for="series-free-2">VIP</label>
                                        </div>
                                    </fieldset>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-3">
                    <div class="form-group">
                        <label for="input-email">状态</label>
                        <div class="controls">
                            <ul class="list-unstyled mb-0">
                                <li class="d-inline-block mr-2 mb-1">
                                    <fieldset>
                                        <div class="radio">
                                            <input type="radio" name="status" id="series-status-1" value="1" @if($series->status == 1){{'checked'}}@endif>
                                            <label for="series-status-1">上架</label>
                                        </div>
                                    </fieldset>
                                </li>
                                <li class="d-inline-block mr-2 mb-1">
                                    <fieldset>
                                        <div class="radio">
                                            <input type="radio" name="status" id="series-status-2" value="-1" @if($series->status == -1){{'checked'}}@endif>
                                            <label for="series-status-2">下架</label>
                                        </div>
                                    </fieldset>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-2"></div>
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
                    data    : new FormData(this),
                    multipart: true,
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