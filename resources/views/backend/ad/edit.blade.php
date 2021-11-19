@extends('layouts.modal')

{{-- page style --}}
@section('page-styles')
@endsection

@section('content')
    <form id="form" class="form" method="post" action="{{ route('backend.ad.update', $data->id) }}" novalidate>
        @method('put')
        <div class="form-body">
            <div class="row">
                <div class="col-4">
                    <div class="form-group">
                        <label><span class="danger">*</span> 广告位</label>
                        <div class="controls">
                            <select id="select-type" class="form-control" name="space_id">
                                @foreach($ad_spaces as $key => $item)
                                    <option value="{{ $item->id }}" @if($item->id == $data->space_id)  selected @endif>{{ $item->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-4">
                    <div class="form-group">
                        <label><span class="danger">*</span> 排序</label>
                        <div class="controls">
                            <input type="text" class="form-control" name="sort" placeholder="数字由大到小排序" value="{{ $data->sort }}">
                        </div>
                    </div>
                </div>
                <div class="col-4">
                    <div class="form-group">
                        <label>状态</label>
                        <div class="controls">
                            <select class="form-control" name="status">
                                <option value="1" @if($data->status == 1){{'selected'}}@endif>上架</option>
                                <option value="0" @if($data->status == 0){{'selected'}}@endif>下架</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group">
                        <label>广告地址</label>
                        <div class="controls">
                            <input type="text" class="form-control" name="url"  value="{{ $data->url }}" placeholder="请输入网址">
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group">
                        <label for="input-username"><span class="danger">*</span> 广告图</label>
                        <div class="input-group">
                            <input type="text" class="form-control image-path" name="banner" autocomplete="off" value="{{ $data->getRawOriginal('banner') }}">
                            <input type="file" class="hidden-file-upload" data-path="notable/{{ $data->id }}">
                            <div class="input-group-append" id="input-file-addon">
                                <button class="btn btn-primary upload-image" type="button">上传</button>
                            </div>
                        </div>
                        <div class="upload-image-callback">
                            @if($data->getRawOriginal('banner'))<img src="{{ $data->banner }}" class="img-fluid" alt="">@endif
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
					data    : $('#form').serialize(),
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

