@extends('layouts.modal')

{{-- page style --}}
@section('page-styles')
    <link rel="stylesheet" type="text/css" href="{{ asset('vendors/css/pickers/daterange/daterangepicker.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/plugins/forms/validation/form-validation.css') }}">
@endsection

@section('content')
    <form id="form" class="form" method="post" action="{{ route('backend.recomclass.update', $data->id) }}" novalidate>
        @method('put')
        <div class="form-body">
            <div class="row">
                <div class="col-6">
                    <div class="form-group">
                        <label for="input-username"><span class="danger">*</span>排序</label>
                        <div class="controls">
                            <input type="text" id="input-username" class="form-control" name="listorder"
                                   placeholder="请输入排序"
                                   required
                                   data-validation-required-message="请输入排序"
                                   value="{{ $data->listorder }}">
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group">
                        <label for="input-username"><span class="danger">*</span>推荐名称</label>
                        <div class="controls">
                            <input type="text" id="input-username" class="form-control" name="title"
                                   placeholder="请输入推荐名称"
                                   required
                                   data-validation-required-message="请输入推荐名称"
                                   value="{{ $data->title }}">
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group">
                        <label for="input-username"><span class="danger">*</span>图标</label>
                        <div class="controls">
                            <div class="input-group">
                                <input type="text" class="form-control image-path" name="icon" autocomplete="off" aria-describedby="input-file-addon" value="{{ $data->icon }}">
                                <input type="file" class="hidden-file-upload" data-path="icon">
                                <div class="input-group-append" id="input-file-addon">
                                    <button class="btn btn-primary upload-image" type="button">上传</button>
                                </div>
                            </div>
                            <div class="upload-image-callback">
                                <img src="{{ $data->icon }}" width="80">
                            </div>
                            <div class="text-muted"></div>
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group">
                        <label for="input-username"><span class="danger">*</span>展示风格</label>
                        <div class="controls">
                            <select class="form-control" name="style" >
                                @foreach ($style as $key => $item)
                                    <option value="{{ $key }}" @if($data->style == $key) selected @endif>{{ $item }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group">
                        <label for="input-username"><span class="danger">*</span>状态</label>
                        <div class="controls">
                            <ul class="list-unstyled mb-0">
                                <li class="d-inline-block mr-2 mb-1">
                                    <fieldset>
                                        <div class="radio radio-shadow">
                                            <input type="radio" id="radioshadow2"  name="display" value="1" @if($data->display == 1) checked @endif>
                                            <label for="radioshadow2">显示</label>
                                        </div>
                                    </fieldset>
                                </li>
                                <li class="d-inline-block mr-2 mb-1">
                                    <fieldset>
                                        <div class="radio radio-shadow">
                                            <input type="radio" id="radioshadow1" name="display" value="0" @if($data->display == 0) checked @endif>
                                            <label for="radioshadow1">隐藏</label>
                                        </div>
                                    </fieldset>
                                </li>
                            </ul>
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
    <script src="{{ asset('js/scripts/forms/validation/form-validation.js') }}"></script>
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
					// debug: true,
					callback: function (res) {
                        if (res.code == 200) {
                            // iframe.blade.php
                            parent.$.hideModal();

                            // iframeLayoutMaster.blade.php
                            parent.parent.$.reloadIFrame({
                                title  : '提交成功',
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
			});

		});
    </script>
@endsection