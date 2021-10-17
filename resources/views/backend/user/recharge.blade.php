@extends('layouts.modal')

{{-- vendor style --}}
@section('vendor-styles')
@endsection

@section('content')
    <div class="table-responsive">
        <table class="table table-striped table-hover">
            <thead>
            <tr>
                <th>订单号</th>
                <th>方案类型</th>
                <th>金币</th>
                <th>加赠金币</th>
                <th>VIP天数</th>
                <th>加赠VIP天数</th>
                <th>时间</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($list as $log)
                <tr>
                    <td>{{ $log->order_no }}</td>
                    <td>
                        @if($log->type == 'vip')
                            <label class="badge badge-light-primary badge-pill">{{ $type_options[$log->type] }}</label>
                        @endif
                        @if($log->type == 'charge')
                            <label class="badge badge-light-secondary badge-pill">{{ $type_options[$log->type] }}</label>
                        @endif
                        @if($log->type == 'gift')
                            <label class="badge badge-light-danger badge-pill">{{ $type_options[$log->type] }}</label>
                        @endif
                    </td>
                    <td>{{ $log->coin }}</td>
                    <td>{{ $log->gift_coin }}</td>
                    <td>{{ $log->days }}</td>
                    <td>{{ $log->gift_days }}</td>
                    <td>{{ $log->created_at }}</td>
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

@section('search-form')
@endsection

{{-- vendor scripts --}}
@section('vendor-scripts')
@endsection

{{-- page scripts --}}
@section('page-scripts')
@endsection

