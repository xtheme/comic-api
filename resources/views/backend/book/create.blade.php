@extends('layouts.modal')

@section('vendor-styles')
    <link rel="stylesheet" type="text/css" href="{{ asset('vendors/css/bootstrap-multiselect/bootstrap-multiselect.css') }}">
@endsection

@section('page-styles')
@endsection

@section('content')
    <form id="form" class="form" method="post" action="{{ route('backend.book.store') }}">
        <div class="form-body">
            <div class="row">
                <div class="col-12">
                    <div class="form-group">
                        <label>标签分类</label>
                        <div class="controls">
                            <select id="tags-selector" class="form-control" name="tag[]" multiple="multiple">
                                @foreach($tags as $tag)
                                    <option value="{{ $tag->name }}">{{ $tag->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-8">
                    <div class="form-group">
                        <label><span class="danger">*</span> 漫画名称</label>
                        <div class="controls">
                            <input type="text" class="form-control" name="title">
                        </div>
                    </div>
                </div>
                <div class="col-4">
                    <div class="form-group">
                        <label><span class="danger">*</span> 作者</label>
                        <div class="controls">
                            <input type="text" class="form-control" name="author" value="">
                        </div>
                    </div>
                </div>
                <div class="col-4">
                    <div class="form-group">
                        <label>上架状态</label>
                        <div class="controls">
                            <select class="form-control" id="select-status" name="status">
                                @foreach ($status_options as $key => $val)
                                    <option value="{{ $key }}">{{ $val }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-4">
                    <div class="form-group">
                        <label for="input-email">漫画类型</label>
                        <div class="controls">
                            <select class="form-control" name="type">
                                <option value="1">日漫</option>
                                <option value="2">韩漫</option>
                            </select>
                        </div>
{{--                        <div class="controls">--}}
{{--                            <ul class="list-unstyled mb-0">--}}
{{--                                <li class="d-inline-block mr-2 mb-1">--}}
{{--                                    <fieldset>--}}
{{--                                        <div class="radio">--}}
{{--                                            <input type="radio" name="type" id="cartoon-type-1" value="1" checked>--}}
{{--                                            <label for="cartoon-type-1">日漫</label>--}}
{{--                                        </div>--}}
{{--                                    </fieldset>--}}
{{--                                </li>--}}
{{--                                <li class="d-inline-block mr-2 mb-1">--}}
{{--                                    <fieldset>--}}
{{--                                        <div class="radio">--}}
{{--                                            <input type="radio" name="type" id="cartoon-type-2" value="2">--}}
{{--                                            <label for="cartoon-type-2">韩漫</label>--}}
{{--                                        </div>--}}
{{--                                    </fieldset>--}}
{{--                                </li>--}}
{{--                            </ul>--}}
{{--                        </div>--}}
                    </div>
                </div>
                <div class="col-4">
                    <div class="form-group">
                        <label for="input-email">连载状态</label>
                        <div class="controls">
                            <select class="form-control" name="end">
                                <option value="-1">连载中</option>
                                <option value="1">已完结</option>
                            </select>
                        </div>
{{--                        <div class="controls">--}}
{{--                            <ul class="list-unstyled mb-0">--}}
{{--                                <li class="d-inline-block mr-2 mb-1">--}}
{{--                                    <fieldset>--}}
{{--                                        <div class="radio">--}}
{{--                                            <input type="radio" name="end" id="end-1" value="-1" checked>--}}
{{--                                            <label for="end-1">连载中</label>--}}
{{--                                        </div>--}}
{{--                                    </fieldset>--}}
{{--                                </li>--}}
{{--                                <li class="d-inline-block mr-2 mb-1">--}}
{{--                                    <fieldset>--}}
{{--                                        <div class="radio">--}}
{{--                                            <input type="radio" name="type" id="end-2" value="1">--}}
{{--                                            <label for="end-2">已完结</label>--}}
{{--                                        </div>--}}
{{--                                    </fieldset>--}}
{{--                                </li>--}}
{{--                            </ul>--}}
{{--                        </div>--}}
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group">
                        <label><span class="danger">*</span> 内容简介</label>
                        <textarea name="description" class="form-control" rows="3" placeholder="内容简介"></textarea>
                    </div>
                </div>
{{--                <div class="col-4">--}}
{{--                    <div class="form-group">--}}
{{--                        <label>阅读数</label>--}}
{{--                        <div class="controls">--}}
{{--                            <input type="text" class="form-control" name="view" value="" placeholder="0">--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--                <div class="col-4">--}}
{{--                    <div class="form-group">--}}
{{--                        <label>收藏数</label>--}}
{{--                        <div class="controls">--}}
{{--                            <input type="text" class="form-control" name="collect" value="" placeholder="0">--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}
                <div class="col-12">
                    <div class="form-group">
                        <label>竖向封面</label>
                        <div class="input-group">
                            <input type="text" class="form-control image-path" name="vertical_cover" autocomplete="off">
                            <input type="file" class="hidden-file-upload" data-path="book">
                            <div class="input-group-append" id="input-file-addon">
                                <button class="btn btn-primary upload-image" type="button">上传</button>
                            </div>
                        </div>
{{--                        <div class="input-group">--}}
{{--                            <div class="custom-file">--}}
{{--                                <input type="file" class="custom-file-input" id="vertical-thumb" name="vertical_cover">--}}
{{--                                <label class="custom-file-label" for="vertical-thumb">请选择文件</label>--}}
{{--                            </div>--}}
{{--                        </div>--}}
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group">
                        <label>横向封面</label>
                        <div class="input-group">
                            <input type="text" class="form-control image-path" name="horizontal_cover" autocomplete="off">
                            <input type="file" class="hidden-file-upload" data-path="book">
                            <div class="input-group-append" id="input-file-addon">
                                <button class="btn btn-primary upload-image" type="button">上传</button>
                            </div>
                        </div>
{{--                        <div class="custom-file">--}}
{{--                            <input type="file" class="custom-file-input" id="horizontal-thumb" name="horizontal_cover">--}}
{{--                            <label class="custom-file-label" for="horizontal-thumb">请选择文件</label>--}}
{{--                        </div>--}}
                    </div>
                </div>
                <div class="col-12 d-flex justify-content-end">
                    <input type="hidden" name="operating" value="1">
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
