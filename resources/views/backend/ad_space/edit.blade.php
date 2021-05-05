@extends('layouts.modal')

{{-- page style --}}
@section('page-styles')
    <link rel="stylesheet" type="text/css" href="{{ asset('css/plugins/forms/validation/form-validation.css') }}">
@endsection

@section('content')
    <form id="form" class="form" method="post" action="{{ route('backend.ad_space.update', $data->id) }}" novalidate>
        @method('PUT')
        <div class="form-body">
            <div class="row">
                <div class="col-4">
                    <div class="form-group">
                        <label for="input-name">广告位类型</label>
                        <div class="controls">
                            <ul class="list-unstyled mb-0">
                                <li class="d-inline-block mr-2 mb-1">
                                    <fieldset>
                                        <div class="radio">
                                            <input type="radio" name="class" id="class_active_1" value="video" @if($data->class == 'video'){{'checked'}}@endif>
                                            <label for="class_active_1">动画</label>
                                        </div>
                                    </fieldset>
                                </li>
                                <li class="d-inline-block mr-2 mb-1">
                                    <fieldset>
                                        <div class="radio">
                                            <input type="radio" name="class" id="class_active_2" value="comic" @if($data->class == 'comic'){{'checked'}}@endif>
                                            <label for="class_active_2">漫画</label>
                                        </div>
                                    </fieldset>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-4">
                    <div class="form-group">
                        <label for="input-name">状态</label>
                        <div class="controls">
                            <ul class="list-unstyled mb-0">
                                <li class="d-inline-block mr-2 mb-1">
                                    <fieldset>
                                        <div class="radio">
                                            <input type="radio" name="status" id="status_active_1" value="1" @if($data->status == 1){{'checked'}}@endif>
                                            <label for="status_active_1">上架</label>
                                        </div>
                                    </fieldset>
                                </li>
                                <li class="d-inline-block mr-2 mb-1">
                                    <fieldset>
                                        <div class="radio">
                                            <input type="radio" name="status" id="status_active_2" value="-1" @if($data->status == -1){{'checked'}}@endif>
                                            <label for="status_active_2">下架</label>
                                        </div>
                                    </fieldset>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-4">
                    <div class="form-group">
                        <label for="select-type">接入广告SDK</label>
                        <div class="controls">
                            <ul class="list-unstyled mb-0">
                                <li class="d-inline-block mr-2 mb-1">
                                    <fieldset>
                                        <div class="radio">
                                            <input type="radio" name="sdk" id="sdk_active1" value="1" @if($data->sdk == 1){{'checked'}}@endif>
                                            <label for="sdk_active1">开启</label>
                                        </div>
                                    </fieldset>
                                </li>
                                <li class="d-inline-block mr-2 mb-1">
                                    <fieldset>
                                        <div class="radio">
                                            <input type="radio" name="sdk" id="sdk_active2" value="-1" @if($data->sdk == -1){{'checked'}}@endif>
                                            <label for="sdk_active2">关闭</label>
                                        </div>
                                    </fieldset>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group">
                        <label for="input-name">备注</label>
                        <div class="controls">
                            <textarea class="form-control" name="remark" rows="3" placeholder="请输入备注">{{$data->remark}}</textarea>
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


{{-- page scripts --}}
@section('page-scripts')
    <script src="{{ asset('js/scripts/forms/validation/form-validation.js') }}"></script>
    <script>
		$(document).ready(function () {


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
    </script>
@endsection

