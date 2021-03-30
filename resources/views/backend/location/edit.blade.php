@extends('layouts.modal')

{{-- page style --}}
@section('page-styles')
@endsection

@section('content')
    <form id="form" class="form" method="post" action="{{ route('backend.location.update', $location->id) }}">
        @method('put')
        <div class="form-body">
            <div class="row">
                <div class="col-12">
                    <div class="form-group">
                        <label for="input-name"><span class="danger">*</span> 地點名稱</label>
                        <div class="controls">
                            <input type="text" id="input-name" class="form-control" name="name"
                                   placeholder="请填写地點名稱"
                                   value="{{ $location->name }}">
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group">
                        <label for="input-phone">連絡電話</label>
                        <div class="controls">
                            <input type="text" id="input-phone" class="form-control" name="phone"
                                   value="{{ $location->phone }}">
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group">
                        <label for="input-email">連絡信箱</label>
                        <div class="controls">
                            <input type="text" id="input-email" class="form-control" name="email"
                                   value="{{ $location->email }}">
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group">
                        <label for="input-address">完整地址</label>
                        <div class="controls">
                            <input type="text" id="input-address" class="form-control" name="address"
                                   value="{{ $location->address }}">
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group">
                        <div class="font-size-small text-muted float-right">數字越小順序越高</div>
                        <label for="input-sort">排序</label>
                        <div class="controls">
                            <input type="text" id="input-sort" class="form-control" name="sort"
                                   value="{{ $location->sort }}">
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group">
                        <label>描述</label>
                        <div class="controls">
                            <textarea name="description" class="form-control" rows="5">{{$location->description}}</textarea>
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group">
                        <label>地圖語法</label>
                        <div class="controls">
                            <textarea name="map" class="form-control" rows="5">{{$location->map}}</textarea>
                        </div>
                    </div>
                </div>
                <div class="col-12 d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary mr-1">更新</button>
                    <button type="reset" class="btn btn-light-secondary mr-1">重置</button>
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
                    url: $(this).attr('action'),
                    type: $(this).attr('method'),
                    data: $(this).serialize(),
                    debug: true,
                    callback: function (res) {
                        if (res.code == 200) {
                            // iframe.blade.php
                            parent.$.hideModal();

                            // iframeLayoutMaster.blade.php
                            parent.parent.$.reloadIFrame({
                                title: '提交成功',
                                message: '数据已刷新'
                            });
                        } else {
                            parent.$.toast({
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
