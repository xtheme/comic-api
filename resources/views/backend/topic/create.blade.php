@extends('layouts.modal')

{{-- page style --}}
@section('page-styles')
@endsection

@section('content')
    <form id="form" class="form" method="post" action="{{ route('backend.topic.store') }}" novalidate>
        <div class="form-body">
            <div class="row">
                <div class="col-4">
                    <div class="form-group">
                        <label><span class="danger">*</span> 类型</label>
                        <div class="controls">
                            <select class="form-control" name="type">
                                @foreach ($causer_options as $key => $val)
                                    <option value="{{ $key }}">{{ $val }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-4">
                    <div class="form-group">
                        <label><span class="danger">*</span> 筛选器</label>
                        <div class="controls">
                            <select  class="form-control" name="filter_id">
                                @foreach($filters as $filter)
                                    <option value="{{ $filter->id }}">{{ $filter->title }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-4">
                    <div class="form-group">
                        <label><span class="danger">*</span> 区块排序</label>
                        <div class="controls">
                            <input type="text" class="form-control" name="sort" placeholder="请输入排序" value="0">
                        </div>
                    </div>
                </div>

                <div class="col-4">
                    <div class="form-group">
                        <label><span class="danger">*</span> 首笔聚焦</label>
                        <select class="form-control" name="spotlight">
                            <option value="0">否</option>
                            <option value="1">是</option>
                        </select>
                    </div>
                </div>
                <div class="col-4">
                    <div class="form-group">
                        <span class="float-right font-size-small text-danger">(聚焦之外的数据每行几笔)</span>
                        <label><span class="danger">*</span> 每行笔数</label>
                        <select class="form-control" name="row">
                            <option value="2">每行 2 笔</option>
                            <option value="3">每行 3 笔</option>
                        </select>
                    </div>
                </div>
                <div class="col-4">
                    <div class="form-group">
                        <label><span class="danger">*</span> 区块展示笔数</label>
                        <div class="controls">
                            <input type="number" class="form-control" name="limit" value="3">
                        </div>
                    </div>
                </div>
                <div class="col-4">
                    <div class="form-group">
                        <label><span class="danger">*</span> 状态</label>
                        <div class="controls">
                            <select class="form-control" name="status">
                                <option value="1">开启</option>
                                <option value="-1">关闭</option>
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
                    data    : $('#form').serialize(),
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
