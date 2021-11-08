@extends('layouts.modal')

{{-- vendor style --}}
@section('vendor-styles')
@endsection

@section('content')
    <div class="table-responsive">
        <table class="table table-striped table-hover">
            <thead>
            <tr>
                <th>ID</th>
                <th>商品类别</th>
                <th>商品ID</th>
                <th>商品名称</th>
                <th>时间</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($list as $log)
                <tr>
                    <td>{{ $log->id }}</td>
                    <td>{{ $log->item_type }}</td>
                    <td>{{ $log->item_id }}</td>
                    <td>{{ $log->item_title }}</td>
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

