@extends('layouts.modal')

{{-- page style --}}
@section('page-styles')
    <link rel="stylesheet" type="text/css" href="{{ asset('vendors/css/pickers/daterange/daterangepicker.css') }}">
@endsection

@section('content')
    <form id="form" class="form" method="post" action="{{ route('backend.filter.store') }}" novalidate>
        <div class="form-body">
            <div class="row">
                <div class="col-6">
                    <div class="form-group">
                        <label><span class="danger">*</span> 条件备注</label>
                        <div class="controls">
                            <input type="text" class="form-control" name="title" placeholder="请输入条件备注" value="">
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group">
                        <span class="float-right font-size-small text-light">(模糊匹配, 留空不设置挑选条件)</span>
                        <label>标题关键字</label>
                        <div class="controls">
                            <input type="text" class="form-control" name="params[title]" value="">
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group">
                        <span class="float-right font-size-small text-light">(模糊匹配, 留空不设置挑选条件)</span>
                        <label>作者关键字</label>
                        <div class="controls">
                            <input type="text" class="form-control" name="params[author]" value="">
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group">
                        <label>完结</label>
                        <div class="controls">
                            <select class="form-control" name="causer">
                                <option value="0">无限制</option>
                                <option value="1">已完结</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group">
                        <label>排序字段</label>
                        <div class="controls">
                            <input type="text" class="form-control" name="params[order_by]" placeholder="排序字段" value="created_at">
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group">
                        <span class="float-right font-size-small text-light">(留空不设置挑选条件)</span>
                        <label>挑选作品上架时间段</label>
                        <div class="controls">
                            <fieldset class="form-group position-relative has-icon-left">
                                <input type="text" class="form-control date-picker" name="params[date_between]" autocomplete="off" value="">
                                <div class="form-control-position">
                                    <i class='bx bx-calendar-check'></i>
                                </div>
                            </fieldset>
                        </div>
                    </div>
                </div>
                @foreach($tag_group as $group_name => $group)
                    <div class="col-12">
                        <div class="form-group">
                            <label>{{ $group_name }}</label>
                            <div class="controls">
                                <div class="row">
                                    @foreach($group as $tag)
                                        <div class="col-2">
                                            <fieldset>
                                                <div class="checkbox mt-1">
                                                    <input type="checkbox" name="tags[{{ $tag['type'] }}][]" id="{{ $tag['type'] . $tag['name'] }}" value="{{ $tag['name'] }}">
                                                    <label for="{{ $tag['type'] . $tag['name'] }}">{{ $tag['name'] }}</label>
                                                </div>
                                            </fieldset>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
                <div class="col-12 d-flex justify-content-end">
                    <input type="hidden" name="type" value="{{ $type }}">
                    <button type="submit" class="btn btn-primary">提交</button>
                </div>
            </div>
        </div>
    </form>
@endsection

{{-- vendor scripts --}}
@section('vendor-scripts')
    <script src="{{ asset('vendors/js/extensions/moment.min.js') }}"></script>
    <script src="{{ asset('vendors/js/pickers/daterange/daterangepicker.js') }}"></script>
    <script src="{{ asset('vendors/js/extensions/locale/zh-cn.js') }}"></script>
@endsection


{{-- page scripts --}}
@section('page-scripts')
    <script>
        let $datePicker = $('.date-picker');

        $datePicker.daterangepicker({
            autoUpdateInput: false,
            startDate: moment().subtract(7, 'days').calendar(),
        });

        $datePicker.on('apply.daterangepicker', function(ev, picker) {
            $(this).val(picker.startDate.format('YYYY-MM-DD') + ' - ' + picker.endDate.format('YYYY-MM-DD'));
        });

        $datePicker.on('cancel.daterangepicker', function(ev, picker) {
            $(this).val('');
        });

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
