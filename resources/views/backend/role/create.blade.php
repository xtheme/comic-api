@extends('layouts.modal')

{{-- page style --}}
@section('page-styles')
@endsection

@section('content')
    <form id="form" class="form" method="post" action="{{ route('backend.role.store') }}" novalidate>
        <div class="form-body">
            <div class="row">
                <div class="col-12">
                    <div class="form-group">
                        <label>角色</label>
                        <div class="controls">
                            <input type="text" class="form-control" name="name" value="">
                        </div>
                    </div>
                </div>
                @foreach($route_permissions as $key => $permissions)
                    <div class="col-4 mt-1">
                        <div class="form-group">
                            <label class="font-medium-2 text-primary">{{ __('permissions.' . $key) }}</label>
                            <div class="controls">
                                @foreach($permissions as $permission)
                                    <fieldset>
                                        <div class="checkbox mt-1">
                                            <input type="checkbox" name="permission[]" id="{{ $permission['route'] }}" value="{{ $permission['route'] }}">
                                            <label for="{{ $permission['route'] }}">{{ $permission['name'] }}</label>
                                        </div>
                                    </fieldset>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endforeach
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
					url: $(this).attr('action'),
					type: $(this).attr('method'),
					data: $(this).serialize(),
					debug: true,
					callback: function (res) {
						if (res.code == 200) {
							parent.$.hideModal();
							parent.$.reloadIFrame();
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

