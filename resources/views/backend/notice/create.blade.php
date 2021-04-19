@extends('layouts.modal')

{{-- page style --}}
@section('page-styles')
    <link rel="stylesheet" type="text/css" href="{{ asset('css/plugins/forms/validation/form-validation.css') }}">
@endsection

@section('content')
    <form id="form" class="form" method="post" action="{{ route('backend.notice.store') }}" novalidate>
        <div class="form-body">
            <div class="row">
                <div class="col-12">
                    <div class="form-group">
                        <label for="input-username"><span class="danger">*</span>公告标题</label>
                        <div class="controls">
                            <input type="text" id="input-username" class="form-control" name="notice_title"
                                   placeholder="请输入公告标题"
                                   required
                                   data-validation-required-message="请输入公告标题">
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group">
                        <label for="input-username"><span class="danger">*</span>公告详情</label>
                        <div class="controls">
                            <textarea name="notice_content"  class="form-control" style="width:100%;height:160px;" placeholder="请输入内容"></textarea>
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group">
                        <label for="input-username">公告关键词</label>
                        <div class="controls">
                            <input type="text" id="input-username" class="form-control" name="notice_keyword"
                                   placeholder="请输入公告关键词"
                                   required
                                   data-validation-required-message="请输入公告关键词">
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group">
                        <label for="input-username">字体颜色</label>
                        <div class="controls">
                            <input type="text" name="dyestuff" class="form-control" placeholder="字体颜色：格式#4e6ef2" value="#4e6ef2">
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group">
                        <label for="input-username"><span class="danger">*</span>复制内容</label>
                        <div class="controls">
                            <textarea name="copy_content"  class="form-control" style="width:100%;height:160px;" placeholder="请输入内容"></textarea>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group">
                        <label for="input-username"><span class="danger">*</span>新旧版本</label>
                        <div class="controls">
                            <ul class="list-unstyled mb-0">
                                <li class="d-inline-block mr-2 mb-1">
                                    <fieldset>
                                        <div class="radio radio-shadow">
                                            <input type="radio" id="radioshadow2"  name="edition_type" value="2" checked>
                                            <label for="radioshadow2">新版</label>
                                        </div>
                                    </fieldset>
                                </li>
                                <li class="d-inline-block mr-2 mb-1">
                                    <fieldset>
                                        <div class="radio radio-shadow">
                                            <input type="radio" id="radioshadow1" name="edition_type" value="1" >
                                            <label for="radioshadow1">旧版</label>
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
    <script src="{{ asset('vendors/js/forms/validation/jqBootstrapValidation.js') }}"></script>
@endsection

{{-- page scripts --}}
@section('page-scripts')
    <script src="{{ asset('js/scripts/forms/validation/form-validation.js') }}"></script>
    <script>
		$(document).ready(function () {
			$('#form').submit(function (e) {
				e.preventDefault();

				$.request({
					url     : $(this).attr('action'),
					type    : $(this).attr('method'),
                    data    : $('#form').serialize(),
					// debug: true,
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
