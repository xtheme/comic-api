!function(e,t,a){var s="#5A8DEE";a("#table-extended-transactions").DataTable({responsive:!0,searching:!1,lengthChange:!1,paging:!1,bInfo:!1,columnDefs:[{orderable:!1,targets:2}]}),a("#table-extended-success").DataTable({responsive:!0,searching:!1,lengthChange:!1,paging:!1,bInfo:!1,columnDefs:[{orderable:!1,targets:[1,2,3,4,5]}]});a("#table-extended-chechbox").DataTable({searching:!1,lengthChange:!1,paging:!1,bInfo:!1,columnDefs:[{orderable:!1,targets:[0,3,4]},{targets:0,render:function(e,t,a,s){return"display"===t&&(e='<div class="checkbox"><input type="checkbox" class="dt-checkboxes"><label></label></div>'),e},checkboxes:{selectRow:!0,selectAllRender:'<div class="checkbox"><input type="checkbox" class="dt-checkboxes" checked=""><label></label></div >'}}],select:"multi",order:[[1,"asc"]]});a(".single-daterange").daterangepicker({singleDatePicker:!0,showDropdowns:!0,minYear:1990,maxYear:parseInt(moment().format("YYYY"),10)});var n={chart:{width:80,height:100,type:"donut"},dataLabels:{enabled:!1},series:[70,30,40],labels:["Installation","Page Views","Active Users"],stroke:{width:2},colors:["#FDAC41","#00CFDD",s],plotOptions:{pie:{offsetY:15,donut:{size:"70%"}}},legend:{show:!1}};(n=new ApexCharts(t.querySelector("#table-donut-chart-1"),n)).render();var l={chart:{width:80,height:100,type:"donut"},dataLabels:{enabled:!1},series:[70,40,30],labels:["Installation","Page Views","Active Users"],stroke:{width:2},colors:["#FF5B5C","#828D99",s],plotOptions:{pie:{offsetY:15,donut:{size:"70%"}}},legend:{show:!1}};(l=new ApexCharts(t.querySelector("#table-donut-chart-2"),l)).render()}(window,document,jQuery);