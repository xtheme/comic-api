@extends('layouts.modal')

{{-- page style --}}
@section('page-styles')
    <link rel="stylesheet" type="text/css" href="{{ asset('vendors/css/bootstrap-multiselect/bootstrap-multiselect.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('vendors/css/pickers/daterange/daterangepicker.css') }}">
@endsection

@section('content')
    <form id="form" class="form" method="post" action="{{ route('backend.topic.update', $data->id) }}" novalidate>
        @method('put')
        <div class="form-body">
            <div class="row">
                <div class="col-4">
                    <div class="form-group">
                        <label><span class="danger">*</span> 类型</label>
                        <div class="controls">
                            <select class="form-control" name="causer">
                                @foreach ($causer_options as $key => $val)
                                <option value="{{ $key }}" @if($data->causer == $key){{'selected'}}@endif>{{ $val }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-8">
                    <div class="form-group">
                        <label><span class="danger">*</span> 模块标题</label>
                        <div class="controls">
                            <input type="text" class="form-control" name="title" placeholder="请输入模块标题" value="{{ $data->title }}">
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group">
                        <span class="float-right font-size-small text-light">(数字由大到小排序)</span>
                        <label><span class="danger">*</span> 模块排序</label>
                        <div class="controls">
                            <input type="text" class="form-control" name="sort" placeholder="请输入排序" value="{{ $data->sort }}">
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group">
                        <label><span class="danger">*</span> 状态</label>
                        <div class="controls">
                            <select class="form-control" name="status">
                                <option value="1" @if($data->status == 1){{'selected'}}@endif>开启</option>
                                <option value="-1" @if($data->status == -1){{'selected'}}@endif>关闭</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group">
                        <span class="float-right font-size-small text-danger">(例如：动漫1大2小, 则选择是)</span>
                        <label>首笔聚焦</label>
                        <select class="form-control" name="spotlight">
                            <option value="0" @if($data->spotlight == 0){{'selected'}}@endif>否</option>
                            <option value="1" @if($data->spotlight == 1){{'selected'}}@endif>是</option>
                        </select>
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group">
                        <span class="float-right font-size-small text-danger">(例如：动漫1大2小, 则选择2)</span>
                        <label>聚焦之外的数据每行几笔</label>
                        <select class="form-control" name="row">
                            <option value="2" @if($data->row == 2){{'selected'}}@endif>每行 2 笔</option>
                            <option value="3" @if($data->row == 3){{'selected'}}@endif>每行 3 笔</option>
                        </select>
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group">
                        <span class="float-right font-size-small text-danger">(例如：动漫1大2小, 填入5, 扣除聚焦笔数1则展示2行)</span>
                        <label><span class="danger">*</span> 模块展示笔数</label>
                        <div class="controls">
                            <input type="number" class="form-control" name="properties[limit]" value="{{ $data->properties['limit'] ?? 0 }}">
                        </div>
                    </div>
                </div>
            </div>
            <div class="divider">
                <div class="divider-text">筛选条件, 留空表示忽略该条件</div>
            </div>
            <div class="row">
                <div class="col-6">
                    <div class="form-group">
                        <span class="float-right font-size-small text-danger">(此条件只对动画类型有效)</span>
                        <label>角标</label>
                        <div class="controls">
                            <select class="form-control" name="properties[ribbon]">
                                <option value="">忽略</option>
                                @foreach ($ribbon_options as $key => $val)
                                    <option value="{{ $key }}" @if(($data->properties['ribbon'] ?? '') == $key){{'selected'}}@endif>{{ $val }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group">
                        <span class="float-right font-size-small text-light">(留空不设置挑选条件)</span>
                        <label>挑选作者</label>
                        <div class="controls">
                            <input type="text" class="form-control" name="properties[author]" value="{{ $data->properties['author'] ?? '' }}">
                        </div>
                    </div>
                </div>
                <input type="hidden" name="properties[order]" value="created_at">
                {{--                <div class="col-6">--}}
                {{--                    <div class="form-group">--}}
                {{--                        <label><span class="danger">*</span> 挑选排序</label>--}}
                {{--                        <div class="controls">--}}
                {{--                            <input type="hidden" name="properties[1]" value="created_at">--}}
                {{--                            <ul class="list-unstyled mb-0">--}}
                {{--                                <li class="d-inline-topic mr-2 mb-1">--}}
                {{--                                    <fieldset>--}}
                {{--                                        <div class="radio radio-shadow">--}}
                {{--                                            <input type="radio" id="radio_condition_1"  name="properties[1]" value="created_at" checked>--}}
                {{--                                            <label for="radio_condition_1">时间</label>--}}
                {{--                                        </div>--}}
                {{--                                    </fieldset>--}}
                {{--                                </li>--}}
                {{--                                <li class="d-inline-topic mr-2 mb-1">--}}
                {{--                                    <fieldset>--}}
                {{--                                        <div class="radio radio-shadow">--}}
                {{--                                            <input type="radio" id="radio_condition_2" name="properties[1]" value="hot" >--}}
                {{--                                            <label for="radio_condition_2">热度</label>--}}
                {{--                                        </div>--}}
                {{--                                    </fieldset>--}}
                {{--                                </li>--}}
                {{--                                <li class="d-inline-topic mr-2 mb-1">--}}
                {{--                                    <fieldset>--}}
                {{--                                        <div class="radio radio-shadow">--}}
                {{--                                            <input type="radio" id="radio_condition_3" name="properties[1]" value="collect" >--}}
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
                        <span class="float-right font-size-small text-light">(留空不设置挑选条件)</span>
                        <label>挑选作品上架时间段</label>
                        <div class="controls">
                            <fieldset class="form-group position-relative has-icon-left">
                                <input type="text" class="form-control date-picker" name="properties[date_between]" autocomplete="off" value="{{ $data->properties['date_between'] ?? '' }}">
                                <div class="form-control-position">
                                    <i class='bx bx-calendar-check'></i>
                                </div>
                            </fieldset>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group">
                        <label>挑选标签</label>
                        <div class="controls">
                            <select id="tags-selector" class="form-control" name="properties[tag][]" multiple="multiple">
                                @foreach($tags as $tag)
                                    <option value="{{ $tag }}" @if(isset($data->properties['tag']) && in_array($tag, $data->properties['tag'])){{'selected'}}@endif>{{ $tag }}</option>
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
    <script src="{{ asset('vendors/js/pickers/daterange/daterangepicker.js') }}"></script>
    <script src="{{ asset('vendors/js/extensions/locale/zh-cn.js') }}"></script>
    <script src="{{ asset('vendors/js/bootstrap-multiselect/bootstrap-multiselect.js') }}"></script>
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

