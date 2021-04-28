@extends('layouts.modal')

{{-- page style --}}
@section('page-styles')
    <link rel="stylesheet" type="text/css" href="{{ asset('css/plugins/forms/validation/form-validation.css') }}">
@endsection

@section('content')
    <form id="form" class="form" method="post" action="{{ route('backend.ad.update', $data->id) }}" novalidate>
        @method('PUT')
        <div class="form-body">
            <div class="row">
                <div class="col-6">
                    <div class="form-group">
                        <label for="input-name"><span class="danger">*</span>广告位</label>
                        <div class="controls">
                            <select id="select-type" class="form-control" name="space_id">
                                @foreach($ad_spaces as $key => $item)
                                    <option value="{{$item->id}}" @if($item->id == $data->space_id)  selected @endif>{{$item->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group">
                        <label for="input-name"><span class="danger">*</span>广告名称</label>
                        <div class="controls">
                            <input type="text" id="input-name" class="form-control" name="name"
                                   placeholder="请输入广告名称"
                                   required
                                   data-validation-required-message="广告名称"
                                   value="{{$data->name}}"
                            >
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group">
                        <label for="input-name"><span class="danger">*</span>排序</label>
                        <div class="controls">
                            <input type="text" id="input-name" class="form-control" name="sort"
                                   placeholder="请输入排序顺序"
                                   required
                                   data-validation-required-message="排序顺序"
                                   value="{{$data->sort}}"
                            >
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group">
                        <label for="select-platform"><span class="danger">*</span>所属平台</label>
                        <div class="controls">
                            <select id="select-platform" class="form-control" name="platform">
                                <option value="1" @if($data->platform == 1) selected @endif>安卓</option>
                                <option value="2" @if($data->platform == 2) selected @endif>IOS</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group">
                        <label for="jump-type"><span class="danger">*</span>跳转类型</label>
                        <div class="controls">
                            <select id="jump-type" class="form-control" name="jump_type">
                                <option value="">请选择跳转类型</option>
                                @foreach ($jump_type as $key => $val)
                                    <option value="{{ $key }}" @if($data->jump_type == $key) selected @endif>{{ $val }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="url col-6 @if($data->jump_type == 5) hidden @endif">
                    <div class="form-group">
                        <label for="input-url">广告地址</label>
                        <div class="controls">
                            <input type="text" id="input-url" class="form-control" name="url"
                                   placeholder="请输入网站地址"
                                   data-validation-required-message="请输入网站地址"
                                   value="{{$data->url}}"
                            >
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group">
                        <label for="input-times">显示时间</label>
                        <div class="controls">
                            <input type="text" id="input-times" class="form-control" name="show_time"
                                   placeholder="秒"
                                   data-validation-required-message="秒"
                                   value="{{$data->show_time}}"
                            >
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group">
                        <label for="input-name">状态</label>
                        <div class="controls">
                            <ul class="list-unstyled mb-0">
                                <li class="d-inline-block mr-2 mb-1">
                                    <fieldset>
                                        <div class="radio">
                                            <input type="radio" name="status" id="status_active_1" value="1" @if($data->status == 1) checked @endif >
                                            <label for="status_active_1">上架</label>
                                        </div>
                                    </fieldset>
                                </li>
                                <li class="d-inline-block mr-2 mb-1">
                                    <fieldset>
                                        <div class="radio">
                                            <input type="radio" name="status" id="status_active_2" value="-1" @if($data->status == -1) checked @endif >
                                            <label for="status_active_2">下架</label>
                                        </div>
                                    </fieldset>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group">
                        <label for="input-username"><span class="danger">*</span>广告图</label>
                        <div class="input-group">
                            <div class="custom-file">
                                <input type="hidden" name="image" value="{{$data->image}}">
                                <input type="file" class="custom-file-input" id="vertical-thumb" name="image" >
                                <label class="custom-file-label" for="vertical-thumb">请选择文件</label>
                            </div>
                        </div>
                    </div>
                    <div class="input-group mb-1">
                        <img src="{{ $data->image_thumb }}" width="160px">
                    </div>
                </div>
                <div class="col-12 justify-content-end">
                    <button type="submit" class="btn btn-primary mr-1 mb-1">提交</button>
                    <button type="reset" class="btn btn-light-secondary mr-1 mb-1">还原</button>
                </div>
            </div>
        </div>
    </form>
@endsection


{{-- page scripts --}}
@section('page-scripts')
    <script src="{{ asset('js/scripts/forms/validation/form-validation.js') }}"></script>
    <script>
		$(document).ready(function () {

            $('#jump-type').on('change', function () {
                if ($(this).val() == 5){
                    $('.url').addClass('hidden');
                    return;
                }

                $('.url').removeClass('hidden');
            });


            $('#form').submit(function (e) {
                e.preventDefault();
                $.ajax({
                    url: $(this).attr('action'),
                    type: $(this).attr('method'),
                    data: new FormData(this),
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    contentType: false,
                    processData: false,
                    dataType: 'json',
                    success: function (res) {
                        console.log(res);

                        if (res.code == 200) {
                            // iframe.blade.php
                            parent.$.hideModal();

                            // iframeLayoutMaster.blade.php
                            parent.parent.$.reloadIFrame({
                                title: '提交成功',
                                message: '请稍后数据刷新'
                            });
                        } else {
                            parent.$.toast({
                                type: 'error',
                                title: '提交失败',
                                message: res.msg
                            });
                        }
                    },
                    error: function (xhr, textStatus, errorThrown) {
                        if (settings.debug == true) {
                            console.log(xhr);
                            console.log(textStatus);
                            console.log(errorThrown)
                        }
                    }
                });
            });
		});
    </script>
@endsection

