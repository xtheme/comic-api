@extends('layouts.modal')

{{-- page style --}}
@section('page-styles')
{{--    <link rel="stylesheet" type="text/css" href="{{ asset('vendors/css/pickers/daterange/daterangepicker.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/plugins/forms/validation/form-validation.css') }}">--}}
@endsection

@section('content')
    <form id="form" class="form" method="post" action="{{ route('backend.user.update', $user->id) }}" novalidate>
        @method('put')
        <div class="form-body">
            <div class="row">
                <div class="col-12">
                    <div class="form-group">
                        <label>头像</label>
                        <div class="input-group">
                            <input type="file" class="hidden-file-upload" data-path="avatar/{{ $user->id }}">
                            <input type="text" class="form-control image-path" name="avatar" value="{{ $user->getRawOriginal('avatar') ?? '' }}" autocomplete="off" aria-describedby="input-file-addon">
                            <div class="input-group-append" id="input-file-addon">
                                <button class="btn btn-primary upload-image" type="button">上传</button>
                            </div>
                        </div>
                        <div class="upload-image-callback">@if($user->getRawOriginal('avatar'))<img src="{{ $user->avatar }}">@endif</div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group">
                        <label><span class="danger">*</span> 昵称</label>
                        <div class="controls">
                            <input type="text" class="form-control" name="username"
                                   placeholder="请填写用户昵称"
                                   value="{{ $user->username }}">
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group">
                        <label for="select-sex">性别</label>
                        <div class="controls">
                            <select id="select-sex" class="form-control" name="sex">
                                <option value="1" @if($user->sex == 1){{'selected'}}@endif>男</option>
                                <option value="2" @if($user->sex == 2){{'selected'}}@endif>女</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group">
                        <label>手机号</label>
                        <div class="controls">
                            <input type="text" class="form-control" name="mobile"
                                   value="{{ $user->phone }}"
                                   readonly="readonly">
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group">
                        <label>设备号</label>
                        <div class="controls">
                            <input type="text" class="form-control" name="device_id"
                                   value="{{$user->device_id}}"
                                   readonly="readonly">
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
                                            <input type="radio" name="status" id="status-active" value="1" @if($user->status == 1){{'checked'}}@endif>
                                            <label for="status-active">启用</label>
                                        </div>
                                    </fieldset>
                                </li>
                                <li class="d-inline-block mr-2 mb-1">
                                    <fieldset>
                                        <div class="radio">
                                            <input type="radio" name="status" id="status-inactive" value="0" @if($user->status == 0){{'checked'}}@endif>
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
                        <label>积分</label>
                        <div class="controls">
                            <input type="text" class="form-control" name="score" value="{{$user->score ?? 0}}">
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group">
                        <label>用户简介</label>
                        <textarea name="sign" class="form-control" rows="3" placeholder="请输入用户简介，最长255个字符">{{$user->sign}}</textarea>
                    </div>
                </div>
                {{--<div class="col-6">
                    <div class="form-group">
                        <label for="input-score">VIP到期时间</label>
                        <div class="controls">
                            <input type="text" class="form-control" name="subscribed_until" value="{{$user->subscribed_until}}">
                        </div>
                    </div>
                </div>--}}
                <div class="col-12 d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary mr-1">提交</button>
                    <button type="reset" class="btn btn-light-secondary mr-1">还原</button>
                </div>
            </div>
        </div>
    </form>
@endsection

{{-- vendor scripts --}}
@section('vendor-scripts')
    {{--<script src="{{ asset('vendors/js/extensions/moment.min.js') }}"></script>
    <script src="{{ asset('vendors/js/pickers/daterange/daterangepicker.js') }}"></script>
    <script src="{{ asset('vendors/js/extensions/locale/zh-cn.js') }}"></script>--}}
    <script src="{{ asset('vendors/js/forms/validation/jqBootstrapValidation.js') }}"></script>
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

            // 日期時間選擇
            // moment.locale('zh-cn');
            //
            // let $created = $('input[name="subscribed_until"]');
            //
            // $created.daterangepicker({
            //     drops: 'up',
            //     buttonClasses: 'btn',
            //     applyClass: 'btn-success',
            //     cancelClass: 'btn-danger',
            //     autoUpdateInput: false,
            //     locale: {
            //         direction       : 'ltr',
            //         format          : moment.localeData().longDateFormat('L'),
            //         separator       : ' - ',
            //         applyLabel      : '确定',
            //         cancelLabel     : '取消',
            //         weekLabel       : 'W',
            //         customRangeLabel: 'Custom Range',
            //         daysOfWeek      : moment.weekdaysMin(),
            //         monthNames      : moment.monthsShort(),
            //         firstDay        : moment.localeData().firstDayOfWeek()
            //     },
            //     singleDatePicker: true,
            //     timePicker: true,
            //     timePicker24Hour: true,
            //     timePickerSeconds: true,
            //     timePickerIncrement: 5,
            //     // minDate: moment().subtract(1, 'days'),
            // });
            //
            // $created.on('apply.daterangepicker', function(ev, picker) {
            //     $(this).val(picker.startDate.format('YYYY-MM-DD HH:mm:ss'));
            // });
            //
            // $created.on('cancel.daterangepicker', function(ev, picker) {
            //     $(this).val('');
            // });
		});
    </script>
@endsection
