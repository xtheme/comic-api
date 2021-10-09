@extends('layouts.iframePage')

{{-- page Title --}}
@section('title','配置列表')

{{-- page style --}}
@section('page-styles')
@endsection

@section('content')
    <section id="config-list">
        <div class="mb-1">
            <a href="{{ route('backend.config.create') }}" class="btn btn-primary glow" data-modal title="添加配置" role="button" aria-pressed="true">添加配置</a>
        </div>
        <div class="card">
            <div class="card-header">
                <div class="float-left">
                    <h4 class="card-title">@yield('title')</h4>
                </div>
                <div class="float-right d-flex flex-wrap">
                    <form id="search-form" class="form form-horizontal" method="get" action="{{ url()->current() }}" novalidate>
                        <div class="form-body">
                            <div class="d-flex align-items-center">
                                <div class="form-group mr-1">
                                    <div class="controls">
                                        <input type="text" class="form-control" name="keyword"
                                               placeholder="请输入配置描述或代码"
                                               value="{{ request()->get('keyword') }}">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <input type="hidden" name="group" value="{{ request()->get('group') }}">
                                    <button type="submit" class="btn btn-primary">搜索</button>
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
                                <th>配置描述</th>
                                <th>配置代号</th>
                                <th>配置值</th>
                                <th>创建时间</th>
                                <th>更新时间</th>
                                <th>操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($list as $item)
                                <tr>
                                    <td>{{ $item->name }}</td>
                                    <td>{{ $item->code }}</td>
                                    <td>
                                        @foreach($item->options as $key => $value)
                                            <dl class="row mb-0">
                                                <dt class="col-sm-3">{{ $key }}</dt>
                                                <dd class="col-sm-9">{{ $value }}</dd>
                                            </dl>
                                        @endforeach
                                    </td>
                                    <td>@if($item->created_at){{ $item->created_at->diffForHumans()  }}@endif</td>
                                    <td>@if($item->updated_at){{ $item->updated_at->diffForHumans()  }}@endif</td>
                                    <td @if($loop->count == 1)style="position: fixed;"@endif>
                                        <div class="@if(($loop->count - $loop->iteration) < 3){{'dropup'}}@else{{'dropdown'}}@endif">
                                            <span class="bx bx-dots-vertical-rounded font-medium-3 dropdown-toggle nav-hide-arrow cursor-pointer"
                                                  id="dropdownMenuButton{{ $item->id }}" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></span>
                                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton{{ $item->id }}">
                                                <a class="dropdown-item" data-modal href="{{ route('backend.config.edit', $item->id) }}" title="修改配置"><i class="bx bx-edit-alt mr-1"></i> 修改</a>
                                                <a class="dropdown-item" data-destroy href="{{ route('backend.config.destroy', $item->id) }}" title="刪除配置"><i class="bx bx-trash mr-1"></i> 删除</a>
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
                        <div class="col-md-6">{!! $list->links() !!}</div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
