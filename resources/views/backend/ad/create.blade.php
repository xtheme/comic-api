@extends('layouts.modal')

{{-- page style --}}
@section('page-styles')
    <link rel="stylesheet" type="text/css" href="{{ asset('css/plugins/forms/validation/form-validation.css') }}">
@endsection

@section('content')
    <form id="form" class="form" method="post"  enctype="multipart/form-data" action="{{ route('backend.ad.store') }}">
        <div class="form-body">
            <div class="row">
                <div class="col-6">
                    <div class="form-group">
                        <label for="input-name"><span class="danger">*</span>广告位</label>
                        <div class="controls">
                            <select id="select-type" class="form-control" name="space_id">
                                @foreach($ad_spaces as $key => $item)
                                    <option value="{{$item->id}}" >{{$item->name}}</option>
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
                                   data-validation-required-message="广告名称">
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
                                   data-validation-required-message="排序顺序">
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group">
                        <label for="select-platform"><span class="danger">*</span>所属平台</label>
                        <div class="controls">
                            <select id="select-platform" class="form-control" name="platform">
                                <option value="1">安卓</option>
                                <option value="2">IOS</option>
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
                                    <option value="{{ $key }}">{{ $val }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="url col-6">
                    <div class="form-group">
                        <label for="input-url">广告地址</label>
                        <div class="controls">
                            <input type="text" id="input-url" class="form-control" name="url"
                                   placeholder="请输入网站地址"
                                   data-validation-required-message="请输入网站地址">
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
                                   value="0"
                            >
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group">
                        <label for="input-name">状态</label>
                        <div class="controls">
                            <select id="select-platform" class="form-control" name="status">
                                <option value="1">上架</option>
                                <option value="-1">下架</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group">
                        <label for="input-username"><span class="danger">*</span>广告图</label>
                        <div class="input-group">
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" id="vertical-thumb" name="image">
                                <label class="custom-file-label" for="vertical-thumb">请选择文件</label>
                            </div>
                        </div>
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

{{-- vendor scripts --}}
@section('vendor-scripts')
@endsection

{{-- page scripts --}}
@section('page-scripts')
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
