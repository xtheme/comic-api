@extends('layouts.modal')

{{-- page style --}}
@section('page-styles')
@endsection

@section('content')
    <form id="form" class="form" method="post" action="{{ route('backend.category.updateTags', $category->id) }}" novalidate>
        @method('put')
        <div class="form-body">
            <div class="row">
                <div class="col-12">
                    <div class="form-group">
                        <label>标签</label>
                        <div class="controls repeater">
                            <div data-repeater-list="tags" class="row">
                                @forelse ($tags as $tag)
                                    <div data-repeater-item class="col-3">
                                        <div class="row">
                                            <div class="col-10">
                                                <div class="form-group">
                                                    <input type="hidden" name="name" value="{{ $tag->name }}">
                                                    <input type="text" class="form-control" name="new_name" value="{{ $tag->name }}">
                                                </div>
                                            </div>
                                            <div class="col-2">
                                                <button class="btn btn-danger text-nowrap px-1" data-repeater-delete type="button">
                                                    <i class="bx bx-x"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div data-repeater-item class="col-3">
                                        <div class="row">
                                            <div class="col-10">
                                                <div class="form-group">
                                                    <input type="hidden" name="name" value="">
                                                    <input type="text" class="form-control" name="new_name" value="">
                                                </div>
                                            </div>
                                            <div class="col-2">
                                                <button class="btn btn-danger text-nowrap px-1" data-repeater-delete type="button">
                                                    <i class="bx bx-x"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                @endforelse
                            </div>
                            <button class="btn btn-primary" data-repeater-create type="button"><i class="bx bx-plus"></i>
                                添加标签
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
					if (confirm('是否确定要删除此标签？删除标签会同时从关联的项目中移除！')) {
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
