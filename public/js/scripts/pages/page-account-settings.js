form=$(".validate-form"),function(e,r,i){"use strict";var t=i(".validate-form");i("#languageselect2").select2({dropdownAutoWidth:!0,width:"100%"}),i("#musicselect2").select2({dropdownAutoWidth:!0,width:"100%"}),i("#moviesselect2").select2({dropdownAutoWidth:!0,width:"100%"});i(".birthdate-picker").pickadate({format:"mmmm, d, yyyy"}),t.length&&t.each((function(){var e=i(this);e.validate({rules:{username:{required:!0},name:{required:!0},email:{required:!0,email:!0},password:{required:!0},company:{required:!0},"new-password":{required:!0,minlength:6},"confirm-new-password":{required:!0,minlength:6,equalTo:"#account-new-password"},dob:{required:!0},phone:{required:!0},website:{required:!0}}}),e.on("submit",(function(e){e.preventDefault()}))}))}(window,document,jQuery);
