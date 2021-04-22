@extends('layouts.modal')

{{-- page style --}}
@section('page-styles')
    <link rel="stylesheet" type="text/css" href="{{ asset('css/plugins/forms/validation/form-validation.css') }}">
@endsection

@section('content')
    <form id="form" class="form" method="post" action="{{ route('backend.user.store') }}" novalidate>
        <div class="form-body">
            <div class="row">
                <div class="col-12">
                    <div class="form-group">
                        <label for="input-username">头像</label>
                        <div class="input-group">
                            <input type="file" class="hidden-file-upload" data-path="avatar">
                            <input type="text" class="form-control image-path" name="avatar" autocomplete="off" aria-describedby="input-file-addon">
                            <div class="input-group-append" id="input-file-addon">
                                <button class="btn btn-primary upload-image" type="button">上传</button>
                            </div>
                        </div>
                        <div class="upload-image-callback"></div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group">
                        <label for="input-username"><span class="danger">*</span> 昵称</label>
                        <div class="controls">
                            <input type="text" id="input-username" class="form-control" name="username" value="{{$username}}"
                                   placeholder="请填写用户昵称"
                                   required
                                   data-validation-required-message="请填写用户昵称">
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group">
                        <label for="select-sex">性别</label>
                        <div class="controls">
                            <select id="select-sex" class="form-control" name="sex">
                                <option value="1" selected>男</option>
                                <option value="2">女</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group">
                        <label for="input-mobile"><span class="danger">*</span> 手机号</label>
                        <div class="controls">
                            <input type="text" class="form-control" name="mobile"
                                   required
                                   data-validation-required-message="请填写用户手机号">
                        </div>
                    </div>
                </div>
                {{--<div class="col-6">
                    <div class="form-group">
                        <label for="input-integral"><span class="danger">*</span> 积分</label>
                        <div class="controls">
                            <input type="text" class="form-control" name="integral" value="{{$user->integral ?? 0}}">
                        </div>
                    </div>
                </div>--}}
                <div class="col-6">
                    <div class="form-group">
                        <label for="input-email">邮箱</label>
                        <div class="controls">
                            <input type="text" class="form-control" name="email">
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group">
                        <label>状态</label>
                        <div class="controls">
                            <ul class="list-unstyled mb-0">
                                <li class="d-inline-block mr-2 mb-1">
                                    <fieldset>
                                        <div class="radio">
                                            <input type="radio" name="status" id="status-active" value="1" checked>
                                            <label for="status-active">正常</label>
                                        </div>
                                    </fieldset>
                                </li>
                                <li class="d-inline-block mr-2 mb-1">
                                    <fieldset>
                                        <div class="radio">
                                            <input type="radio" name="status" id="status-inactive" value="2">
                                            <label for="status-inactive">封禁</label>
                                        </div>
                                    </fieldset>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group">
                        <label>评论状态</label>
                        <div class="controls">
                            <ul class="list-unstyled mb-0">
                                <li class="d-inline-block mr-2 mb-1">
                                    <fieldset>
                                        <div class="radio">
                                            <input type="radio" name="comment_status" id="comment-active" value="1" checked>
                                            <label for="comment-active">正常</label>
                                        </div>
                                    </fieldset>
                                </li>
                                <li class="d-inline-block mr-2 mb-1">
                                    <fieldset>
                                        <div class="radio">
                                            <input type="radio" name="comment_status" id="comment-inactive" value="0">
                                            <label for="comment-inactive">禁言</label>
                                        </div>
                                    </fieldset>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group">
                        <label>用户简介</label>
                        <textarea name="desc" class="form-control" rows="3" placeholder="请输入用户简介，最长255个字符"></textarea>
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
