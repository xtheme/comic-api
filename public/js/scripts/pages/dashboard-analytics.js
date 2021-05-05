$(window).on("load",(function(){var e="#5A8DEE",o="#39DA8A",t="#FF5B5C",a="#FDAC41",r="#00CFDD",s="#ffeed9",i="#828D99",l={chart:{height:40,width:40,type:"radialBar"},grid:{show:!1,padding:{left:-13,right:-13,top:0}},series:[30],colors:[o],plotOptions:{radialBar:{hollow:{size:"30%"},dataLabels:{showOn:"always",name:{show:!1},value:{show:!1}}}},fill:{type:"gradient",gradient:{shade:"light",type:"horizontal",gradientToColors:[o],opacityFrom:1,opacityTo:.8,stops:[0,70,100]}},stroke:{lineCap:"round"}};new ApexCharts(document.querySelector("#radial-success-chart"),l).render();var n={chart:{height:40,width:40,type:"radialBar"},grid:{show:!1,padding:{left:-13,right:-13,top:0}},series:[80],colors:[a],plotOptions:{radialBar:{hollow:{size:"30%"},dataLabels:{showOn:"always",name:{show:!1},value:{show:!1}}}},fill:{type:"gradient",gradient:{shade:"light",type:"horizontal",gradientToColors:[a],opacityFrom:1,opacityTo:.8,stops:[0,70,100]}},stroke:{lineCap:"round"}};new ApexCharts(document.querySelector("#radial-warning-chart"),n).render();var d={chart:{height:40,width:40,type:"radialBar"},grid:{show:!1,padding:{left:-13,right:-13,top:0}},series:[50],colors:[t],plotOptions:{radialBar:{hollow:{size:"30%"},dataLabels:{showOn:"always",name:{show:!1},value:{show:!1}}}},fill:{type:"gradient",gradient:{shade:"light",type:"horizontal",gradientToColors:[t],opacityFrom:1,opacityTo:.8,stops:[0,70,100]}},stroke:{lineCap:"round"}};new ApexCharts(document.querySelector("#radial-danger-chart"),d).render();var h={chart:{height:260,type:"bar",toolbar:{show:!1}},plotOptions:{bar:{horizontal:!1,columnWidth:"20%",endingShape:"rounded"}},dataLabels:{enabled:!1},colors:[e,"#B6CDF8"],fill:{type:"gradient",gradient:{shade:"light",type:"vertical",inverseColors:!0,opacityFrom:1,opacityTo:1,stops:[0,70,100]}},series:[{name:"2020",data:[80,95,150,210,140,230,300,280,130]},{name:"2019",data:[50,70,130,180,90,180,270,220,110]}],xaxis:{categories:["Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep"],axisBorder:{show:!1},axisTicks:{show:!1},labels:{style:{colors:i}}},yaxis:{min:0,max:300,tickAmount:3,labels:{style:{color:i}}},legend:{show:!1},tooltip:{y:{formatter:function(e){return"$ "+e+" thousands"}}}};new ApexCharts(document.querySelector("#analytics-bar-chart"),h).render();var p={chart:{height:100,type:"line",toolbar:{show:!1}},grid:{show:!1,padding:{bottom:0}},colors:[o],dataLabels:{enabled:!1},stroke:{width:3,curve:"smooth"},series:[{data:[50,0,50,40,90,0,40,25,80,40,45]}],xaxis:{show:!1,labels:{show:!1},axisBorder:{show:!1}},yaxis:{show:!1}};new ApexCharts(document.querySelector("#success-line-chart"),p).render();var c={chart:{width:180,type:"donut"},dataLabels:{enabled:!1},series:[80,30,60],labels:["Social","Email","Search"],stroke:{width:0,lineCap:"round"},colors:[e,r,a],plotOptions:{pie:{donut:{size:"90%",labels:{show:!0,name:{show:!0,fontSize:"15px",colors:"#596778",offsetY:20,fontFamily:"IBM Plex Sans"},value:{show:!0,fontSize:"26px",fontFamily:"Rubik",color:"#475f7b",offsetY:-20,formatter:function(e){return e}},total:{show:!0,label:"Impression",color:i,formatter:function(e){return e.globals.seriesTotals.reduce((function(e,o){return e+o}),0)}}}}}},legend:{show:!1}};new ApexCharts(document.querySelector("#donut-chart"),c).render();var w={chart:{height:110,stacked:!0,type:"bar",toolbar:{show:!1},sparkline:{enabled:!0}},plotOptions:{bar:{columnWidth:"20%",endingShape:"rounded"},distributed:!0},colors:[e,a],series:[{name:"New Clients",data:[75,150,225,200,35,50,150,180,50,150,240,140,75,35,60,120]},{name:"Retained Clients",data:[-100,-55,-40,-120,-70,-40,-60,-50,-70,-30,-60,-40,-50,-70,-40,-50]}],grid:{show:!1},legend:{show:!1},dataLabels:{enabled:!1},tooltip:{x:{show:!1}}};new ApexCharts(document.querySelector("#bar-negative-chart"),w).render();var g={chart:{height:40,type:"line",toolbar:{show:!1},sparkline:{enabled:!0}},grid:{show:!1,padding:{bottom:5,top:5,left:10,right:0}},colors:[e],dataLabels:{enabled:!1},stroke:{width:3,curve:"smooth"},series:[{data:[50,100,0,60,20,30]}],fill:{type:"gradient",gradient:{shade:"dark",type:"horizontal",gradientToColors:[e],opacityFrom:0,opacityTo:.9,stops:[0,30,70,100]}},xaxis:{show:!1,labels:{show:!1},axisBorder:{show:!1}},yaxis:{show:!1}};new ApexCharts(document.querySelector("#primary-line-chart"),g).render();var u={chart:{height:40,type:"line",toolbar:{show:!1},sparkline:{enabled:!0}},grid:{show:!1,padding:{bottom:5,top:5,left:10,right:0}},colors:[a],dataLabels:{enabled:!1},stroke:{width:3,curve:"smooth"},series:[{data:[30,60,30,80,20,70]}],fill:{type:"gradient",gradient:{shade:"dark",type:"horizontal",gradientToColors:[a],opacityFrom:0,opacityTo:.9,stops:[0,30,70,100]}},xaxis:{show:!1,labels:{show:!1},axisBorder:{show:!1}},yaxis:{show:!1}};new ApexCharts(document.querySelector("#warning-line-chart"),u).render();var y={chart:{height:40,width:40,type:"radialBar",sparkline:{show:!0}},grid:{show:!1,padding:{left:-13,right:-13,top:-5,bottom:0}},series:[50],colors:[e],plotOptions:{radialBar:{hollow:{size:"30%"},dataLabels:{showOn:"always",name:{show:!1},value:{show:!1}}}},stroke:{lineCap:"round"}};new ApexCharts(document.querySelector("#profit-primary-chart"),y).render();var m={chart:{height:40,width:40,type:"radialBar",sparkline:{show:!0}},grid:{show:!1,padding:{left:-13,right:-13,top:-5,bottom:0}},series:[70],colors:[r],plotOptions:{radialBar:{hollow:{size:"30%"},dataLabels:{showOn:"always",name:{show:!1},value:{show:!1}}}},stroke:{lineCap:"round"}};new ApexCharts(document.querySelector("#profit-info-chart"),m).render();var b={chart:{type:"bar",height:60,width:140,sparkline:{enabled:!0},toolbar:{show:!1}},states:{hover:{filter:"none"}},colors:[s,s,s,s,a,s],series:[{name:"Sessions",data:[3,7,5,15,9,8,12]}],grid:{show:!1,padding:{left:0,right:0}},plotOptions:{bar:{columnWidth:"80%",distributed:!0}},tooltip:{x:{show:!1}},xaxis:{type:"numeric"}};new ApexCharts(document.querySelector("#registration-chart"),b).render();var f={chart:{height:100,type:"bar",stacked:!0,toolbar:{show:!1}},grid:{show:!1,padding:{left:0,right:0,top:-20,bottom:-15}},plotOptions:{bar:{horizontal:!1,columnWidth:"20%",endingShape:"rounded"}},legend:{show:!1},dataLabels:{enabled:!1},colors:[e,"#E2ECFF"],series:[{name:"2020",data:[80,40,30,90,20,50,95]},{name:"2019",data:[20,60,70,10,80,50,5]}],xaxis:{categories:["S","M","T","W","T","F","S"],axisBorder:{show:!1},axisTicks:{show:!1},labels:{style:{colors:i},offsetY:-5}},yaxis:{show:!1,floating:!0},tooltip:{x:{show:!1}}};new ApexCharts(document.querySelector("#sales-chart"),f).render();var x={chart:{height:220,type:"radialBar",sparkline:{show:!0}},grid:{show:!1},plotOptions:{radialBar:{size:100,startAngle:-135,endAngle:135,offsetY:10,hollow:{size:"60%"},track:{strokeWidth:"90%",background:"#fff"},dataLabels:{value:{offsetY:-10,color:"#475f7b",fontSize:"26px"},name:{fontSize:"15px",color:"#596778",offsetY:30}}}},colors:[t],fill:{type:"gradient",gradient:{shade:"dark",type:"horizontal",shadeIntensity:.5,gradientToColors:[e],inverseColors:!0,opacityFrom:1,opacityTo:1,stops:[0,100]}},stroke:{dashArray:3},series:[78],labels:["Growth"]};new ApexCharts(document.querySelector("#growth-Chart"),x).render(),$(document).on("click",".widget-todo-item input",(function(){$(this).closest(".widget-todo-item").toggleClass("completed")})),dragula([document.getElementById("widget-todo-list")],{moves:function(e,o,t){return t.classList.contains("cursor-move")}})}));