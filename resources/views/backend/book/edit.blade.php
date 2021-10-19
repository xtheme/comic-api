@extends('layouts.modal')

@section('vendor-styles')
    <link rel="stylesheet" type="text/css" href="{{ asset('vendors/css/bootstrap-multiselect/bootstrap-multiselect.css') }}">
@endsection

@section('page-styles')
@endsection

@section('content')
    <form id="form" class="form" method="post" action="{{ route('backend.book.update', $book->id) }}">
        @method('PUT')
        <div class="form-body">
            <div class="row">
                <div class="col-12">
                    <div class="form-group">
                        <label>标签分类</label>
                        <div class="controls">
                            <select id="tags-selector" class="form-control" name="tag[]" multiple="multiple">
                                @foreach($tags as $tag)
                                    <option value="{{ $tag }} "@if(in_array($tag, $book->tagged_tags)){{'selected'}}@endif>{{ $tag }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-8">
                    <div class="form-group">
                        <label><span class="danger">*</span> 漫画名称</label>
                        <div class="controls">
                            <input type="text" class="form-control" name="title" value="{{ $book->title }}">
                        </div>
                    </div>
                </div>
                <div class="col-4">
                    <div class="form-group">
                        <label><span class="danger">*</span> 作者</label>
                        <div class="controls">
                            <input type="text" class="form-control" name="author" value="{{ $book->author }}">
                        </div>
                    </div>
                </div>
                <div class="col-4">
                    <div class="form-group">
                        <label>上架状态</label>
                        <div class="controls">
                            <select class="form-control" name="status">
                                @foreach ($status_options as $key => $val)
                                    <option value="{{ $key }}" @if($key == $book->status){{'selected'}}@endif>{{ $val }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-4">
                    <div class="form-group">
                        <label>漫画类型</label>
                        <div class="controls">
                            <select class="form-control" name="type">
                                <option value="1" @if($key == $book->type){{'selected'}}@endif>日漫</option>
                                <option value="2" @if($key == $book->type){{'selected'}}@endif>韩漫</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-4">
                    <div class="form-group">
                        <label for="input-email">连载状态</label>
                        <div class="controls">
                            <select class="form-control" name="end">
                                <option value="0" @if($key == $book->end){{'selected'}}@endif>连载中</option>
                                <option value="1" @if($key == $book->end){{'selected'}}@endif>已完结</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group">
                        <label><span class="danger">*</span> 内容简介</label>
                        <textarea name="description" class="form-control" rows="3" placeholder="内容简介">{{ $book->description }}</textarea>
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group">
                        <label>竖向封面</label>
                        <div class="input-group">
                            <input type="text" class="form-control image-path" name="vertical_cover" value="{{ $book->getRawOriginal('vertical_cover') }}" autocomplete="off">
                            <input type="file" class="hidden-file-upload" data-path="book">
                            <div class="input-group-append" id="input-file-addon">
                                <button class="btn btn-primary upload-image" type="button">上传</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group">
                        <label>横向封面</label>
                        <div class="input-group">
                            <input type="text" class="form-control image-path" name="horizontal_cover" value="{{ $book->getRawOriginal('horizontal_cover') }}" autocomplete="off">
                            <input type="file" class="hidden-file-upload" data-path="book">
                            <div class="input-group-append" id="input-file-addon">
                                <button class="btn btn-primary upload-image" type="button">上传</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 d-flex justify-content-end">
                    <input type="hidden" name="operating" value="1">
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
