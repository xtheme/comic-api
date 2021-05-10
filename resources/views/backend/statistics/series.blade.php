@extends('layouts.iframePage')

{{-- page Title --}}
@section('title','系列统计')

{{-- vendor style --}}
@section('vendor-styles')
    <link rel="stylesheet" type="text/css" href="{{ asset('vendors/css/x-editable/bootstrap-editable.css') }}">
@endsection

@section('content')
    <section>
        <div class="card">
            <div class="card-header">
                <div class="float-left">
                    <h4 class="card-title">@yield('title')</h4>
                </div>
            </div>
            <div class="card-content">
                <div class="card-body">
                    <!-- Table with outer spacing -->
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                            <tr>
                                <th>视频ID</th>
                                <th>标题</th>
                                <th>非会员播放量</th>
                                <th>会员播放量</th>
                                <th>总播放量</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($data as $item)
                                <tr>
                                    <td>{{ $item->id }}</td>
                                    <td>
                                        {{ $item->title }}

                                        @if(!empty($item->tagged))
                                            <div class="d-flex align-content-center flex-wrap" style="margin-top: 5px;">
                                                @foreach($item->tagged as $tagged)
                                                    <span class="badge badge-pill badge-light-primary" style="margin-right: 3px; margin-bottom: 3px;">{{ $tagged->tag_name }}</span>
                                                @endforeach
                                            </div>
                                        @endif
                                    </td>
                                    <td>{{ $item->not_member->count  ?? '0' }}</td>
                                    <td>{{ $item->member->count  ?? '0' }}</td>
                                    <td>{{ ($item->not_member->count ?? '0') + ($item->member->count ?? '0')}}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="row">
                        <div class="col-md-6">总共 <b>{{ $data->appends(request()->input())->total() }}</b> 条, 分为 <b>{{ $data->lastPage() }}</b> 页</div>
                        <div class="col-md-6">{!! $data->appends(request()->input())->links() !!}</div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
