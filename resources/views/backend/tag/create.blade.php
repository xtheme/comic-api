@extends('layouts.modal')

@section('vendor-styles')
@endsection

@section('page-styles')
@endsection

@section('content')
    <form id="form" class="form" method="post" action="{{ route('backend.tag.store') }}">
        <div class="form-body">
            <div class="row">
                <div class="col-12">
                    <div class="form-group">
                        <label>分类</label>
                        <div class="controls">
                            <select class="form-control" name="type">
                                @foreach ($categories as $category)
                                    <option value="{{ $category->type }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group">
                        <label>添加标签</label>
                        <div class="controls">
                            <input type="text" class="form-control" name="name">
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group">
                        <span class="float-right font-size-small text-light">(数字由大到小排序)</span>
                        <label>排序</label>
                        <div class="controls">
                            <input type="text" class="form-control" name="order_column" placeholder="请输入排序">
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group">
                        <label>前端显示</label>
                        <div class="controls">
                            <select class="form-control" name="suggest">
                                @foreach ($status_options as $key => $val)
                                    <option value="{{ $key }}">{{ $val }}</option>
                                @endforeach
                            </select>
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
					debug: true,
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
