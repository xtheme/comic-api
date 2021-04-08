@extends('layouts.modal')

{{-- page style --}}
@section('page-styles')
    <link rel="stylesheet" type="text/css" href="{{ asset('css/plugins/forms/validation/form-validation.css') }}">
@endsection

@section('content')
    <form id="form" class="form" method="post" action="{{ route('backend.pricingpackage.store') }}" novalidate>
        <div class="form-body">
            <div class="row">
                <div class="col-12">
                    <div class="form-group">
                        <label for="input-username">套餐名称</label>
                        <div class="controls">
                            <input type="text" id="input-username" class="form-control" name="type"
                                   placeholder="请输入套餐名称"
                                   required
                                   data-validation-required-message="请输入套餐名称">
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group">
                        <label for="input-username">小标题</label>
                        <div class="controls">
                            <input type="text" id="input-username" class="form-control" name="name"
                                   placeholder="请输入小标题"
                                   required
                                   data-validation-required-message="请输入小标题">
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group">
                        <label for="input-username">会员支付价</label>
                        <div class="controls">
                            <input type="text" id="input-username" class="form-control" name="price"
                                   placeholder="请输入会员支付价"
                                   required
                                   data-validation-required-message="请输入会员支付价">
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group">
                        <label for="input-username">会员原价</label>
                        <div class="controls">
                            <input type="text" id="input-username" class="form-control" name="list_price"
                                   placeholder="请输入会员原价"
                                   required
                                   data-validation-required-message="请输入会员原价">
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group">
                        <label for="input-username">天数</label>
                        <div class="controls">
                            <input type="text" id="input-username" class="form-control" name="days"
                                   placeholder="请输入天数"
                                   required
                                   data-validation-required-message="请输入天数">
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group">
                        <label for="input-username">标签</label>
                        <div class="controls">
                            <input type="text" id="input-username" class="form-control" name="label"
                                   placeholder="标签长度请介于2~6字"
                                   required
                                   data-validation-required-message="标签长度请介于2~6字">
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group">
                        <label for="select-sex">用户状态</label>
                        <div class="controls">
                            <ul class="list-unstyled mb-0">
                                <li class="d-inline-block mr-2 mb-1">
                                    <fieldset>
                                        <div class="radio radio-shadow">
                                            <input type="radio" id="radioshadow1" name="status" value="0" checked>
                                            <label for="radioshadow1">全部用户</label>
                                        </div>
                                    </fieldset>
                                </li>
                                <li class="d-inline-block mr-2 mb-1">
                                    <fieldset>
                                        <div class="radio radio-shadow">
                                            <input type="radio" id="radioshadow2"  name="status" value="1">
                                            <label for="radioshadow2">新用户</label>
                                        </div>
                                    </fieldset>
                                </li>
                                <li class="d-inline-block mr-2 mb-1">
                                    <fieldset>
                                        <div class="radio radio-shadow">
                                            <input type="radio" id="radioshadow3"  name="status" value="2">
                                            <label for="radioshadow3">老用户</label>
                                        </div>
                                    </fieldset>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group">
                        <label>显示顺序</label>
                        <div class="controls">
                            <input type="text" id="input-username" class="form-control" name="sort"
                                   placeholder="请输入显示顺序"
                                   required
                                   data-validation-required-message="请输入显示顺序">
                            <div class="col-sm-8 dd_ts">值越大, 显示越靠前</div>
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
    <script src="{{ asset('vendors/js/forms/validation/jqBootstrapValidation.js') }}"></script>
@endsection

{{-- page scripts --}}
@section('page-scripts')
    <script src="{{ asset('js/scripts/forms/validation/form-validation.js') }}"></script>
    <script>
		$(document).ready(function () {
			$('#form').submit(function (e) {
				e.preventDefault();

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
                                message: '数据已刷新'
                            });
                        } else {
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
