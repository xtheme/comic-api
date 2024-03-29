(function (window, undefined) {
    'use strict';

    /*
    NOTE:
    ------
    PLACE HERE YOUR OWN JAVASCRIPT CODE IF NEEDED
    WE WILL RELEASE FUTURE UPDATES SO IN ORDER TO NOT OVERWRITE YOUR JAVASCRIPT CODE PLEASE CONSIDER WRITING YOUR SCRIPT HERE.  */

    // 檔案上傳
    $('.upload-image').on('click', function () {
        const file = $(this).parents('.input-group').find('.hidden-file-upload');
        file.trigger('click');
    });

    // 檔案上傳
    // <div class="input-group">
    //     <input type="file" class="hidden-file-upload" data-path="{path}">
    //     <input type="text" class="form-control image-path" name="avatar" autocomplete="off" aria-describedby="input-file-addon">
    //     <div class="input-group-append" id="input-file-addon">
    //         <button class="btn btn-primary upload-image" type="button">上传</button>
    //     </div>
    // </div>
    $('.hidden-file-upload').on('change', function () {
        const $this = $(this);
        const file = new FormData();
        file.append('image', $this.get(0).files[0]);
        $.ajax({
            url: `/upload/${$this.attr('data-path')}`,
            type: 'post',
            cache: false,
            contentType: false,
            processData: false,
            dataType: 'json',
            data: file,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function (res) {
                console.log(res);
                if (res.code == 200) {
                    $this.parents('.form-group').find('.upload-image-callback').empty().append(`<img src="${res.data.url}" class="img-fluid mx-auto d-block" alt="Responsive image">`).show();
                    $this.parents('.form-group').find('.image-path').val(res.data.path);
                } else {
                    $.toast({
                        type: 'error',
                        message: res.msg
                    });
                }
            },
            error: function (xhr) {
                var res = JSON.parse(xhr.responseText);
                parent.$.toast({
                    type: 'error',
                    message: res.msg
                });
            }
        });
    });

    // 連結開啟彈窗 iframe
    // <a class="dropdown-item" data-modal href="{{ url('/admin/config/update/' . $item->id) }}" title="修改配置">修改配置</a>
    $('[data-modal]').on('click', function (e) {
        e.preventDefault();
        let $this = $(this);
        $.openModal({
            size: $this.data('size') || 'xl',
            height: $this.data('height') || '50vh',
            title: $this.attr('title'),
            url: $this.attr('href')
        });
    });

    $('[data-lightbox]').on('click', function (e) {
        e.preventDefault();
        let $this = $(this);
        $.openImage({
            size: $this.data('size') || '',
            height: $this.data('height') || '',
            title: '检视图片',
            image: $this.attr('src')
        });
    });

    function confirmThenUpdate($this, $action) {
        let url = $this.attr('href');
        let params = new URLSearchParams(url);

        $.confirm({
            text: `请确认是否要${$this.attr('title')}?`,
            callback: function () {
                $.request({
                    url: url,
                    type: $action,
                    data: (Object.keys(params).length === 0) ? null : params,
                    // debug: true,
                    callback: function (res) {
                        // console.log(res);
                        if (res.code != 200) {
                            $.toast({
                                type: 'error',
                                message: res.msg
                            });
                        } else {
                            $.reloadIFrame({title: res.msg});
                        }
                    }
                });
            }
        });
    }

    // 更新確認彈窗
    // <a data-confirm href="{{ route('backend.user.topic', $item->id) }}" title="封禁该账号">封禁该账号</a>
    $('[data-confirm]').on('click', function (e) {
        e.preventDefault();

        let $this = $(this);
        confirmThenUpdate($this, 'put');
    });

    // 删除確認彈窗
    // <a data-destroy href="{{ route('backend.user.topic', $item->id) }}" title="封禁该账号">封禁该账号</a>
    $('[data-destroy]').on('click', function (e) {
        e.preventDefault();

        let $this = $(this);
        confirmThenUpdate($this, 'delete');
    });

    function modalConfirmThenUpdate($this, $action) {
        let url = $this.attr('href');
        let params = new URLSearchParams(url);

        $.confirm({
            text: `请确认是否要${$this.attr('title')}?`,
            callback: function () {
                $.request({
                    url: url,
                    type: $action,
                    data: (Object.keys(params).length === 0) ? null : params,
                    // debug: true,
                    callback: function (res) {
                        let $iframe = parent.$('#global-modal .modal-body iframe');
                        // console.log($iframe.attr('src'));
                        $iframe.attr('src', $iframe.attr('src'));

                        parent.$.toast({
                            title: res.msg,
                            message: '请稍后数据刷新'
                        });
                    }
                });
            }
        });
    }

    $('[data-modal-confirm]').on('click', function (e) {
        e.preventDefault();

        let $this = $(this);
        modalConfirmThenUpdate($this, 'put');
    });

    // 列表 checkbox 全选
    $('input.check-all').on('click', function () {
        if (this.checked) {
            $('input.check-opt').prop('checked', true);
        } else {
            $('input.check-opt').prop('checked', false);
        }
    });

    $('input.check-opt').on('click', function () {
        if ($('input.check-opt').length == $('input.check-opt:checked').length) {
            $('input.check-all').prop('checked', true);
        } else {
            $('input.check-all').prop('checked', false);
        }
    });

    $('a[data-batch]').on('click', function (e) {
        e.preventDefault();
        let $this = $(this);

        let ids = $.checkedIds();

        $.confirm({
            text: `请确认是否要继续批量操作?`,
            callback: function () {
                let url = $this.attr('href') + '/' + ids;
                let params = new URLSearchParams(url);
                $.request({
                    url: url,
                    type: 'post',
                    data: (Object.keys(params).length === 0) ? null : params,
                    // debug   : true,
                    callback: function (res) {
                        $.reloadIFrame();
                    }
                });
            }
        });
    });

})(window);
