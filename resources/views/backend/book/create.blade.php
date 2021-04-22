@extends('layouts.modal')

{{-- page style --}}
@section('page-styles')
@endsection

@section('content')
    <form id="form" class="form" method="post" enctype="multipart/form-data" action="{{ route('backend.book.store') }}">
        <div class="form-body">
            <div class="row">
                <div class="col-12">
                    <div class="form-group">
                        <label><span class="danger">*</span> 漫画分类</label>
                        <ul class="list-unstyled mb-0">
                            @foreach($tags as $tag)
                                <li class="d-inline-block mr-2 mb-1 checkbox">
                                    <input type="checkbox" class="checkbox-input" id="tag-{{ $tag->id }}" name="tag[]" value="{{ $tag->name }}">
                                    <label for="tag-{{ $tag->id }}">{{ $tag->name }}</label>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                <div class="col-8">
                    <div class="form-group">
                        <label><span class="danger">*</span> 漫画名称</label>
                        <div class="controls">
                            <input type="text" class="form-control" name="book_name">
                        </div>
                    </div>
                </div>
                <div class="col-4">
                    <div class="form-group">
                        <label for="input-email">类型</label>
                        <div class="controls">
                            <ul class="list-unstyled mb-0">
                                <li class="d-inline-block mr-2 mb-1">
                                    <fieldset>
                                        <div class="radio">
                                            <input type="radio" name="cartoon_type" id="cartoon-type-1" value="1" checked>
                                            <label for="cartoon-type-1">日漫</label>
                                        </div>
                                    </fieldset>
                                </li>
                                <li class="d-inline-block mr-2 mb-1">
                                    <fieldset>
                                        <div class="radio">
                                            <input type="radio" name="cartoon_type" id="cartoon-type-2" value="2">
                                            <label for="cartoon-type-2">韩漫</label>
                                        </div>
                                    </fieldset>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group">
                        <label><span class="danger">*</span> 内容简介</label>
                        <textarea name="book_desc" class="form-control" rows="3" placeholder="内容简介"></textarea>
                    </div>
                </div>
                <div class="col-4">
                    <div class="form-group">
                        <label><span class="danger">*</span> 作者</label>
                        <div class="controls">
                            <input type="text" class="form-control" name="pen_name" value="">
                        </div>
                    </div>
                </div>
                <div class="col-4">
                    <div class="form-group">
                        <label>阅读数</label>
                        <div class="controls">
                            <input type="text" class="form-control" name="view" value="" placeholder="0">
                        </div>
                    </div>
                </div>
                <div class="col-4">
                    <div class="form-group">
                        <label>收藏数</label>
                        <div class="controls">
                            <input type="text" class="form-control" name="collect" value="" placeholder="0">
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group">
                        <label>竖向封面</label>
                        <div class="input-group">
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" id="vertical-thumb" name="book_thumb">
                                <label class="custom-file-label" for="vertical-thumb">请选择文件</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group">
                        <label>横向封面</label>
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" id="horizontal-thumb" name="book_thumb2">
                            <label class="custom-file-label" for="horizontal-thumb">请选择文件</label>
                        </div>
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
@endsection

{{-- page scripts --}}
@section('page-scripts')
    <script>
		$(document).ready(function () {
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
