@extends('layouts.iframePage')

{{-- page Title --}}
@section('title','各金流收入')

{{-- vendor style --}}
@section('vendor-styles')
    <link rel="stylesheet" type="text/css" href="{{ asset('vendors/css/pickers/pickadate/pickadate.css') }}">
@endsection

@section('content')
    <section>
        <div class="card">
            <div class="card-header">
                <div class="float-left">
                    <h4 class="card-title">@yield('title')</h4>
                </div>
                <div class="float-right">
                    <form id="search-form" class="form form-vertical" method="get" action="{{ route('backend.finance.gateway_revenue') }}" novalidate>
                        <div class="form-body">
                            <div class="d-flex align-items-center">
                                <div class="form-group mr-1">
                                    <input type="text" name="date" class="form-control date-picker" value="{{ $date }}" placeholder="选择日期">
                                </div>
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary">查询</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="card-content">
                <div class="card-body">
                    <!-- Table with outer spacing -->
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                            <tr>
                                <th>渠道ID</th>
                                <th>渠道名称</th>
                                <th>日期</th>
                                <th>收入</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse ($list as $item)
                                <tr>
                                    <td>{{ $item->payment->id }}</td>
                                    <td>{{ $item->payment->name }}</td>
                                    <td>{{ $item->date }}</td>
                                    <td>{{ $item->recharge_amount }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="11" class="text-center font-medium-1">没有数据，请选择其他日期</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="row">
                        <div class="col-md-6">总共 <b>{{ $list->appends(request()->input())->total() }}</b> 条, 分为 <b>{{ $list->lastPage() }}</b> 页</div>
                        <div class="col-md-6">{!! $list->appends(request()->input())->links() !!}</div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('search-form')
@endsection

{{-- vendor scripts --}}
@section('vendor-scripts')
    <script src="{{ asset('vendors/js/pickers/pickadate/picker.js') }}"></script>
    <script src="{{ asset('vendors/js/pickers/pickadate/picker.date.js') }}"></script>
@endsection

{{-- page scripts --}}
@section('page-scripts')
    <script>
		$(document).ready(function () {
			$('.date-picker').pickadate({
				firstDay: 1,
				format: 'yyyy-mm-dd',
				monthsFull: [ '一月', '二月', '三月', '四月', '五月', '六月', '七月', '八月', '九月', '十月', '十一月', '十二月' ],
				monthsShort: [ '一', '二', '三', '四', '五', '六', '七', '八', '九', '十', '十一', '十二' ],
				weekdaysShort: [ '日', '一', '二', '三', '四', '五', '六' ],
				today: '今天',
				clear: '清除',
				close: '关闭'
			});

			$('#search-form').submit(function(e) {
				e.preventDefault();

				let url = $(this).attr('action') + '?' + $(this).serialize();
				console.log(url);
				$.reloadIFrame({
					reloadUrl: url
				});
			});
		});
    </script>
@endsection

