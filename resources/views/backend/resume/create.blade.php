@extends('layouts.modal')

{{-- vendor style --}}
@section('vendor-styles')
    <link rel="stylesheet" type="text/css" href="{{ asset('vendors/css/file-uploader/dropzone.min.css') }}">
@endsection

{{-- page style --}}
@section('page-styles')
    <link rel="stylesheet" type="text/css" href="{{ asset('css/plugins/file-uploader/dropzone.css') }}">
@endsection

@section('content')
    <form id="form" class="form" method="post" action="{{ route('backend.resume.store') }}" novalidate>
        <div class="form-body">
            <div class="row">
                <div class="col-4">
                    <div class="form-group">
                        <label><span class="danger">*</span> 昵称</label>
                        <div class="controls">
                            <input type="text" class="form-control" name="nickname" placeholder="请输入昵称" value="">
                        </div>
                    </div>
                </div>
                <div class="col-4">
                    <div class="form-group">
                        <span class="float-right font-size-small text-muted">(换算年龄用)</span>
                        <label><span class="danger">*</span> 出生年份</label>
                        <div class="controls">
                            <input type="text" class="form-control" name="birth_year" placeholder="请输入出生年份" value="">
                        </div>
                    </div>
                </div>
                <div class="col-4">
                    <div class="form-group">
                        <label><span class="danger">*</span> 罩杯</label>
                        <div class="controls">
                            <input type="text" class="form-control" name="cup" placeholder="请输入罩杯" value="">
                        </div>
                    </div>
                </div>
                <div class="col-4">
                    <div class="form-group">
                        <label><span class="danger">*</span> 省份</label>
                        <div class="controls">
                            <select class="form-control" name="province_id" id="province_id">
                                <option value=""> ---</option>
                                @foreach($provinces as $province_id => $province_name)
                                    <option value="{{ $province_id }}">{{ $province_name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-4">
                    <div class="form-group">
                        <label><span class="danger">*</span> 城市</label>
                        <div class="controls">
                            <select class="form-control" name="city_id" id="city_id">
                                <option value=""> ---</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-4">
                    <div class="form-group">
                        <label><span class="danger">*</span> 区县</label>
                        <div class="controls">
                            <select class="form-control" name="area_id" id="area_id">
                                <option value=""> ---</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-4">
                    <div class="form-group">
                        <label><span class="danger">*</span> QQ</label>
                        <div class="controls">
                            <input type="text" class="form-control" name="content[qq]" placeholder="请至少提供一种联系方式" value="">
                        </div>
                    </div>
                </div>
                <div class="col-4">
                    <div class="form-group">
                        <label><span class="danger">*</span> 微信</label>
                        <div class="controls">
                            <input type="text" class="form-control" name="content[wechat]" placeholder="请至少提供一种联系方式" value="">
                        </div>
                    </div>
                </div>
                <div class="col-4">
                    <div class="form-group">
                        <label><span class="danger">*</span> 手机号</label>
                        <div class="controls">
                            <input type="text" class="form-control" name="content[phone]" placeholder="请至少提供一种联系方式" value="">
                        </div>
                    </div>
                </div>
                <div class="col-4">
                    <div class="form-group">
                        <label><span class="danger">*</span> 价位</label>
                        <div class="controls">
                            <input type="text" class="form-control" name="price" placeholder="请输入价位" value="0">
                        </div>
                    </div>
                </div>
                <div class="col-4">
                    <div class="form-group">
                        <label><span class="danger">*</span> 解锁点数</label>
                        <div class="controls">
                            <input type="text" class="form-control" name="point" placeholder="请输入点数" value="5">
                        </div>
                    </div>
                </div>
                <div class="col-4">
                    <div class="form-group">
                        <label><span class="danger">*</span> 状态</label>
                        <div class="controls">
                            <select class="form-control" name="status">
                                <option value="1">上架</option>
                                <option value="0">下架</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group">
                        <label><span class="danger">*</span> 身型</label>
                        <div class="form-group">
                            <div class="controls">
                                <fieldset>
                                    @foreach($body_shape as $tag)
                                        <div class="checkbox m-25">
                                            <input type="checkbox" name="body_shape[]" id="{{ $tag }}" value="{{ $tag }}">
                                            <label for="{{ $tag }}">{{ $tag }}</label>
                                        </div>
                                    @endforeach
                                </fieldset>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group">
                        <label><span class="danger">*</span> 服务项目</label>
                        <div class="form-group">
                            <div class="controls">
                                <fieldset>
                                    @foreach($service_type as $tag)
                                        <div class="checkbox m-25">
                                            <input type="checkbox" name="service[]" id="{{ $tag }}" value="{{ $tag }}">
                                            <label for="{{ $tag }}">{{ $tag }}</label>
                                        </div>
                                    @endforeach
                                </fieldset>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12">
                    <div class="form-group">
                        <label><span class="danger">*</span> 照片</label>
                        <div class="input-group">
                            <input type="text" class="form-control image-path" name="cover" autocomplete="off">
                            <input type="file" class="hidden-file-upload" data-path="resume">
                            <div class="input-group-append">
                                <button class="btn btn-primary upload-image" type="button">上传</button>
                            </div>
                        </div>
                        <div class="upload-image-callback"></div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group">
                        <label>视频</label>
                        <div class="input-group">
                            <input type="text" class="form-control image-path" name="video" autocomplete="off">
                            <input type="file" class="hidden-file-upload" data-path="resume">
                            <div class="input-group-append">
                                <button class="btn btn-primary upload-image" type="button">上传</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group">
                        <label>更多照片</label>
                        <div class="controls">
                            <div id="dpz-multiple-files" class="dropzone sortable dropzone-area">
                                <div class="dz-message">请拖拉图片到此上传</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 d-flex justify-content-end">
                    <input type="hidden" name="agent_type" value="admin">
                    <input type="hidden" name="agent_id" value="{{ auth()->id() }}">
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
            url: '{{ route('upload', 'book_chapter') }}',
            headers: {
                'X-CSRF-Token': '{{ csrf_token() }}'
            },
            error: function (file, response) {
                // this.removeFile(file);
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
        const sortZone = document.getElementById('dpz-multiple-files');
        new Sortable(sortZone, {
            draggable: '.dz-preview'
        });

        $(document).ready(function () {
            function chainSelect(current, target) {
                $('#' + current + '_id').on('change', function () {
                    let $id = '';
                    if ($(this).find(':selected').val() != '') {
                        $(target).removeAttr('disabled');
                        $id = $(this).find(':selected').val();
                    }
                    console.log($id);
                    if ($id) {
                        let $url = '/backend/location/' + target + '/' + $id;
                        console.log($url);
                        $.ajax({
                            url: $url,
                            type: 'GET',
                            dataType: 'json',
                            success: function (data) {
                                let newTarget = $('#' + target + '_id');
                                newTarget.empty();
                                newTarget.append('<option value="">---</option>');
                                $.each(data, function (key, value) {
                                    newTarget.append('<option value="' + key + '">' + value + '</option>');
                                });
                            }
                        });
                    } else {
                        $('select[name="cities"]').empty();
                    }
                });
            }

            chainSelect('province', 'city');
            chainSelect('city', 'area');

            $('#form').submit(function (e) {
                e.preventDefault();

                $.request({
                    url: $(this).attr('action'),
                    type: $(this).attr('method'),
                    data: $('#form').serialize(),
                    // debug: true,
                    callback: function (res) {
                        if (res.code === 200) {
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
