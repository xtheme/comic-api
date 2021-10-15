@extends('layouts.modal')

@section('vendor-styles')
    <link rel="stylesheet" type="text/css" href="{{ asset('vendors/css/bootstrap-multiselect/bootstrap-multiselect.css') }}">
    <link rel="stylesheet" type="text/css" href="{{asset('vendors/css/file-uploaders/dropzone.min.css')}}">
@endsection

@section('page-styles')
@endsection

@section('content')
    <form id="form" class="form" method="post" action="{{ route('backend.book_chapter.update' , $data->id) }}">
        @method('PUT')
        <div class="form-body">
            <div class="row">
                <div class="col-4">
                    <div class="form-group">
                        <label><span class="danger">*</span> 章节名称</label>
                        <div class="controls">
                            <input type="text" class="form-control" name="title" value="{{ $data->title }}">
                        </div>
                    </div>
                </div>
                <div class="col-4">
                    <div class="form-group">
                        <label><span class="danger">*</span> 是否收费</label>
                        <div class="controls">
                            <select class="form-control" id="select-charge" name="charge">
                                <option value="1" @if(1 == $data->charge){{'selected'}}@endif >是</option>
                                <option value="0" @if(-1 == $data->charge){{'selected'}}@endif >否</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-4">
                    <div class="form-group">
                        <label>上架状态</label>
                        <div class="controls">
                            <select class="form-control" id="select-status" name="status">
                                <option value="1" @if(1 == $data->status){{'selected'}}@endif >上架</option>
                                <option value="0" @if(-1 == $data->status){{'selected'}}@endif >下架</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group">
                        <label><span class="danger">*</span> 章节顺序</label>
                        <div class="controls">
                            <input type="text" class="form-control" name="episode" value="{{ $data->episode }}">
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group">
                        <label><span class="danger">*</span> 添加方式</label>
                        <div class="controls">
                            <select class="form-control" id="select-operating" name="operating">
                                <option value="1" @if(1 == $data->operating){{'selected'}}@endif >手动</option>
                                <option value="2" @if(2 == $data->operating){{'selected'}}@endif >自动</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group">
                        <label><span class="danger">*</span> 章节详情</label>
                        <div class="controls">
                            <div id="myDropzone" class="btn btn-primary glow">多图上传</div>
                        </div>
                        <ul id="sortable" class="visualization sortable dropzone-previews"></ul>
                    </div>
                </div>

                <div class="col-12 d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary mr-1">提交</button>
                    <button type="reset" class="btn btn-light-secondary">还原</button>
                </div>
            </div>
        </div>
    </form>

    <div class="preview" style="display:none;">
        <li>
            <div>
                <div class="dz-preview dz-file-preview">
                    <div class="dz-details">
                        <img data-dz-thumbnail/>
                    </div>
                    <div class="dz-progress"><span class="dz-upload" data-dz-uploadprogress></span></div>
                    <div class="dz-error-message"><span data-dz-errormessage></span></div>
                </div>
                <input data-multiple type="hidden"/></div>
            </div>
        </li>
    </div>
@endsection

{{-- vendor scripts --}}
@section('vendor-scripts')
    <script src="{{ asset('vendors/js/bootstrap-multiselect/bootstrap-multiselect.js') }}"></script>
    <script src="{{asset('vendors/js/extensions/dropzone.min.js')}}"></script>
    <script src="{{asset('vendors/js/sortable.js')}}"></script>
@endsection

{{-- page scripts --}}
@section('page-scripts')
    <script>

        Dropzone.autoDiscover = false;
        new Sortable(sortable, {
            animation: 150,
            ghostClass: 'blue-background-class'
        });

        $(document).ready(function () {
            $("#myDropzone").dropzone({
                paramName: 'image',
                maxFilesize: 1, // MB
                parallelUploads: 1, //一次上一個文件
                addRemoveLinks: true,
                dictRemoveFile: '删除图片',
                dictCancelUpload: '取消上传',
                url: "{{ route('upload', 'book') }}",
                previewsContainer: '.visualization',
                previewTemplate: $('.preview').html(),
                sending: function (file, xhr, formData) {
                    formData.append("_token", "{{ csrf_token() }}");
                },
                init: function () {
                    json_images = '@json($data->json_image_thumb)';

                    files     = $.parseJSON(json_images);
                    var _this = this;
                    $.each(files, function (idx, val) {
                        let file = {
                            name: '',
                            accepted: true,
                            status: 'success',
                            processing: true,
                            multiple: val
                        }

                        _this.emit("addedfile", file);
                        _this.emit("thumbnail", file, file.multiple);
                        _this.emit("complete", file);
                        _ref       = file.previewTemplate.querySelector('[data-multiple]');
                        _ref.value = file.multiple;
                        _ref.name  = 'json_images[' + idx + ']';
                    });

                    this.on("addedfile", function (file) {

                    });

                    this.on("error", function (file) {
                        this.removeFile(file);
                    });

                    this.on("success", function (response, xhr) {
                        console.log('上传成功')
                        console.log(response)
                        _ref       = response.previewTemplate.querySelector('[data-multiple]');
                        _ref.value = xhr.data.path;
                        $('#form').find('input[data-multiple]').each(function (idx) {
                            $(this).attr('name', 'json_images[' + idx + ']');
                        });
                    });
                }
            });

            $('#form').submit(function (e) {
                e.preventDefault();

                // 圖片順序 重新排序
                $('#form').find('input[data-multiple]').each(function (idx) {
                    $(this).attr('name', 'json_images[' + idx + ']');
                });

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
                            parent.$.reloadIFrame({
                                title: '提交成功',
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
