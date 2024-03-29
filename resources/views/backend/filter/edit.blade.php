@extends('layouts.modal')

{{-- page style --}}
@section('page-styles')
{{--    <link rel="stylesheet" type="text/css" href="{{ asset('vendors/css/bootstrap-multiselect/bootstrap-multiselect.css') }}">--}}
    <link rel="stylesheet" type="text/css" href="{{ asset('vendors/css/pickers/daterange/daterangepicker.css') }}">
@endsection

@section('content')
    <form id="form" class="form" method="post" action="{{ route('backend.filter.update', $data->id) }}" novalidate>
        @method('put')
        <div class="form-body">
            <div class="row">
                <div class="col-6">
                    <div class="form-group">
                        <label><span class="danger">*</span> 条件备注</label>
                        <div class="controls">
                            <input type="text" class="form-control" name="title" placeholder="请输入条件备注" value="{{ $data->title }}">
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group">
                        <span class="float-right font-size-small text-light">(模糊匹配, 留空不设置挑选条件)</span>
                        <label>标题关键字</label>
                        <div class="controls">
                            <input type="text" class="form-control" name="params[title]" value="{{ $data->params['title'] ?? '' }}">
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group">
                        <span class="float-right font-size-small text-light">(模糊匹配, 留空不设置挑选条件)</span>
                        <label>作者关键字</label>
                        <div class="controls">
                            <input type="text" class="form-control" name="params[author]" value="{{ $data->params['author'] ?? '' }}">
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group">
                        <label>完结</label>
                        <div class="controls">
                            <select class="form-control" name="causer">
                                <option value="0" @if(isset($data->params['end']) && $data->params['end'] == 0){{'selected'}}@endif>无限制</option>
                                <option value="1" @if(isset($data->params['end']) && $data->params['end'] == 1){{'selected'}}@endif>已完结</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group">
                        <label>排序字段</label>
                        <div class="controls">
                            <input type="text" class="form-control" name="params[order_by]" placeholder="排序字段" value="{{ $data->params['order_by'] ?? 'created_at' }}">
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group">
                        <span class="float-right font-size-small text-light">(留空不设置挑选条件)</span>
                        <label>挑选作品上架时间段</label>
                        <div class="controls">
                            <fieldset class="form-group position-relative has-icon-left">
                                <input type="text" class="form-control date-picker" name="params[date_between]" autocomplete="off" value="{{ $data->params['date_between'] ?? '' }}">
                                <div class="form-control-position">
                                    <i class='bx bx-calendar-check'></i>
                                </div>
                            </fieldset>
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group">
                        <label>类型</label>
                        <div class="controls">
                            <div class="form-group">
                                <div class="controls">
                                    <select class="form-control" name="params[type]">
                                        <option value="0">无限制</option>
                                        @foreach ($type_options as $key => $val)
                                            <option value="{{ $key }}" @if(isset($data->params['type']) && $key == $data->params['type']){{'selected'}}@endif>{{ $val }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @foreach($categories as $title => $item)
                    <div class="col-12">
                        <div class="form-group">
                            <label>{{ $title }}</label>
                            <div class="controls">
                                <div class="row mt-1">
                                    @foreach($item['tags'] as $tag)
                                        <div class="col-2">
                                            <div class="form-group">
                                                <div class="controls">
                                                    <fieldset>
                                                        <div class="checkbox">
                                                            <input type="checkbox" name="tags[{{ $item['code'] }}][]" id="{{ $tag }}" value="{{ $tag }}" @if(isset($data->tags[$item['code']]) && in_array($tag, $data->tags[$item['code']])){{'checked'}}@endif>
                                                            <label for="{{ $tag }}">{{ $tag }}</label>
                                                        </div>
                                                    </fieldset>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
{{--                @foreach($categories as $title => $item)--}}
{{--                    <div class="col-12">--}}
{{--                        <div class="form-group">--}}
{{--                            <label>{{ $title }}标签</label>--}}
{{--                            <div class="controls">--}}
{{--                                <select class="form-control tags-selector" name="tags[{{ $item['code'] }}][]" multiple="multiple">--}}
{{--                                    @foreach($item['tags'] as $tag)--}}
{{--                                        <option value="{{ $tag }}" @if(in_array($tag, $data->tags[$item['code']])){{'selected'}}@endif>{{ $tag }}</option>--}}
{{--                                    @endforeach--}}
{{--                                </select>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                @endforeach--}}
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
{{--    <script src="{{ asset('vendors/js/bootstrap-multiselect/bootstrap-multiselect.js') }}"></script>--}}
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
            // $('.tags-selector').multiselect({
            //     buttonWidth: '100%',
            //     buttonTextAlignment: 'left',
            //     buttonText: function(options, select) {
            //         if (options.length === 0) {
            //             return '请选择标签';
            //         }
            //         else {
            //             var labels = [];
            //             options.each(function() {
            //                 if ($(this).attr('label') !== undefined) {
            //                     labels.push($(this).attr('label'));
            //                 }
            //                 else {
            //                     labels.push($(this).html());
            //                 }
            //             });
            //             return labels.join(', ') + '';
            //         }
            //     }
            // });

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

