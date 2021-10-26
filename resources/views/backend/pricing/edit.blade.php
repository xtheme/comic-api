@extends('layouts.modal')

{{-- page style --}}
@section('page-styles')
@endsection

@section('content')
    <form id="form" class="form" method="post" action="{{ route('backend.pricing.update', $pack->id) }}" novalidate>
        @method('put')
        <div class="form-body">
            <div class="row">
                <div class="col-4">
                    <div class="form-group">
                        <label>方案类型</label>
                        <div class="controls">
                            <select class="form-control" name="type">
                                @foreach ($type_options as $key => $val)
                                    <option value="{{ $key }}" @if($pack->type == $key){{'selected'}}@endif>{{ $val }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-4">
                    <div class="form-group">
                        <label>方案名称</label>
                        <div class="controls">
                            <input type="text" class="form-control" name="name" value="{{ $pack->name }}">
                        </div>
                    </div>
                </div>
                <div class="col-4">
                    <div class="form-group">
                        <label>标签</label>
                        <div class="controls">
                            <input type="text" class="form-control" name="label" value="{{ $pack->label }}">
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group">
                        <label>方案描述</label>
                        <div class="controls">
                            <textarea class="form-control" name="description" rows="3">{{ $pack->description }}</textarea>
                        </div>
                    </div>
                </div>
                <div class="col-4">
                    <div class="form-group">
                        <label>支付金额</label>
                        <div class="controls">
                            <input type="text" class="form-control" name="price" value="{{ $pack->price }}">
                        </div>
                    </div>
                </div>
                <div class="col-4">
                    <div class="form-group">
                        <label>VIP天数</label>
                        <div class="controls">
                            <input type="text" class="form-control" name="days" value="{{ $pack->days }}">
                        </div>
                    </div>
                </div>
                <div class="col-4">
                    <div class="form-group">
                        <label>金币</label>
                        <div class="controls">
                            <input type="text" class="form-control" name="coin" value="{{ $pack->coin }}">
                        </div>
                    </div>
                </div>
                <div class="col-4">
                    <div class="form-group">
                        <label>支付原价</label>
                        <div class="controls">
                            <input type="text" class="form-control" name="list_price" value="{{ $pack->list_price }}">
                        </div>
                    </div>
                </div>
                <div class="col-4">
                    <div class="form-group">
                        <label>加赠IP天数</label>
                        <div class="controls">
                            <input type="text" class="form-control" name="gift_days" value="{{ $pack->gift_days }}">
                        </div>
                    </div>
                </div>
                <div class="col-4">
                    <div class="form-group">
                        <label>加赠金币</label>
                        <div class="controls">
                            <input type="text" class="form-control" name="gift_coin" value="{{ $pack->gift_coin }}">
                        </div>
                    </div>
                </div>
                <div class="col-4">
                    <div class="form-group">
                        <label>目标客群</label>
                        <div class="controls">
                            <select class="form-control" name="target">
                                @foreach ($target_options as $key => $val)
                                    <option value="{{ $key }}" @if($pack->target == $key){{'selected'}}@endif>{{ $val }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-4">
                    <div class="form-group">
                        <label>状态</label>
                        <div class="controls">
                            <select class="form-control" name="status">
                                @foreach ($status_options as $key => $val)
                                    <option value="{{ $key }}" @if($pack->status == $key){{'selected'}}@endif>{{ $val }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-4">
                    <div class="form-group">
                        <label>排序</label>
                        <div class="controls">
                            <input type="text" class="form-control" name="sort" value="{{ $pack->sort }}" placeholder="数字由大到小排序">
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
