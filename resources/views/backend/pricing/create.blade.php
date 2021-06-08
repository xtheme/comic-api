@extends('layouts.modal')

{{-- page style --}}
@section('page-styles')
@endsection

@section('content')
    <form id="form" class="form" method="post" action="{{ route('backend.pricing.store') }}" novalidate>
        <div class="form-body">
            <div class="row">
                <div class="col-6">
                    <div class="form-group">
                        <label>套餐名称</label>
                        <div class="controls">
                            <input type="text" class="form-control" name="type" placeholder="请输入套餐名称">
                        </div>
                    </div>
                </div>
                <div class="col-2">
                    <div class="form-group">
                        <label></label>
                        <div class="controls">
                            <div class="checkbox">
                                <input type="checkbox" class="checkbox-input" id="checkbox-preset" name="preset" value="1">
                                <label for="checkbox-preset">预设</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-4">
                    <div class="form-group">
                        <label>显示顺序</label>
                        <div class="controls">
                            <input type="text" class="form-control" name="sort" placeholder="数字由大到小排序">
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group">
                        <label>小标题</label>
                        <div class="controls">
                            <input type="text" class="form-control" name="name" placeholder="请输入小标题">
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group">
                        <label>会员支付价</label>
                        <div class="controls">
                            <input type="text" class="form-control" name="price" placeholder="请输入会员支付价">
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group">
                        <label>会员原价</label>
                        <div class="controls">
                            <input type="text" class="form-control" name="list_price" placeholder="请输入会员原价">
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group">
                        <label>天数</label>
                        <div class="controls">
                            <input type="text" class="form-control" name="days" placeholder="请输入天数">
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group">
                        <label>标签</label>
                        <div class="controls">
                            <input type="text" class="form-control" name="label" placeholder="标签长度请介于2~6字">
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group">
                        <label>用户状态</label>
                        <div class="controls">
                            <ul class="list-unstyled mb-0">
                                <li class="d-inline-block mr-2 mb-1">
                                    <fieldset>
                                        <div class="radio radio-shadow">
                                            <input type="radio" id="radio-status-0" name="status" value="0" checked>
                                            <label for="radio-status-0">全部用户</label>
                                        </div>
                                    </fieldset>
                                </li>
                                <li class="d-inline-block mr-2 mb-1">
                                    <fieldset>
                                        <div class="radio radio-shadow">
                                            <input type="radio" id="radio-status-1" name="status" value="1">
                                            <label for="radio-status-1">新用户</label>
                                        </div>
                                    </fieldset>
                                </li>
                                <li class="d-inline-block mr-2 mb-1">
                                    <fieldset>
                                        <div class="radio radio-shadow">
                                            <input type="radio" id="radio-status-2" name="status" value="2">
                                            <label for="radio-status-2">老用户</label>
                                        </div>
                                    </fieldset>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-12 justify-content-end">
                    <button type="submit" class="btn btn-primary mr-1 mb-1">提交</button>
                    <button type="reset" class="btn btn-light-secondary mr-1 mb-1">还原</button>
                </div>
            </div>
        </div>
    </form>
@endsection

{{-- vendor scripts --}}
@section('vendor-scripts')
@endsection

{{-- page scripts --}}
@section('page-scripts')
    <script>
		$(document).ready(function () {
			$('#form').submit(function (e) {
				e.preventDefault();
                if ($('.btn-primary').hasClass('disabled')){
                    return false;
                }
                
                $('.btn-primary').addClass('disabled');
				$.request({
					url     : $(this).attr('action'),
					type    : $(this).attr('method'),
                    data    : $('#form').serialize(),
					// debug: true,
					callback: function (res) {
                        if (res.code == 200) {
                            // iframe.blade.php
                            parent.$.hideModal();

                            // iframeLayoutMaster.blade.php
                            parent.parent.$.reloadIFrame({
                                title  : '提交成功',
                                message: '请稍后数据刷新'
                            });
                        } else {
                            $('.btn-primary').removeClass('disabled');
                            parent.$.toast({
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
