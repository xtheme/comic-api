@extends('layouts.modal')

{{-- vendor style --}}
@section('vendor-styles')
    <link rel="stylesheet" type="text/css" href="{{ asset('vendors/css/x-editable/bootstrap-editable.css') }}">
@endsection

@section('content')
    <div class="float-right">
        <form id="batch-action" class="form form-vertical" method="get" action="{{ route('backend.video_domain.change_domain') }}" novalidate>
            <div class="form-body">
                <div class="d-flex align-items-center">
                    <div class="form-group mr-1">
                        <select class="form-control" name="domain">
                            @foreach($domains as $domain)
                                <option value="{{ $domain->id }}">{{ $domain->title }} ({{ $domain->remark }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary">转换域名</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <div class="table-responsive">
        <table class="table table-striped table-hover">
            <thead>
            <tr>
                <th>
                    <div class="checkbox">
                        <input type="checkbox" class="checkbox-input check-all" id="check-all">
                        <label for="check-all"></label>
                    </div>
                </th>
                <th>ID</th>
                <th>章节顺序</th>
                <th>章节标题</th>
                <th>章节封面</th>
                <th>是否收费</th>
                <th>发布时间</th>
                <th>更新时间</th>
                <th>状态</th>
                <th>操作</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($list as $series)
                <tr>
                    <td>
                        <div class="checkbox">
                            <input type="checkbox" class="checkbox-input check-opt" id="check-{{ $series->id }}" name="ids[]" value="{{ $series->id }}">
                            <label for="check-{{ $series->id }}"></label>
                        </div>
                    </td>
                    <td>{{ $series->id }}</td>
                    <td>
                        <span data-type="text" data-pk="{{ $series->id }}" data-title="修改章节顺序" class="editable editable-click" data-url="{{ route('backend.book_chapter.editable', 'episode') }}">{{ $series->episode }}</span>
                    </td>
                    <td>{{ $series->title }}</td>
                    <td>章节封面</td>
                    <td>@if($series->charge == -1)
                            <span class="badge badge-pill badge-light-primary">免费</span>
                        @else
                            <span class="badge badge-pill badge-light-danger">收费</span>
                        @endif</td>
                    <td>
                        @if($series->created_at)
                            <span data-toggle="tooltip" data-placement="top" data-original-title="{{ $series->created_at }}">
                            {{ $series->created_at->diffForHumans() }}
                            </span>
                        @else
                            <span class="text-light">N/A</span>
                        @endif
                    </td>
                    <td>
                        @if($series->updated_at)
                            <span data-toggle="tooltip" data-placement="top" data-original-title="{{ $series->updated_at }}">
                            {{ $series->updated_at->diffForHumans() }}
                            </span>
                        @else
                            <span class="text-light">N/A</span>
                        @endif
                    </td>
                    <td>
                        @if($series->status == 1)
                            <span class="badge badge-pill badge-light-success">上架</span>
                        @else
                            <span class="badge badge-pill badge-light-danger">下架</span>
                        @endif
                    </td>
                    <td @if($loop->count == 1)style="position: fixed;"@endif>
                        <div class="@if(($loop->count - $loop->iteration) < 3){{'dropup'}}@else{{'dropdown'}}@endif">
                            <span class="bx bx-dots-vertical-rounded font-medium-3 dropdown-toggle nav-hide-arrow cursor-pointer"
                                  id="dropdownMenuButton{{ $series->id }}" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></span>
                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton{{ $series->id }}">
                                <a class="dropdown-item" data-modal href="" title="审核操作"><i class="bx bxs-check-shield mr-1"></i>审核操作</a>
                                <a class="dropdown-item" data-modal href="" title="编辑章节"><i class="bx bx-edit-alt mr-1"></i>编辑章节</a>
                            </div>
                        </div>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    <div class="row">
        <div class="col-md-6">总共 <b>{{ $list->appends(request()->input())->total() }}</b> 条, 分为 <b>{{ $list->lastPage() }}</b> 页</div>
        <div class="col-md-6">{!! $list->appends(request()->input())->links() !!}</div>
    </div>
@endsection

@section('search-form')
@endsection

{{-- vendor scripts --}}
@section('vendor-scripts')
    <script src="{{ asset('vendors/js/x-editable/bootstrap-editable.js') }}"></script>
@endsection

{{-- page scripts --}}
@section('page-scripts')
    <script>
        $(document).ready(function () {
	        $('#batch-action').submit(function (e) {
		        e.preventDefault();

		        let $this = $(this);
		        let ids   = $.checkedIds();
		        let url   = $this.attr('action');

		        if (!ids) {
			        parent.$.toast({
				        type: 'error',
				        message: '请先选择要操作的数据'
			        });
			        return false;
		        }

		        $.confirm({
			        text: `请确认是否要继续批量操作?`,
			        callback: function () {
				        $.request({
					        url: url,
					        type: 'put',
					        data: {'ids': ids, 'domain_id': $this.find('select[name="domain"]').val()},
					        debug: true,
					        callback: function (res) {
						        // console.log(res);
						        /*$.reloadModal({
							        reloadUrl: '{{ route('backend.video_domain.series', $id) }}',
							        title: res.msg
						        });*/
						        parent.$.hideModal();
						        parent.parent.$.reloadIFrame({
							        title: '提交成功',
							        message: '请稍后数据刷新'
						        });
					        }
				        });
			        }
		        });
	        });
        });
    </script>
@endsection

