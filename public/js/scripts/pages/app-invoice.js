$(document).ready((function(){if($(".pickadate").length&&$(".pickadate").pickadate({format:"mm/dd/yyyy"}),$(".invoice-data-table").length)$(".invoice-data-table").DataTable({columnDefs:[{targets:0,className:"control"},{orderable:!0,targets:1,checkboxes:{selectRow:!0}},{targets:[0,1],orderable:!1}],order:[2,"asc"],dom:'<"top d-flex flex-wrap"<"action-filters flex-grow-1"f><"actions action-btns d-flex align-items-center">><"clear">rt<"bottom"p>',language:{search:"",searchPlaceholder:"Search Invoice"},select:{style:"multi",selector:"td:first-child",items:"row"},responsive:{details:{type:"column",target:0}}});var e=$(".invoice-filter-action"),t=$(".invoice-options");$(".action-btns").append(e,t),$(".dt-checkboxes-cell").find("input").on("change",(function(){var e=$(this);e.is(":checked")?e.closest("tr").addClass("selected-row-bg"):e.closest("tr").removeClass("selected-row-bg")})),$(document).on("change",".dt-checkboxes-select-all input",(function(){$(this).is(":checked")?$(".dt-checkboxes-cell").find("input").prop("checked",this.checked).closest("tr").addClass("selected-row-bg"):$(".dt-checkboxes-cell").find("input").prop("checked","").closest("tr").removeClass("selected-row-bg")})),$(".invoice-item-repeater").length&&$(".invoice-item-repeater").repeater({show:function(){$(this).slideDown()},hide:function(e){$(this).slideUp(e)}}),$(document).on("click",".invoice-tax",(function(e){e.stopPropagation()})),$(document).on("click",".invoice-apply-btn",(function(){var e=$(this),t=e.closest(".dropdown-menu").find("#discount").val();""===t?t="0%":t>100?t="100%":t+="%";var i=e.closest(".dropdown-menu").find("#Tax1 option:selected").text(),n=e.closest(".dropdown-menu").find("#Tax2 option:selected").text();e.parents().eq(4).find(".discount-value").html(t),e.parents().eq(4).find(".tax1").html(i),e.parents().eq(4).find(".tax2").html(n)})),$(document).on("change",".invoice-item-select",(function(e){switch(this.options[e.target.selectedIndex].text){case"Frest Admin Template":$(e.target).closest(".invoice-item-filed").find(".invoice-item-desc").val("The most developer friendly & highly customisable HTML5 Admin");break;case"Stack Admin Template":$(e.target).closest(".invoice-item-filed").find(".invoice-item-desc").val("Ultimate Bootstrap 4 Admin Template for Next Generation Applications.");break;case"Robust Admin Template":$(e.target).closest(".invoice-item-filed").find(".invoice-item-desc").val("Robust admin is super flexible, powerful, clean & modern responsive bootstrap admin template with unlimited possibilities");break;case"Apex Admin Template":$(e.target).closest(".invoice-item-filed").find(".invoice-item-desc").val("Developer friendly and highly customizable Angular 7+ jQuery Free Bootstrap 4 gradient ui admin template. ");break;case"Modern Admin Template":$(e.target).closest(".invoice-item-filed").find(".invoice-item-desc").val("The most complete & feature packed bootstrap 4 admin template of 2019!")}})),$(".invoice-print").length>0&&$(".invoice-print").on("click",(function(){window.print()}))}));
