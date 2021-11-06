@extends('layouts.modal')

@section('vendor-styles')
    <link rel="stylesheet" type="text/css" href="{{ asset('vendors/css/file-uploader/dropzone.min.css') }}">
@endsection

@section('page-styles')
    <link rel="stylesheet" type="text/css" href="{{ asset('css/plugins/file-uploader/dropzone.css') }}">
@endsection

@section('content')
    <form id="form" class="form" method="post" action="{{ route('backend.book_chapter.update' , $data->id) }}">
        @method('put')
        <div class="form-body">
            <div class="row">
                <div class="col-3">
                    <div class="form-group">
                        <label><span class="danger">*</span> 章节</label>
                        <div class="controls">
                            <input type="text" class="form-control" name="episode" value="{{ $data->episode }}">
                        </div>
                    </div>
                </div>
                <div class="col-3">
                    <div class="form-group">
                        <label>章节标题</label>
                        <div class="controls">
                            <input type="text" class="form-control" name="title" value="{{ $data->title }}">
                        </div>
                    </div>
                </div>
                <div class="col-3">
                    <div class="form-group">
                        <label><span class="danger">*</span> 售价</label>
                        <div class="controls">
                            <input type="text" class="form-control" name="price" value="{{ $data->price }}">
                        </div>
                    </div>
                </div>
                <div class="col-3">
                    <div class="form-group">
                        <label>上架状态</label>
                        <div class="controls">
                            <select class="form-control" name="status">
                                <option value="1" @if(1 == $data->status){{'selected'}}@endif>上架</option>
                                <option value="0" @if(0 == $data->status){{'selected'}}@endif>下架</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group">
                        <label><span class="danger">*</span> 章节详情</label>
                        <div class="controls">
                            <span id="clear-dropzone" class="btn btn-primary mb-1"><i class="icon-trash4"></i>清空图片</span>
                            <div id="dpz-multiple-files" class="dropzone sortable dropzone-area">
                                <div class="dz-message">请拖拉图片到此上传</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 d-flex justify-content-end">
                    <input type="hidden" name="operating" value="1">
                    <button type="submit" class="btn btn-primary">提交</button>
                </div>
            </div>
        </div>
    </form>
@endsection

{{-- vendor scripts --}}
@section('vendor-scripts')
    <script src="{{ asset('vendors/js/file-uploader/dropzone.min.js') }}"></script>
    <script src="{{ asset('vendors/js/sortable.js') }}"></script>
@endsection

{{-- page scripts --}}
@section('page-scripts')
    <script>
        Dropzone.options.dpzMultipleFiles = {
            paramName: 'image',
            maxFilesize: 1, // MB
            parallelUploads: 1, //一次上一個文件
            addRemoveLinks: true,
            dictRemoveFile: '删除图片',
            dictCancelUpload: '取消上传',
            url: '{{ route('upload', 'book_chapter/' . $data->id) }}',
            headers: {
                'X-CSRF-Token': '{{ csrf_token() }}'
            },
            init: function () {
                myDropzone  = this;
                json_images = '@json($data->content)';
                images      = $.parseJSON(json_images);
                $.each(images, function (idx, val) {
                    let file = {
                        name: idx
                    }
                    myDropzone.files.push(file);
                    myDropzone.emit('addedfile', file);
                    myDropzone.emit('thumbnail', file, val);
                    myDropzone.emit('complete', file);

                    // 隱藏字段
                    var input = '<input type="hidden" name="json_images[]" value="' + val + '">';
                    $(file.previewTemplate).append(input);
                    // 移除 size
                    $(file.previewTemplate).find('.dz-size').remove();

	                $("#clear-dropzone").on('click', function () {
		                myDropzone.removeAllFiles();
	                });
                });
            },
            error: function (file, response) {
                this.removeFile(file);
            },
            success: function (file, response) {
                console.log('上传成功');
                console.log(file);
                console.log(response);
                // 隱藏字段
                var input = '<input type="hidden" name="json_images[]" value="' + response.data.path + '">';
                $(file.previewTemplate).append(input);
                // 移除 size
                $(file.previewTemplate).find('.dz-size').remove();
            },
            removedfile: function (file) {
                console.log(file);
                var fileName = $(file.previewTemplate).children('input[name="json_images[]"]').val();
                console.log(fileName);
                $.ajax({
                    type: 'POST',
                    headers: {
                        'X-CSRF-Token': '{{ csrf_token() }}'
                    },
                    url: '{{ route('unlink') }}',
                    data: {path: fileName},
                    sucess: function (data) {
                        console.log('success: ' + data);
                    }
                });

                var _ref;
                return (_ref = file.previewElement) != null ? _ref.parentNode.removeChild(file.previewElement) : void 0;
            }
        };

        // 排序
        var el = document.getElementById('dpz-multiple-files');
        new Sortable(el, {
            draggable: '.dz-preview'
        });

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
