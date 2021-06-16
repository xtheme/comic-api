@extends('layouts.iframePage')

{{-- page Title --}}
@section('title','广告位列表')

{{-- page style --}}
@section('page-styles')
@endsection

@section('content')
    <section id="config-list">
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
                                        <select id="class-type" class="form-control" name="class">
                                            <option value="" >全部</option>
                                            @foreach($class_type as $key => $item)
                                                <option value="{{$key}}" @if(request()->get('class') == $key){{'selected'}}@endif>{{$item}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group mr-1">
                                    <div class="controls">
                                        <input type="text" class="form-control" name="name"
                                               placeholder="请输入广告位名称"
                                               value="{{ request()->get('name') }}">
                                    </div>
                                </div>
                                <div class="form-group">
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
                                <th>ID</th>
                                <th>广告位名称</th>
                                <th>广告位类型</th>
{{--                                <th>备注</th>--}}
                                <th>状态</th>
                                <th>接入广告SDK</th>
                                <th>更新时间</th>
                                <th>操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($list as $item)
                                <tr>
                                    <td>{{ $item->id }}</td>
                                    <td>
                                        <span class="text-bold-600 text-dark">{{ $item->name }}</span>
                                        <em class="d-flex align-content-center flex-wrap text-light" style="margin-top: 5px;">
                                            {{ $item->remark }}
                                        </em>
                                    </td>
                                    <td>
                                        @if($item->class == 'video')
                                            <span class="badge badge-pill badge-primary">动画</span>
                                        @elseif($item->class == 'comics')
                                            <span class="badge badge-pill badge-success">漫画</span>
                                        @else
                                            <span class="badge badge-pill badge-light-danger">其他</span>
                                        @endif
                                    </td>
{{--                                    <td>{{ $item->remark }}</td>--}}
                                    <td>
                                        @if($item->status == 1)
                                            <span class="badge badge-pill badge-light-primary">上架</span>
                                        @else
                                            <span class="badge badge-pill badge-light-danger">下架</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($item->sdk == 1)
                                            <span class="badge badge-pill badge-light-primary">开启</span>
                                        @else
                                            <span class="badge badge-pill badge-light-secondary">关闭</span>
                                        @endif
                                    </td>
                                    <td>@if($item->updated_at){{ $item->updated_at->diffForHumans()  }}@endif</td>
                                    <td @if($loop->count == 1)style="position: fixed;"@endif>
                                        <div class="@if(($loop->count - $loop->iteration) < 3){{'dropup'}}@else{{'dropdown'}}@endif">
                                            <span class="bx bx-dots-vertical-rounded font-medium-3 dropdown-toggle nav-hide-arrow cursor-pointer"
                                                  id="dropdownMenuButton{{ $item->id }}" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></span>
                                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton{{ $item->id }}">
                                                <a class="dropdown-item" data-modal href="{{ route('backend.ad_space.edit', $item->id) }}" title="修改广告位 - {{$item->name}}"><i class="bx bx-edit-alt mr-1"></i> 修改</a>
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
