@extends('layouts.modal')

{{-- page style --}}
@section('page-styles')
@endsection

@section('content')
    <form id="form" class="form" method="post" action="{{ route('backend.config.update', $config->id) }}" novalidate>
        @method('PUT')
        <div class="form-body">
            <div class="row">
                <div class="col-12">
                    <div class="form-group">
                        <label>配置组</label>
                        <div class="controls">
                            <input type="text" class="form-control" name="code" value="{{ $groups[$config->group] }}" readonly>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group">
                        <label>配置键</label>
                        <div class="controls">
                            <input type="text" class="form-control" name="code" value="{{ $config->code }}" readonly>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group">
                        <label>配置描述</label>
                        <div class="controls">
                            <input type="text" class="form-control" name="name" placeholder="配置描述" value="{{ $config->name }}">
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group">
                        <label>配置型别</label>
                        <div class="controls">
                            <select id="select-type" class="form-control" name="type">
                                <option value="string" @if($config->type == 'string'){{'selected'}}@endif>字符串</option>
                                <option value="switch" @if($config->type == 'switch'){{'selected'}}@endif>开关</option>
                                <option value="text" @if($config->type == 'text'){{'selected'}}@endif>文本</option>
                                <option value="array" @if($config->type == 'array'){{'selected'}}@endif>数组</option>
                                <option value="json" @if($config->type == 'json'){{'selected'}}@endif>JSON</option>
                                <option value="image" @if($config->type == 'image'){{'selected'}}@endif>图片</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group">
                        <label><span class="danger">*</span> 配置值</label>
                        <div class="controls">
                            <div id="input-value-string">
                                <input type="text" class="form-control" name="content" placeholder="配置值" value="{{ $config->content }}">
                            </div>
                            <div id="input-value-switch" class="hidden">
                                <select class="form-control">
                                    <option value="1" @if($config->content == 1){{'selected'}}@endif>启用</option>
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
{{--    <script src="{{ asset('vendors/js/editors/CKEditor/ckeditor.js') }}"></script>--}}
@endsection

{{-- page scripts --}}
@section('page-scripts')
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

		function switchType($type) {
			console.log($type);
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

			if ($type == 'text' || $type == 'array' || $type == 'json') {
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

