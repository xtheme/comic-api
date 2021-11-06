@extends('layouts.iframePage')

{{-- page Title --}}
@section('title','用户举报')

{{-- vendor style --}}
@section('vendor-styles')
@endsection

@section('content')
    <section id="config-list">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">@yield('title')</h4>
            </div>
            <div class="card-content">
                <div class="card-body">
                    <!-- Table with outer spacing -->
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                            <tr>
                                <th>编号</th>
                                <th>用户ID</th>
                                <th>漫画ID</th>
                                <th>漫画名</th>
                                <th>举报类型</th>
                                <th>举报时间</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($list as $item)
                                <tr>
                                    <td>{{$item->id }}</td>
                                    <td>{{$item->user->id }}</td>
                                    <td>{{$item->book->id}}</td>
                                    <td>{{$item->book->title}}</td>
                                    <td>{{$item->report_type->name}}</td>
                                    <td>
                                    <span data-toggle="tooltip" data-placement="top" data-original-title="{{ $item->created_at}}">
                                        {{ $item->created_at->diffForHumans() }}
                                    </span>
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
                </div>
            </div>
        </div>
    </section>
@endsection



{{-- vendor scripts --}}
@section('vendor-scripts')
@endsection

{{-- page scripts --}}
@section('page-scripts')
@endsection

