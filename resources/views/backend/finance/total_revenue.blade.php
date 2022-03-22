@extends('layouts.contentLayout')

{{-- page Title --}}
@section('title','总收入')

{{-- vendor style --}}
@section('vendor-styles')
@endsection

@section('content')
    <section>
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
                                <th style="width: 15vw;">日期</th>
                                <th>收入</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse ($list as $item)
                                <tr>
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
@endsection

{{-- page scripts --}}
@section('page-scripts')
@endsection

