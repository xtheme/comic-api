@extends('layouts.iframePage')

{{-- page Title --}}
@section('title', '管理员列表')

{{-- vendor style --}}
@section('vendor-styles')
@endsection

@section('content')
    <section id="config-list">
        <div class="mb-1">
            <a href=" {{ route('backend.admin.create') }}" data-modal data-size="md" data-height="40vh" title="添加管理员" class="btn btn-primary">添加管理员</a>
        </div>
        <div class="card">
            <div class="card-header">
                <div class="float-left">
                    <h4 class="card-title">@yield('title')</h4>
                </div>
                <div class="float-right d-flex flex-wrap">
                    <form id="batch-action" class="form form-vertical" method="get" action="{{ route('backend.admin.batch') }}" novalidate>
                        <div class="form-body">
                            <div class="d-flex align-items-center">
                                <div class="form-group mr-1">
                                    <select class="form-control" name="role">
                                        @foreach($roles as $role)
                                            <option value="{{ $role }}">{{ $role }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary">指派角色</button>
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
                                <th>
                                    <div class="checkbox">
                                        <input type="checkbox" class="checkbox-input check-all" id="check-all">
                                        <label for="check-all"></label>
                                    </div>
                                </th>
                                <th>ID</th>
                                <th>登录帐号</th>
                                <th>角色</th>
                                <th>昵称</th>
                                <th>添加时间</th>
                                <th>修改时间</th>
                                <th>登录日期</th>
                                <th>登录IP</th>
                                <th>状态</th>
                                <th>操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($list as $admin)
                                <tr>
                                    <td>
                                        <div class="checkbox">
                                            <input type="checkbox" class="checkbox-input check-opt" id="check-{{ $admin->id }}" name="ids[]" value="{{ $admin->id }}">
                                            <label for="check-{{ $admin->id }}"></label>
                                        </div>
                                    </td>
                                    <td>{{ $admin->id }}</td>
                                    <td>{{ $admin->username }}</td>
                                    <td>{{ $admin->getRoleNames()->first() }}</td>
                                    <td>{{ $admin->nickname }}</td>
                                    <td>{{ $admin->create_time }}</td>
                                    <td>{{ $admin->update_time }}</td>
                                    <td>{{ $admin->logintime }}</td>
                                    <td>{{ $admin->loginip }}</td>
                                    <td>
                                        @if($admin->id == 1)
                                            <span class="badge badge-pill badge-light-warning">超级管理员</span>
                                        @else
                                            @if($admin->status == 1)
                                                <a class="badge badge-pill badge-light-success" data-confirm href="{{ route('backend.admin.batch', ['action'=>'disable', 'ids' => $admin->id]) }}" title="封禁帐号">启用</a>
                                            @else
                                                <a class="badge badge-pill badge-light-danger" data-confirm href="{{ route('backend.admin.batch', ['action'=>'enable', 'ids' => $admin->id]) }}" title="启用帐号">封禁</a>
                                            @endif
                                        @endif
                                    </td>
                                    <td>
                                        <div class="@if(($loop->count - $loop->iteration) < 3){{'dropup'}}@else{{'dropdown'}}@endif">
                                            <span class="bx bx-dots-vertical-rounded font-medium-3 dropdown-toggle nav-hide-arrow cursor-pointer"
                                                  id="dropdownMenuButton{{ $admin->id }}" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></span>
                                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton{{ $admin->id }}">
                                                <a class="dropdown-item" data-modal data-size="md" data-height="40vh" href="{{ route('backend.admin.edit', $admin->id) }}" title="修改管理员"><i class="bx bx-edit-alt mr-1"></i> 修改</a>
                                                @if($admin->id != 1)
                                                <a class="dropdown-item" data-destroy href="{{ route('backend.admin.destroy', $admin->id) }}" title="刪除管理员"><i class="bx bx-trash mr-1"></i> 删除</a>
                                                @endif
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

@section('search-form')
@endsection


{{-- vendor scripts --}}
@section('vendor-scripts')
@endsection


@section('page-scripts')
    <script>
        $(document).ready(function () {
            $('#batch-action').submit(function (e) {
                e.preventDefault();

                let $this = $(this);
                let ids   = $.checkedIds();
                let url   = $this.attr('action') + '/assign';

                if (!ids) {
                    parent.$.toast({
                        type: 'error',
                        message: '请先选择要指派的管理员'
                    });
                    return false;
                }

                $.confirm({
                    text: `请确认是否要继续指派角色?`,
                    callback: function () {
                        $.request({
                            url: url,
                            type: 'put',
                            data: {'ids' : ids, 'role': $this.find('select[name="role"]').val()},
                            debug   : true,
                            callback: function (res) {
                                $.reloadIFrame({
                                    title: '提交成功',
                                    message: '请稍后数据刷新'
                                });
                            }
                        });
                    }
                });
            });

            $('#search-form').submit(function(e) {
                e.preventDefault();

                let url = $(this).attr('action') + '?' + $(this).serialize();
                console.log(url);
                parent.$.reloadIFrame({
                    reloadUrl: url
                });
            });
        });
    </script>
@endsection
