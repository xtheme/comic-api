@extends('layouts.modal')

{{-- page style --}}
@section('page-styles')
@endsection

@section('content')
    <form id="form" class="form" method="post" action="{{ route('backend.admin.update', $admin->id) }}" novalidate>
        @method('put')
        <div class="form-body">
            <div class="row">
                <div class="col-12">
                    <div class="form-group">
                        <label>角色</label>
                        <div class="controls">
                            <select class="form-control" name="role">
                                @foreach($roles as $role)
                                    <option value="{{ $role }}" @if($role == $admin->getRoleNames()->first()){{'selected'}}@endif>{{ $role }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group">
                        <span class="float-right font-size-small text-light">(仅能使用英文及数字)</span>
                        <label>登录帐号</label>
                        <div class="controls">
                            <input type="text" class="form-control" name="username" value="{{ $admin->username }}">
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group">
                        <label>昵称</label>
                        <div class="controls">
                            <input type="text" class="form-control" name="nickname" value="{{ $admin->nickname }}">
                        </div>
                    </div>
                </div>
                {{--<div class="col-12">
                    <div class="form-group">
                        <label>状态</label>
                        <div class="controls">
                            <select class="form-control" name="status">
                               <option value="1" @if($admin->status == 1){{'selected'}}@endif>启用</option>
                               <option value="0" @if($admin->status == 0){{'selected'}}@endif>封禁</option>
                            </select>
                        </div>
                    </div>
                </div>--}}
                <div class="col-12">
                    <div class="form-group">
                        <label>原密码</label>
                        <div class="controls">
                            <input type="password" class="form-control" name="password">
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group">
                        <label>新密码</label>
                        <div class="controls">
                            <input type="password" class="form-control" name="new_password">
                        </div>
                    </div>
                </div>
                <div class="col-12 d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary">提交</button>
                </div>
            </div>
        </div>
    </form>
@endsection

{{-- page scripts --}}
@section('page-scripts')
    <script>
		$(document).ready(function () {

            $('#form').submit(function (e) {
                e.preventDefault();

	            $.request({
		            url     : $(this).attr('action'),
		            type    : $(this).attr('method'),
		            data    : $(this).serialize(),
		            debug: true,
		            callback: function (res) {
			            if (res.code == 200) {
				            parent.$.hideModal();
				            parent.$.reloadIFrame();
			            } else {
				            $.toast({
					            type: 'error',
					            title: '提交失败',
					            message: res.msg
				            });
			            }
		            }
	            });
            });
		});
    </script>
@endsection

