(function (window, undefined) {
    'use strict';

    /*
    NOTE:
    ------
    PLACE HERE YOUR OWN JAVASCRIPT CODE IF NEEDED
    WE WILL RELEASE FUTURE UPDATES SO IN ORDER TO NOT OVERWRITE YOUR JAVASCRIPT CODE PLEASE CONSIDER WRITING YOUR SCRIPT HERE.  */

    // 檔案上傳
    $('.upload-image').on('click', function () {
        var file = $('.hidden-file-upload');
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
        var $this = $(this);
        var file  = new FormData();
        file.append('image', $this.get(0).files[0]);
        $.ajax({
            url: '/upload/' + $this.attr('data-path'),
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
                    $this.parents('.form-group').find('.upload-image-callback').empty().append('<img src="' + res.data.filename + '">').show();
                    $this.parents('.form-group').find('.image-path').val(res.data.filename);
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

    // 確認彈窗
    // <a class="dropdown-item" data-confirm href="{{ url('/admin/config/destroy/' . $item->id) }}" title="刪除配置"><i class="bx bx-trash mr-1"></i> 删除</a>
    $('[data-confirm]').on('click', function (e) {
        e.preventDefault();
        let $this = $(this);
        Swal.fire({
            // title: 'Are you sure?',
            // type: 'warning',
            text: `请确认是否要执行${$this.attr('title')}?`,
            showCancelButton: true,
            confirmButtonColor: '#719DF0',
            cancelButtonColor: '#FF5B5C',
            confirmButtonText: '确认',
            confirmButtonClass: 'btn btn-primary',
            cancelButtonText: '取消',
            cancelButtonClass: 'btn btn-danger ml-1',
            buttonsStyling: false,
        }).then(function (result) {
            console.log(result);
            if (result.value) {
                let url    = $this.attr('href');
                let params = new URLSearchParams(url);
                console.log(params);
                $.request({
                    url: url,
                    type: 'put',
                    data: (Object.keys(params).length === 0) ? null : params,
                    debug: false,
                    callback: function (res) {
                        parent.$.reloadIFrame({title: res.msg});
                    }
                });
            }
        });
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
        let $this    = $(this);
        let $checked = $('input.check-opt:checked');
        let ids      = '';

        if ($checked.length == 0) {
            parent.$.toast({
                message: '请先选择要操作的数据'
            });
            return false;
        }

        $checked.each(function (index) {
            if (index == 0) {
                ids += $(this).val();
            } else {
                ids += ',' + $(this).val();
            }
        });

        Swal.fire({
            // title: '',
            // type: 'warning',
            text: `请确认是否要继续批量操作?`,
            showCancelButton: true,
            confirmButtonColor: '#719DF0',
            cancelButtonColor: '#FF5B5C',
            confirmButtonText: '确认',
            confirmButtonClass: 'btn btn-primary',
            cancelButtonText: '取消',
            cancelButtonClass: 'btn btn-danger ml-1',
            buttonsStyling: false,
        }).then(function (result) {
            console.log(result);
            if (result.value) {
                let url    = $this.attr('href') + '/' + ids;
                let params = new URLSearchParams(url);
                console.log(params);
                $.request({
                    url: url,
                    type: 'post',
                    data: (Object.keys(params).length === 0) ? null : params,
                    // debug   : true,
                    callback: function (res) {
                        console.log(res);
                        parent.$.reloadIFrame();
                    }
                });
            }
        });
    });

})(window);
