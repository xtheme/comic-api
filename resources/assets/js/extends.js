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

