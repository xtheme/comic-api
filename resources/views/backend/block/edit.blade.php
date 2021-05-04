@extends('layouts.modal')

{{-- page style --}}
@section('page-styles')
    <link rel="stylesheet" type="text/css" href="{{ asset('vendors/css/pickers/daterange/daterangepicker.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('vendors/css/bootstrap-multiselect/bootstrap-multiselect.css') }}">
@endsection

@section('content')
    <form id="form" class="form" method="post" action="{{ route('backend.block.update', $data->id) }}" novalidate>
        @method('put')
        <div class="form-body">
            <div class="row">
                <div class="col-6">
                    <div class="form-group">
                        <label for="input-username"><span class="danger">*</span>模块名称</label>
                        <div class="controls">
                            <input type="text" id="input-username" class="form-control" name="title"
                                   placeholder="请输入模块名称"
                                   required
                                   data-validation-required-message="请输入模块名称"
                                   value="{{ $data->title }}">
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group">
                        <label for="input-username"><span class="danger">*</span>模块排序<span class="danger">(由小到大排序)</span></label>
                        <div class="controls">
                            <input type="text" id="input-username" class="form-control" name="sort"
                                   placeholder="请输入排序"
                                   required
                                   data-validation-required-message="请输入排序"
                                   value="{{ $data->sort }}">
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group">
                        <label for="input-username">聚焦的数量<span class="danger">(例如：动漫1大2小 则填入1)</span></label>
                        <div class="controls">
                            <input type="text" id="input-username" class="form-control" name="spotlight"
                                   placeholder="请输入聚焦数"
                                   required
                                   data-validation-required-message="请输入聚焦数"
                                   value="{{ $data->spotlight }}">
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group">
                        <label for="input-username">每行几个内容<span class="danger">(例如：动漫1大2小 则填入2))</span></label>
                        <div class="controls">
                            <input type="text" id="input-username" class="form-control" name="row"
                                   placeholder="请输入行数"
                                   required
                                   data-validation-required-message="请输入行数"
                                   value="{{ $data->row }}">
                        </div>
                    </div>
                </div>
{{--                <div class="col-12">--}}
{{--                    <div class="form-group">--}}
{{--                        <label for="input-username"><span class="danger">*</span>模块展示</label>--}}
{{--                        <div class="controls">--}}
{{--                            <select class="form-control" name="style" >--}}
{{--                                <option value="">请选择风格</option>--}}
{{--                                @foreach ($style as $key => $item)--}}
{{--                                    <option value="{{ $key }}" @if($data->style == $key) selected @endif>{{ $item }}</option>--}}
{{--                                @endforeach--}}
{{--                            </select>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}
                <div class="col-6">
                    <div class="form-group">
                        <label for="input-username"><span class="danger">*</span> 模块模型</label>
                        <div class="controls">
                            <select class="form-control" name="causer" >
                                <option value="video" @if($causer[$data->causer] == 'video') selected @endif>动画</option>
                                <option value="comic" @if($causer[$data->causer] == 'comic') selected @endif>漫画</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group">
                        <label for="input-username"><span class="danger">*</span> 状态</label>
                        <div class="controls">
                            <select class="form-control" name="status" >
                                <option value="1" @if($data->status == 1) selected @endif>开启</option>
                                <option value="-1" @if($data->status == -1) selected @endif>关闭</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group">
                        <label for="input-username">挑选笔数</label>
                        <div class="controls">
                            <input type="text" id="input-username" class="form-control" name="properties[limit][value]"
                                   placeholder="请输入挑选笔数，留空不设置挑选条件"
                                   required
                                   data-validation-required-message="请输入挑选笔数"
                                   value="{{$data->properties['limit']['value']}}">
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group">
                        <label for="input-username">挑选作者</label>
                        <div class="controls">
                            <input type="text" id="input-username" class="form-control" name="properties[author][value]"
                                   placeholder="请输入作者，留空不设置挑选条件"
                                   data-validation-required-message="请输入作者"
                                   value="{{$data->properties['author']['value']}}"
                            >
                        </div>
                    </div>
                </div>
                <input type="hidden" name="properties[order][value]" value="created_at">
{{--                <div class="col-6">--}}
{{--                    <div class="form-group">--}}
{{--                        <label for="input-username">挑选排序</label>--}}
{{--                        <div class="controls">--}}
{{--                            <ul class="list-unstyled mb-0">--}}
{{--                                <li class="d-inline-block mr-2 mb-1">--}}
{{--                                    <fieldset>--}}
{{--                                        <div class="radio radio-shadow">--}}
{{--                                            <input type="radio" id="radio_condition_1"  name="properties[1][value]" value="created_at" @if($data->properties[1]['value'] == 'created_at') checked @endif >--}}
{{--                                            <label for="radio_condition_1">时间</label>--}}
{{--                                        </div>--}}
{{--                                    </fieldset>--}}
{{--                                </li>--}}
{{--                                <li class="d-inline-block mr-2 mb-1">--}}
{{--                                    <fieldset>--}}
{{--                                        <div class="radio radio-shadow">--}}
{{--                                            <input type="radio" id="radio_condition_2" name="properties[1][value]" value="hot" @if($data->properties[1]['value'] == 'hot') checked @endif >--}}
{{--                                            <label for="radio_condition_2">热度</label>--}}
{{--                                        </div>--}}
{{--                                    </fieldset>--}}
{{--                                </li>--}}
{{--                                <li class="d-inline-block mr-2 mb-1">--}}
{{--                                    <fieldset>--}}
{{--                                        <div class="radio radio-shadow">--}}
{{--                                            <input type="radio" id="radio_condition_3" name="properties[1][value]" value="collect" @if($data->properties[1]['value'] == 'collect') checked @endif >--}}
{{--                                            <label for="radio_condition_3">收藏量</label>--}}
{{--                                        </div>--}}
{{--                                    </fieldset>--}}
{{--                                </li>--}}
{{--                            </ul>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}
                <div class="col-12">
                    <div class="form-group">
                        <label for="input-username">挑选作品时间区间</label>
                        <div class="controls">
                            <fieldset class="form-group position-relative has-icon-left">
                                <input type="text" id="input-date-register" class="form-control date-picker"
                                       name="properties[date_between][value]"
                                       autocomplete="off"
                                       placeholder="请输入时间区间，留空不设置挑选条件"
                                       value="{{$data->properties['date_between']['value']}}"
                                >
                                <div class="form-control-position">
                                    <i class='bx bx-calendar-check'></i>
                                </div>
                            </fieldset>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group">
                        <label for="input-username">挑选标签</label>
                        <div class="controls">
                            <select id="tags-selector" class="form-control" name="properties[tag][value][]" multiple="multiple">
                                @foreach($tags as $tag)
                                    <option value="{{ $tag->name }}" @if(in_array($tag->name, $data->properties['tag']['value'])){{'selected'}}@endif>{{ $tag->name }}</option>
                                @endforeach
                            </select>
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
    <script src="{{ asset('vendors/js/extensions/locale-all.js') }}"></script>
    <script src="{{ asset('vendors/js/pickers/daterange/daterangepicker.js') }}"></script>
    <script src="{{ asset('vendors/js/bootstrap-multiselect/bootstrap-multiselect.js') }}"></script>
@endsection

{{-- page scripts --}}
@section('page-scripts')
    <script src="{{ asset('js/scripts/forms/validation/form-validation.js') }}"></script>
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
					// debug: true,
					callback: function (res) {
                        if (res.code == 200) {
                            // iframe.blade.php
                            parent.$.hideModal();

                            // iframeLayoutMaster.blade.php
                            parent.parent.$.reloadIFrame({
                                title  : '提交成功',
                                message: '数据已刷新'
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
