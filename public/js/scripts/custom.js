$(document).ready(function () {
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
		var file = new FormData();
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
				if (res.code == 200) {
					$this.parents('.form-group').find('.upload-image-callback').empty().append('<img src="' + res.data.filename + '">').show();
					$this.parents('.form-group').find('.image-path').val(res.data.filename);
				} else {
                    parent.$.toast({
                        type: 'error',
						message: res.msg
					});
				}
			}, error: function () {

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
				let url = $this.attr('href');
				let params = new URLSearchParams(url);
				console.log(params);
				$.request({
					url     : url,
					type    : 'post',
					data    : (Object.keys(params).length === 0) ? null : params,
					debug   : false,
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
		let $this = $(this);
		let $checked = $('input.check-opt:checked');
		let ids = '';

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
				let url = $this.attr('href') + '/' + ids;
				let params = new URLSearchParams(url);
				console.log(params);
				$.request({
					url     : url,
					type    : 'post',
					data  : (Object.keys(params).length === 0) ? null : params,
					// debug   : true,
					callback: function (res) {
						console.log(res);
						parent.$.reloadIFrame();
					}
				});
			}
		});
	});
});

$.extend({
	toast: function (options) {
		let settings = $.extend({
			type  : 'success',
			message: '',
			title  : '系统提示',
			delay  : 3000,
			debug  : false
		}, options);

		if (settings.debug) {
			console.log('toast debug');
			console.log(settings);
		}

		/*
			let toast = $('#global-toast .toast');
			console.log(toast);
			toast.attr('data-delay', settings.delay);
			toast.find('.toast-title').html(settings.title);
			toast.find('.toast-body').html(settings.message);
			toast.removeClass('hide').toast('show');
		*/
		parent.toastr[settings.type](settings.message, settings.title, {'timeOut': settings.delay, 'debug': settings.debug});
	},
    openImage: function (options) {
        let settings = $.extend({
            title: '',
            size  : '',
            height  : '50vh',
            image  : '',
            debug  : false
        }, options);

        let $modal = $('#global-modal');

        if (settings.debug) {
            console.log('openModal settings');
            console.log(settings);
            console.log($modal);
        }

        $modal.find('.modal-dialog').removeClass('modal-sm modal-lg modal-xl modal-full');
        if (settings.size != '') {
            $modal.find('.modal-dialog').addClass('modal-' + settings.size);
        }
        $modal.find('.modal-title').html(settings.title);
        $modal.on('show.bs.modal', function () {
            if (settings.image) {
                let $html = `<div class="loading-area" style="height: ${settings.height};">
                    <div class="loading">
                        <div class="dot"></div>
                        <div class="dot"></div>
                        <div class="dot"></div>
                        <div class="dot"></div>
                        <div class="dot"></div>
                    </div>
                </div>`;
                $modal.find('.modal-body').html($html);
            }
        });
        $modal.on('shown.bs.modal', function () {
            if (settings.image) {
                setTimeout(function () {
                    $modal.find('.modal-body').html(`<img src="${settings.image}" style="width: 480px;">`);
                }, 500);
            }
        })
        $modal.modal('show');
    },
	openModal: function (options) {
		let settings = $.extend({
			title: '系统弹窗',
			size  : '',
			height  : '50vh',
			url  : '',
			debug  : false
		}, options);

		let $modal = $('#global-modal');

		if (settings.debug) {
			console.log('openModal settings');
			console.log(settings);
			console.log($modal);
		}

		$modal.find('.modal-dialog').removeClass('modal-sm modal-lg modal-xl modal-full');
		if (settings.size != '') {
			$modal.find('.modal-dialog').addClass('modal-' + settings.size);
		}
		$modal.find('.modal-title').html(settings.title);
		$modal.on('show.bs.modal', function () {
			if (settings.url) {
				let $html = `<div class="loading-area" style="height: ${settings.height};">
                    <div class="loading">
                        <div class="dot"></div>
                        <div class="dot"></div>
                        <div class="dot"></div>
                        <div class="dot"></div>
                        <div class="dot"></div>
                    </div>
                </div>`;
				$modal.find('.modal-body').html($html);
			}
		});
		$modal.on('shown.bs.modal', function () {
			if (settings.url) {
				setTimeout(function () {
					$modal.find('.modal-body').html(`<iframe src="${settings.url}" name="modal-frame" frameborder="0" style="border: 0; width: 100%; height: ${settings.height};"></iframe>`);
				}, 500);
			}
		})
		$modal.modal('show');
	},
	hideModal: function () {
		let $modal = $('#global-modal');
		$modal.modal('hide');
	},
	editor: function (options) {
        ClassicEditor.create(options.target, {
            toolbar: {
                items: [
                    'heading',
                    '|',
                    'bold',
                    'italic',
                    'link',
                    'bulletedList',
                    'numberedList',
                    'fontColor',
                    // 'fontSize',
                    'highlight',
                    // 'horizontalLine',
                    // '|',
                    'alignment',
                    // 'indent',
                    // 'outdent',
                    // 'codeBlock',
                    '|',
                    'imageUpload',
                    'blockQuote',
                    // 'insertTable',
                    // 'mediaEmbed',
                    'undo',
                    'redo',
                    '|',
                    'removeFormat'
                ]
            },
            language: 'zh-cn',
            image: {
                toolbar: [
                    'imageTextAlternative',
                    'imageStyle:full',
                    'imageStyle:side'
                ]
            },
            table: {
                contentToolbar: [
                    'tableColumn',
                    'tableRow',
                    'mergeTableCells',
                    'tableCellProperties',
                    'tableProperties'
                ]
            },
            simpleUpload: {
                uploadUrl: options.uploadUrl,
                withCredentials: true,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            }
            // autosave: {
            //     save( editor ) {
            //         console.log(editor.getData());
            //     }
            // }
        }).then(editor => {
            window.editor = editor;
        }).catch(error => {
            console.error('Oops, something went wrong!');
            console.error('Please, report the following error on https://github.com/ckeditor/ckeditor5/issues with the build id and the error stack trace:');
            console.warn('Build id: l0h4f81601ei-bp170a8jwug5');
            console.error(error);
        });
	},
	request: function (options) {
		let settings = $.extend({
			type  : 'post',
			data  : '',
			debug  : false,
			callback  : null
		}, options);

		$.ajax({
			url: settings.url,
			type: settings.type || 'post',
			data: settings.data,
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			},
			dataType: 'json',
			success: function (res) {
				if (settings.debug == true) {
					console.log(res);
				}

                if (settings.callback) {
                    settings.callback(res);
                }
			},
			error: function(xhr, textStatus, errorThrown) {
				if (settings.debug == true) {
					console.log(xhr);
					console.log(textStatus);
					console.log(errorThrown)
				}
                if (settings.callback) {
                    settings.callback(xhr.responseJSON);
                }
			}
		});
	},
	reloadIFrame: function (options) {
		// console.log('reloadIFrame');
		let settings = $.extend({
			icon     : '<i class="bx bx-loader icon-spin"></i>',
			title    : '',
			message  : '数据加载中...',
			timeout  : 2000,
			reloadUrl: null,
			callback : null
		}, options);

		let element = $('#main-content');

		$(element).block({
			message: `<span class="semibold">${settings.icon} ${settings.message}</span>`,
			timeout: settings.timeout, //unblock after 2 seconds
			overlayCSS: {
				backgroundColor: '#5A8DEE',
				opacity: 0.8,
				cursor: 'wait'
			},
			css: {
				width: 200,
				height: 50,
				lineHeight: 1,
				border: 0,
				borderRadius: 30,
				padding: 15,
				color: '#5A8DEE',
				backgroundColor: '#F2F4F4'
			},
			onBlock: function () {
				console.log(settings.reloadUrl);
				if (settings.reloadUrl == null) {
					document.getElementById('content-frame').contentWindow.location.reload(true);
				} else {
					document.getElementById('content-frame').src = settings.reloadUrl;
				}

				setTimeout(function () {
					parent.$.toast({
						title: settings.title,
						message: '数据已刷新'
					});
				}, settings.timeout);
			}
		});
	},
	confirm: function (options) {
		let settings = $.extend({
			// title  : '操作确认',
			// type: 'warning',
			text: '是否确认执行此操作？',
			showCancelButton: true,
			confirmButtonColor: '#719DF0',
			cancelButtonColor: '#FF5B5C',
			confirmButtonText: '确认',
			confirmButtonClass: 'btn btn-primary',
			cancelButtonText: '取消',
			cancelButtonClass: 'btn btn-danger ml-1',
			buttonsStyling: false,
			callback: null
		}, options);

		Swal.fire({
			// title  : settings.title,
			// type:  settings.type,
			text: settings.text,
			showCancelButton: settings.showCancelButton,
			confirmButtonColor: settings.confirmButtonColor,
			cancelButtonColor: settings.cancelButtonColor,
			confirmButtonText: settings.confirmButtonText,
			confirmButtonClass: settings.confirmButtonClass,
			cancelButtonText: settings.cancelButtonText,
			cancelButtonClass: settings.cancelButtonClass,
			buttonsStyling: settings.buttonsStyling,
			// callback: null
		}).then(function (result) {
			if (result.value) {
				if (typeof settings.callback === 'function') {
					settings.callback();
				}
			}
		});
	}
});

$.fn.serializeObject = function () {
    var obj = {};
    $.each(this.serializeArray(), function (index, param) {
        if (!(param.name in obj)) {
            obj[param.name] = param.value;
        }
    });
    return obj;
};

