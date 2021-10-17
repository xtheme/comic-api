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
                <div class="col-6">
                    <div class="form-group">
                        <label><span class="danger">*</span> 帐号</label>
                        <div class="controls">
                            <input type="text" class="form-control" name="name" placeholder="请填写用户帐号" value="{{ $user->name }}">
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group">
                        <label>Email</label>
                        <div class="controls">
                            <input type="text" class="form-control" name="email" value="{{$user->email}}">
                        </div>
                    </div>
                </div>
                <div class="col-3">
                    <div class="form-group">
                        <label>区码</label>
                        <div class="controls">
                            <input type="text" class="form-control" name="area" value="{{ $user->area }}">
                        </div>
                    </div>
                </div>
                <div class="col-3">
                    <div class="form-group">
                        <label>手机号</label>
                        <div class="controls">
                            <input type="text" class="form-control" name="mobile" value="{{ $user->mobile }}">
                        </div>
                    </div>
                </div>
                <div class="col-3">
                    <div class="form-group">
                        <label>封禁</label>
                        <div class="controls">
                            <select class="form-control" name="subtitle">
                                @foreach ($active_options as $key => $val)
                                    <option value="{{ $key }}" @if($key == $user->status){{'selected'}}@endif>{{ $val }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-3">
                    <div class="form-group">
                        <label>黑名單</label>
                        <div class="controls">
                            <select class="form-control" name="subtitle">
                                @foreach ($ban_options as $key => $val)
                                    <option value="{{ $key }}" @if($key == $user->status){{'selected'}}@endif>{{ $val }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-3">
                    <div class="form-group">
                        <label>注册渠道</label>
                        <div class="controls">
                            <input type="text" class="form-control" value="{{ $user->channel_id }}" readonly>
                        </div>
                    </div>
                </div>
                <div class="col-3">
                    <div class="form-group">
                        <label>订单数</label>
                        <div class="controls">
                            <input type="text" class="form-control" value="{{ $user->orders_count }}" readonly>
                        </div>
                    </div>
                </div>
                <div class="col-3">
                    <div class="form-group">
                        <label>有效订单数</label>
                        <div class="controls">
                            <input type="text" class="form-control" value="{{ $user->success_orders_count }}" readonly>
                        </div>
                    </div>
                </div>
                <div class="col-3">
                    <div class="form-group">
                        <label>累计充值金额</label>
                        <div class="controls">
                            <input type="text" class="form-control" value="{{ $user->charge_total }}" readonly>
                        </div>
                    </div>
                </div>
                <div class="col-3">
                    <div class="form-group">
                        <label>VIP到期时间</label>
                        <div class="controls">
                            <input type="text" class="form-control" value="{{ $user->subscribed_until }}" readonly>
                        </div>
                    </div>
                </div>
                <div class="col-3">
                    <div class="form-group">
                        <label>钱包</label>
                        <div class="controls">
                            <input type="text" class="form-control" value="{{ $user->wallet }}" readonly>
                        </div>
                    </div>
                </div>
                <div class="col-3">
                    <div class="form-group">
                        <label>漫画购买次数</label>
                        <div class="controls">
                            <input type="text" class="form-control" value="{{ $user->purchase_books_count }}" readonly>
                        </div>
                    </div>
                </div>
                <div class="col-3">
                    <div class="form-group">
                        <label>累计漫画消费金币</label>
                        <div class="controls">
                            <input type="text" class="form-control" value="{{ $user->purchase_books_total }}" readonly>
                        </div>
                    </div>
                </div>
                <div class="col-3">
                    <div class="form-group">
                        <label>注册时间</label>
                        <div class="controls">
                            <input type="text" class="form-control" value="{{ $user->created_at }}" readonly>
                        </div>
                    </div>
                </div>
                <div class="col-3">
                    <div class="form-group">
                        <label>最近登入时间</label>
                        <div class="controls">
                            <input type="text" class="form-control" value="{{ $user->logged_at }}" readonly>
                        </div>
                    </div>
                </div>
                <div class="col-12 d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary">提交</button>
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
