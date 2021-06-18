@extends('layouts.modal')

{{-- page Title --}}
@section('title','动画列表')

{{-- vendor style --}}
@section('vendor-styles')
    <link rel="stylesheet" type="text/css" href="{{ asset('vendors/css/pickers/daterange/daterangepicker.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('vendors/css/x-editable/bootstrap-editable.css') }}">
@endsection

@section('content')
    <div class="d-flex">
        <div class="pr-1">
            <form id="search-form" class="form form-vertical" method="get" action="{{ url()->current() }}" novalidate>
                <div class="form-body">
                    <div class="d-flex align-items-center">
                        <div class="form-group mr-1">
                            <select class="form-control" name="charge">
                                <option value="">观看资格</option>
                                @foreach ($charge_options as $key => $val)
                                    <option value="{{ $key }}" @if(request()->get('charge') == $key){{'selected'}}@endif>{{ $val }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group mr-1">
                            <select class="form-control" name="status">
                                <option value="">状态</option>
                                @foreach ($status_options as $key => $val)
                                    <option value="{{ $key }}" @if(request()->get('status') == $key){{'selected'}}@endif>{{ $val }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group mr-1">
                            <input type="text" class="form-control" name="title" value="{{ request()->get('title') }}" placeholder="影集标题">
                        </div>
{{--                        <div class="form-group mr-1 position-relative has-icon-left">--}}
{{--                            <input type="text" class="form-control" id="input-created" placeholder="请选择上架时间" name="created_between" autocomplete="off" value="{{ request()->get('created_between') }}">--}}
{{--                            <div class="form-control-position">--}}
{{--                                <i class='bx bx-calendar-check'></i>--}}
{{--                            </div>--}}
{{--                        </div>--}}
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">搜索</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <div class="pr-1">
            <a href="{{ route('backend.video_series.create', $video_id) }}" data-modal data-height="55vh" title="添加影集" class="btn btn-success">添加影集</a>
        </div>
        <div class="ml-auto">
            <form id="batch-action" class="form form-vertical" method="get" action="{{ route('backend.video_series.batch') }}" novalidate>
                <div class="form-body">
                    <div class="d-flex align-items-center">
                        <div class="form-group mr-1">
                            <select class="form-control" name="action">
                                <option value="enable">上架</option>
                                <option value="disable">下架</option>
                                <option value="charge">收费观看</option>
                                <option value="free">免费观看</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">批量操作</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <!-- Table with outer spacing -->
    <div class="table-responsive">
        <table class="table table-striped table-hover">
            <thead>
            <tr>
                <th>
                    <div class="checkbox">
                        <input type="checkbox" class="checkbox-input check-all" id="check-all">
                        <label for="check-all"></label>
                    </div>
                </th>
                <th>ID</th>
                <th>影集标题</th>
                <th>集数</th>
{{--                <th>视频域名</th>--}}
{{--                <th>视频链结</th>--}}
                <th>视频长度</th>
                <th>观看资格</th>
                <th>状态</th>
                <th>更新时间</th>
                <th>操作</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($list as $series)
                <tr>
                    <td>
                        <div class="checkbox">
                            <input type="checkbox" class="checkbox-input check-opt" id="check-{{ $series->id }}" name="ids[]" value="{{ $series->id }}">
                            <label for="check-{{ $series->id }}"></label>
                        </div>
                    </td>
                    <td>{{ $series->id }}</td>
                    <td>{{ $series->title }}
                        <div style="margin-top: .5em;">
                            <a data-modal data-size="lg" data-height="400px" title="视频预览" href="{{ route('backend.video_series.preview', $series->id) }}">{{ $series->url }}</a>
                        </div>
                    </td>
                    <td>{{ $series->episode }}</td>
{{--                    <td>{{ $series->cdn->title }}</td>--}}
{{--                    <td>{{ $series->cdn->domain . $series->link }}</td>--}}
                    <td>{{ clearLength($series->length) }}</td>
                    <td>
                        @if($series->charge == 1)
                            <a class="badge badge-pill badge-light-primary" data-modal-confirm href="{{ route('backend.video_series.batch', ['action'=>'free', 'ids' => $series->id]) }}" title="观看资格调整为免费">付费</a>
                        @else
                            <a class="badge badge-pill badge-light-secondary" data-modal-confirm href="{{ route('backend.video_series.batch', ['action'=>'charge', 'ids' => $series->id]) }}" title="观看资格调整为付费">免费</a>
                        @endif
                    </td>
                    <td>
                        @if($series->status == 1)
                            <a class="badge badge-pill badge-light-success" data-confirm href="{{ route('backend.video_series.batch', ['action'=>'disable', 'ids' => $series->id]) }}" title="下架该作品">上架</a>
                        @else
                            <a class="badge badge-pill badge-light-danger" data-confirm href="{{ route('backend.video_series.batch', ['action'=>'enable', 'ids' => $series->id]) }}" title="上架该作品">下架</a>
                        @endif
                    </td>
                    <td>{{ optional($series->updated_at)->diffForHumans() }}</td>
                    <td>
                        <div class="@if(($loop->count - $loop->iteration) < 3){{'dropup'}}@else{{'dropdown'}}@endif">
                            <span class="bx bx-dots-vertical-rounded font-medium-3 dropdown-toggle nav-hide-arrow cursor-pointer"
                                  id="dropdownMenuButton{{ $series->id }}" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></span>
                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton{{ $series->id }}">
                                <a class="dropdown-item" data-modal href="{{ route('backend.video_series.edit', [$video_id, $series->id]) }}" title="编辑影集"><i class="bx bx-edit-alt mr-1"></i>编辑影集</a>
                                <a class="dropdown-item" data-destroy href="{{ route('backend.video_series.destroy', $series->id) }}" title="刪除影集"><i class="bx bx-trash mr-1"></i>刪除影集</a>
                            </div>
                        </div>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    <div class="row">
        <div class="col-md-6">总共 <b>{{ $list->appends(request()->input())->total() }}</b> 条, 分为 <b>{{ $list->lastPage() }}</b> 页</div>
        <div class="col-md-6">{!! $list->appends(request()->input())->links() !!}</div>
    </div>
@endsection

{{-- vendor scripts --}}
@section('vendor-scripts')
    <script src="{{ asset('vendors/js/x-editable/bootstrap-editable.js') }}"></script>
    <script src="{{ asset('vendors/js/extensions/moment.min.js') }}"></script>
    <script src="{{ asset('vendors/js/pickers/daterange/daterangepicker.js') }}"></script>
    <script src="{{ asset('vendors/js/extensions/locale/zh-cn.js') }}"></script>
@endsection

{{-- page scripts --}}
@section('page-scripts')
    <script>
        $(document).ready(function () {
            // 時間選擇套件
            let $created = $('#input-created');

            // Date Ranges Initially Empty
            $created.daterangepicker({
                timePicker: true,
                timePicker24Hour: true,
                timePickerSeconds: true,
                drops: 'down',
                buttonClasses: 'btn',
                applyClass: 'btn-success',
                cancelClass: 'btn-danger',
                autoUpdateInput: false,
                locale: {
                    format: 'YYYY-MM-DD HH:mm:ss'
                }
            });

            $created.on('apply.daterangepicker', function(ev, picker) {
                $(this).val(picker.startDate.format('YYYY-MM-DD HH:mm:ss') + ' - ' + picker.endDate.format('YYYY-MM-DD HH:mm:ss'));
            });

            $created.on('cancel.daterangepicker', function(ev, picker) {
                $(this).val('');
            });

            // 條件查詢
            $('#search-form').submit(function(e) {
                e.preventDefault();

                let url = $(this).attr('action') + '?' + $(this).serialize();
                console.log(url);

                $.reloadIFrame({
                    reloadUrl  : url
                });

            });

            // 在線編輯
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            });
            $.fn.editable.defaults.ajaxOptions = {type: 'PUT'};
            $.fn.editableform.buttons = '<button type="submit" class="btn btn-primary editable-submit">确认</button>';

            $('.editable-click').editable({
                inputclass: 'form-control',
                emptyclass: 'text-light',
                emptytext: 'N/A',
                success: function (res, newValue) {
                    console.log(res);
                    $.toast({
                        title: '提交成功',
                        message: res.msg
                    });
                }
            });

            // 批量操作
            $('#batch-action').submit(function (e) {
                e.preventDefault();

                let $this = $(this);
                let ids   = $.checkedIds();
                let url   = $this.attr('action') + '/' + $this.find('select[name="action"]').val();

                if (!ids) {
                    $.toast({
                        type: 'error',
                        message: '请先选择要操作的数据'
                    });
                    return false;
                }

                $.confirm({
                    text: `请确认是否要继续批量操作?`,
                    callback: function () {
                        $.request({
                            url: url,
                            type: 'put',
                            data: {'ids' : ids},
                            debug   : true,
                            callback: function (res) {
                                $.reloadIFrame({
{{--                                    reloadUrl: '{{ route('backend.video_series.index', $video_id) }}',--}}
                                    title: res.msg
                                });
                            }
                        });
                    }
                });
            });
        });
    </script>
@endsection

