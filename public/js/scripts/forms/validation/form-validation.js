$((function(){"use strict";var e=$("#jquery-val-form");$(".select2").each((function(){var e=$(this);e.wrap('<div class="position-relative"></div>'),e.select2({placeholder:"Select value",dropdownParent:e.parent()}).change((function(){$(this).valid()}))})),e.length&&e.validate({rules:{"basic-default-name":{required:!0},"basic-default-email":{required:!0,email:!0},"basic-default-password":{required:!0},"confirm-password":{required:!0,equalTo:"#basic-default-password"},"select-country":{required:!0},customFile:{required:!0},validationRadiojq:{required:!0},validationBiojq:{required:!0},validationCheck:{required:!0}}})}));
