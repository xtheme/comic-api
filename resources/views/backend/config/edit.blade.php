@extends('layouts.modal')

{{-- page style --}}
@section('page-styles')
    <link rel="stylesheet" type="text/css" href="{{ asset('css/plugins/forms/validation/form-validation.css') }}">
@endsection

@section('content')
    <form id="form" class="form" method="post" action="{{ route('backend.config.update', $config->id) }}" novalidate>
        @method('PUT')
        <div class="form-body">
            <div class="row">
                <div class="col-12">
                    <div class="form-group">
                        <label for="input-name"><span class="danger">*</span> 配置分類</label>
                        <div class="controls">
                            <select id="select-type" class="form-control" name="group">
                                @foreach($tags as $tag => $name)
                                    <option value="{{$tag}}"  @if($tag == $config->group){{'selected'}}@endif>{{$name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group">
                        <label for="input-name"><span class="danger">*</span> 配置描述</label>
                        <div class="controls">
                            <input type="text" id="input-name" class="form-control" name="name"
                                   placeholder="配置描述"
                                   required
                                   data-validation-required-message="请填写配置描述"
                                   value="{{ $config->name }}">
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group">
                        <label for="select-type">选择配置类型</label>
                        <div class="controls">
                            <select id="select-type" class="form-control" name="type">
                                <option value="string" @if($config->type == 'string'){{'selected'}}@endif>字符串</option>
                                <option value="switch" @if($config->type == 'switch'){{'selected'}}@endif>开关</option>
                                <option value="text" @if($config->type == 'text'){{'selected'}}@endif>富文本</option>
                                <option value="image" @if($config->type == 'image'){{'selected'}}@endif>图片</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group">
                        <label for="input-key"><span class="danger">*</span> 代码</label>
                        <div class="controls">
                            <input type="text" id="input-key" class="form-control" name="code"
                                   placeholder="key"
                                   required
                                   data-validation-required-message="请填写关键字"
                                   value="{{ $config->code }}">
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group">
                        <label for="input-value"><span class="danger">*</span> 配置值</label>
                        <div class="controls">
                            <div id="input-value-string">
                                <input type="text" class="form-control" name="content" placeholder="配置值" value="{{ $config->content }}">
                            </div>
                            <div id="input-value-switch" class="hidden">
                                <select class="form-control">
                                    <option value="1" @if($config->content == 1){{'selected'}}@endif>开启</option>
                                    <option value="0" @if($config->content == 0){{'selected'}}@endif>关闭</option>
                                </select>
                            </div>
                            <div id="input-value-text" class="hidden">
{{--                                <textarea id="editor">{!! html_entity_decode($config->content) !!}</textarea>--}}
                                <textarea class="form-control" rows="5">{!! html_entity_decode($config->content) !!}</textarea>
                            </div>
                            <div id="input-value-image" class="hidden">
                                <div class="input-group">
                                    <input type="file" class="hidden-file-upload" data-path="config/{{ $config->id }}">
                                    <input type="text" class="form-control image-path" autocomplete="off" aria-describedby="input-file-addon" value="{{ $config->content }}">
                                    <div class="input-group-append" id="input-file-addon">
                                        <button class="btn btn-primary upload-image" type="button">上传</button>
                                    </div>
                                </div>
                                <div class="upload-image-callback">@if($config->type == 'image')<img src="{{ $config->content }}">@endif</div>
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
    <script src="{{ asset('vendors/js/forms/validation/jqBootstrapValidation.js') }}"></script>
{{--    <script src="{{ asset('vendors/js/editors/CKEditor/ckeditor.js') }}"></script>--}}
@endsection

{{-- page scripts --}}
@section('page-scripts')
    <script src="{{ asset('js/scripts/forms/validation/form-validation.js') }}"></script>
    <script>
		$(document).ready(function () {
            // CKEditor
            {{--$.editor({--}}
            {{--    target: document.querySelector('#editor'),--}}
            {{--    uploadUrl: '{{ route('editor.upload', ['common']) }}'--}}
            {{--});--}}

			// INIT
			switchType('{{ $config->type }}');

			$('#select-type').on('change', function () {
				switchType($(this).val());
			});

			$('#form').submit(function (e) {
				e.preventDefault();
                $.request({
					url     : $(this).attr('action'),
					type    : $(this).attr('method'),
					data    : $('#form').serialize(),
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

		function switchType($type) {
			if ($type == 'string') {
				$('#input-value-string').removeClass('hidden').children('input[type="text"]').attr('name', 'content');
				$('#input-value-text').addClass('hidden').children('textarea').attr('name', '');
				$('#input-value-image').addClass('hidden').children().children('input[type="text"]').attr('name', '');
				$('#input-value-switch').addClass('hidden').children('select').attr('name', '');
			}

			if ($type == 'switch') {
				$('#input-value-switch').removeClass('hidden').children('select').attr('name', 'content');
				$('#input-value-text').addClass('hidden').children('textarea').attr('name', '');
				$('#input-value-string').addClass('hidden').children('input[type="text"]').attr('name', '');
				$('#input-value-image').addClass('hidden').children().children('input[type="text"]').attr('name', '');
			}

			if ($type == 'text') {
				$('#input-value-text').removeClass('hidden').children('textarea').attr('name', 'content');
				$('#input-value-string').addClass('hidden').children('input[type="text"]').attr('name', '');
				$('#input-value-image').addClass('hidden').children().children('input[type="text"]').attr('name', '');
				$('#input-value-switch').addClass('hidden').children('select').attr('name', '');
			}

			if ($type == 'image') {
				$('#input-value-image').removeClass('hidden').children().children('input[type="text"]').attr('name', 'content');
				$('#input-value-string').addClass('hidden').children('input[type="text"]').attr('name', '');
				$('#input-value-text').addClass('hidden').children('textarea').attr('name', '');
				$('#input-value-switch').addClass('hidden').children('select').attr('name', '');
			}
		}
    </script>
@endsection

