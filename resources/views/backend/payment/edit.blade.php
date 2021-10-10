@extends('layouts.modal')

{{-- page style --}}
@section('page-styles')
    <link rel="stylesheet" type="text/css" href="{{ asset('vendors/css/forms/select/select2.min.css') }}">
@endsection

@section('content')
    <form id="form" class="form" method="post" action="{{ route('backend.payment.update', $payment->id) }}" novalidate>
        @method('put')
        <div class="form-body">
            <div class="row">
                <div class="col-4">
                    <div class="form-group">
                        <label><span class="danger">*</span> 渠道名稱</label>
                        <div class="controls">
                            <input type="text" class="form-control" name="name" value="{{ $payment->name }}">
                        </div>
                    </div>
                </div>
                <div class="col-4">
                    <div class="form-group">
                        <label><span class="danger">*</span> 手續費%</label>
                        <div class="controls">
                            <input type="text" class="form-control" name="fee_percentage" value="{{ $payment->fee_percentage }}">
                        </div>
                    </div>
                </div>
                <div class="col-4">
                    <div class="form-group">
                        <label>状态</label>
                        <div class="controls">
                            <select class="form-control" name="status">
                                @foreach ($status_options as $key => $val)
                                    <option value="{{ $key }}" @if($payment->status == $key){{'selected'}}@endif>{{ $val }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-4">
                    <div class="form-group">
                        <label>渠道網址</label>
                        <div class="controls">
                            <input type="text" class="form-control" name="url" value="{{ $payment->url }}">
                        </div>
                    </div>
                </div>
                <div class="col-4">
                    <div class="form-group">
                        <span class="float-right font-size-small text-light">(达到当日限额将自动停用)</span>
                        <label><span class="danger">*</span> 每日限額</label>
                        <div class="controls">
                            <input type="text" class="form-control" name="daily_limit" value="{{ $payment->daily_limit }}">
                        </div>
                    </div>
                </div>
                <div class="col-4">
                    <div class="form-group">
                        <label>函式庫</label>
                        <div class="controls">
                            <input type="text" class="form-control" name="library" value="{{ $payment->library }}">
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group">
                        <label><span class="danger">*</span> 支付方案</label>
                        <div class="controls">
                            <select class="select2 form-control" multiple="multiple" name="packages[]">
                                @foreach ($pricing as $pack)
                                <option value="{{ $pack->id }}" @if(in_array($pack->id, $payment->packages()->pluck('id')->toArray())){{'selected'}}@endif>{{ $pack->name }} {{ $pack->price }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group">
                        <label>支付配置</label>
                        <div class="controls repeater">
                            <div data-repeater-list="pay_options">
                                @forelse ($payment->pay_options as $key => $value)
                                    <div data-repeater-item>
                                        <div class="row">
                                            <div class="col-3 form-group">
                                                <input type="text" class="form-control" name="key" placeholder="配置键" value="{{ $key }}">
                                            </div>
                                            <div class="col-8 form-group">
                                                <input type="text" class="form-control" name="value" placeholder="配置值" value="{{ $value }}">
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
                                                <input type="text" class="form-control" name="key" placeholder="配置键">
                                            </div>
                                            <div class="col-8 form-group">
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
                <div class="col-12">
                    <div class="form-group">
                        <label>查詢訂單配置</label>
                        <div class="controls repeater">
                            <div data-repeater-list="order_options">
                                @forelse ($payment->order_options as $key => $value)
                                    <div data-repeater-item>
                                        <div class="row">
                                            <div class="col-3 form-group">
                                                <input type="text" class="form-control" name="key" placeholder="配置键" value="{{ $key }}">
                                            </div>
                                            <div class="col-8 form-group">
                                                <input type="text" class="form-control" name="value" placeholder="配置值" value="{{ $value }}">
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
                                                <input type="text" class="form-control" name="key" placeholder="配置键">
                                            </div>
                                            <div class="col-8 form-group">
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
                    <button type="submit" class="btn btn-primary mr-1 mb-1">提交</button>
                    <button type="reset" class="btn btn-light-secondary mr-1 mb-1">还原</button>
                </div>
            </div>
        </div>
    </form>
@endsection

{{-- vendor scripts --}}
@section('vendor-scripts')
    <script src="{{asset('vendors/js/forms/repeater/jquery.repeater.min.js')}}"></script>
    <script src="{{asset('vendors/js/forms/select/select2.full.min.js')}}"></script>
@endsection

{{-- page scripts --}}
@section('page-scripts')
    <script>
		$(document).ready(function () {
			$(".select2").select2({
				// the following code is used to disable x-scrollbar when click in select input and
				// take 100% width in responsive also
				dropdownAutoWidth: true,
				width: '100%'
			});

			$('.repeater').repeater({
				show: function () {
					$(this).slideDown();
				},
				hide: function (deleteElement) {
					if (confirm('是否确定要删除此渠道？')) {
						$(this).slideUp(deleteElement);
					}
				}
			});

			$('#form').submit(function (e) {
				e.preventDefault();

				$.request({
					url     : $(this).attr('action'),
					type    : $(this).attr('method'),
					data    : $(this).serialize(),
					// debug: true,
					callback: function (res) {
                        if (res.code === 200) {
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
