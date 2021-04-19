@extends('layouts.modal')

{{-- page style --}}
@section('page-styles')
    <link rel="stylesheet" type="text/css" href="{{ asset('vendors/css/pickers/daterange/daterangepicker.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/plugins/forms/validation/form-validation.css') }}">
@endsection

@section('content')
    <form id="form" class="form" method="post" action="{{ route('backend.pricingpackage.update', $data->id) }}" novalidate>
        @method('put')
        <div class="form-body">
            <div class="row">
                <div class="col-12">
                    <div class="form-group">
                        <label for="input-username">套餐名称</label>
                        <div class="controls">
                            <input type="text" id="input-username" class="form-control" name="type"
                                   placeholder="请输入套餐名称"
                                   required
                                   data-validation-required-message="请输入套餐名称"
                                   value="{{ $data->type }}">
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group">
                        <label for="input-username">小标题</label>
                        <div class="controls">
                            <input type="text" id="input-username" class="form-control" name="name"
                                   placeholder="请输入小标题"
                                   required
                                   data-validation-required-message="请输入小标题"
                                   value="{{ $data->name }}">
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group">
                        <label for="input-username">会员支付价</label>
                        <div class="controls">
                            <input type="text" id="input-username" class="form-control" name="price"
                                   placeholder="请输入会员支付价"
                                   required
                                   data-validation-required-message="请输入会员支付价"
                                   value="{{ $data->price }}">
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group">
                        <label for="input-username">会员原价</label>
                        <div class="controls">
                            <input type="text" id="input-username" class="form-control" name="list_price"
                                   placeholder="请输入会员原价"
                                   required
                                   data-validation-required-message="请输入会员原价"
                                   value="{{ $data->list_price }}">
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group">
                        <label for="input-username">天数</label>
                        <div class="controls">
                            <input type="text" id="input-username" class="form-control" name="days"
                                   placeholder="请输入天数"
                                   required
                                   data-validation-required-message="请输入天数"
                                   value="{{ $data->days }}">
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group">
                        <label for="input-username">标签</label>
                        <div class="controls">
                            <input type="text" id="input-username" class="form-control" name="label"
                                   placeholder="标签长度请介于2~6字"
                                   required
                                   data-validation-required-message="标签长度请介于2~6字"
                                   value="{{ $data->label }}">
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group">
                        <label for="select-sex">用户状态</label>
                        <div class="controls">
                            <ul class="list-unstyled mb-0">
                                <li class="d-inline-block mr-2 mb-1">
                                    <fieldset>
                                        <div class="radio radio-shadow">
                                            <input type="radio" id="radioshadow1" name="status" value="0" @if($data->status == 0) checked @endif >
                                            <label for="radioshadow1">全部用户</label>
                                        </div>
                                    </fieldset>
                                </li>
                                <li class="d-inline-block mr-2 mb-1">
                                    <fieldset>
                                        <div class="radio radio-shadow">
                                            <input type="radio" id="radioshadow2"  name="status" value="1" @if($data->status == 1) checked @endif>
                                            <label for="radioshadow2">新用户</label>
                                        </div>
                                    </fieldset>
                                </li>
                                <li class="d-inline-block mr-2 mb-1">
                                    <fieldset>
                                        <div class="radio radio-shadow">
                                            <input type="radio" id="radioshadow3"  name="status" value="2" @if($data->status == 2) checked @endif>
                                            <label for="radioshadow3">老用户</label>
                                        </div>
                                    </fieldset>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group">
                        <label>显示顺序</label>
                        <div class="controls">
                            <input type="text" id="input-username" class="form-control" name="sort"
                                   placeholder="请输入显示顺序"
                                   required
                                   data-validation-required-message="请输入显示顺序"
                                   value="{{ $data->sort }}">
                            <div class="col-sm-8 dd_ts">值越大, 显示越靠前</div>
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
    <script src="{{ asset('vendors/js/extensions/moment.min.js') }}"></script>
    <script src="{{ asset('vendors/js/extensions/locale/zh-cn.js') }}"></script>
    <script src="{{ asset('vendors/js/pickers/daterange/daterangepicker.js') }}"></script>
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

            // 日期時間選擇
            moment.locale('zh-cn');

            let $created = $('input[name="subscribed_at"]');

            $created.daterangepicker({
                drops: 'up',
                buttonClasses: 'btn',
                applyClass: 'btn-success',
                cancelClass: 'btn-danger',
                autoUpdateInput: false,
                locale: {
                    direction       : 'ltr',
                    format          : moment.localeData().longDateFormat('L'),
                    separator       : ' - ',
                    applyLabel      : '确定',
                    cancelLabel     : '取消',
                    weekLabel       : 'W',
                    customRangeLabel: 'Custom Range',
                    daysOfWeek      : moment.weekdaysMin(),
                    monthNames      : moment.monthsShort(),
                    firstDay        : moment.localeData().firstDayOfWeek()
                },
                singleDatePicker: true,
                timePicker: true,
                timePicker24Hour: true,
                timePickerSeconds: true,
                timePickerIncrement: 5,
                // minDate: moment().subtract(1, 'days'),
            });

            $created.on('apply.daterangepicker', function(ev, picker) {
                $(this).val(picker.startDate.format('YYYY-MM-DD HH:mm:ss'));
            });

            $created.on('cancel.daterangepicker', function(ev, picker) {
                $(this).val('');
            });
		});
    </script>
@endsection
