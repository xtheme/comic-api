@extends('layouts.modal')

@section('vendor-styles')
@endsection

@section('page-styles')
@endsection

@section('content')
    <form id="form" class="form" method="post" action="{{ route('backend.book.review_update', $book->id) }}">
        @method('PUT')
        <div class="form-body">
            <div class="row">
                <div class="col-12">
                    <div class="form-group">
                        <div class="controls">
                            <ul class="list-unstyled mb-0">
                                @foreach ($review_options as $key => $val)
                                <li class="d-block mr-2 mb-1">
                                    <fieldset>
                                        <div class="radio">
                                            <input type="radio" name="review" id="end-{{ $key }}" value="{{ $key }}" @if($key == $book->review){{'checked'}}@endif>
                                            <label for="end-{{ $key }}">{{ $val }}</label>
                                        </div>
                                    </fieldset>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-12 d-flex justify-content-center">
                    <button type="submit" class="btn btn-primary mr-1">提交</button>
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
