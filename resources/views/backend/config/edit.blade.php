@extends('layouts.modal')

{{-- page style --}}
@section('page-styles')
@endsection

@section('content')
    <form id="form" class="form" method="post" action="{{ route('backend.config.update', $config->id) }}" novalidate>
        @method('put')
        <div class="form-body">
            <div class="row">
                <div class="col-12">
                    <div class="form-group">
                        <label><span class="danger">*</span> 配置描述</label>
                        <div class="controls">
                            <input type="text" class="form-control" name="name" placeholder="配置描述" value="{{ $config->name }}">
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group">
                        <label><span class="danger">*</span> 配置代号</label>
                        <div class="controls">
                            <input type="text" class="form-control" name="code" placeholder="配置代号" value="{{ $config->code }}">
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group">
                        <label><span class="danger">*</span> 配置項</label>
                        <div class="controls repeater">
                            <div data-repeater-list="options">
                                @forelse ($config->options as $key => $row)
                                    <div data-repeater-item>
                                        <div class="row">
                                            <div class="col-3 form-group">
                                                <input type="text" class="form-control" name="remark" placeholder="配置说明" value="{{ isset($row['remark']) ? $row['remark']  : '' }}">
                                            </div>
                                            <div class="col-3 form-group">
                                                <input type="text" class="form-control" name="key" placeholder="配置键" value="{{ $key }}">
                                            </div>
                                            <div class="col-5 form-group">
                                                <input type="text" class="form-control" name="value" placeholder="配置值" value="{{ isset($row['value']) ? $row['value'] : '' }}">
                                            </div>
                                            <div class="col-1 form-group">
                                                <button class="btn btn-danger text-nowrap px-1" data-repeater-delete type="button">
                                                    <i class="bx bx-x"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div data-repeater-item>
                                        <div class="row">
                                            <div class="col-3 form-group">
                                                <input type="text" class="form-control" name="remark" placeholder="配置说明">
                                            </div>
                                            <div class="col-3 form-group">
                                                <input type="text" class="form-control" name="key" placeholder="配置键">
                                            </div>
                                            <div class="col-5 form-group">
                                                <input type="text" class="form-control" name="value" placeholder="配置值">
                                            </div>
                                            <div class="col-1 form-group">
                                                <button class="btn btn-danger text-nowrap px-1" data-repeater-delete type="button">
                                                    <i class="bx bx-x"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                @endforelse
                            </div>
                            <button class="btn btn-primary" data-repeater-create type="button"><i class="bx bx-plus"></i>
                                添加配置项
                            </button>
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

{{-- vendor scripts --}}
@section('vendor-scripts')
    <script src="{{asset('vendors/js/forms/repeater/jquery.repeater.min.js')}}"></script>
@endsection

{{-- page scripts --}}
@section('page-scripts')
    <script>
		$(document).ready(function () {
			$('.repeater').repeater({
				show: function () {
					$(this).slideDown();
				},
				hide: function (deleteElement) {
					if (confirm('是否确定要删除此配置项？')) {
						$(this).slideUp(deleteElement);
					}
				}
			});

			$('#form').submit(function (e) {
				e.preventDefault();
                $.request({
					url     : $(this).attr('action'),
					type    : $(this).attr('method'),
					data    : $('#form').serialize(),
					debug: true,
					callback: function (res) {
                        if (res.code == 200) {
                            // iframe.blade.php
                            parent.$.hideModal();

                            // iframeLayoutMaster.blade.php
                            parent.$.reloadIFrame({
                                title  : '提交成功',
                                message: '请稍后数据刷新'
                            });
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

