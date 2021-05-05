$(window).on("load",(function(){var a="#5A8DEE",e="#39DA8A",o="#FF5B5C",i="#FDAC41",t="#475F7B",r="#dae1e7",l="#f3f3f3",s="#fff",n=[a,i,o,e,"#00CFDD",t],d=$("#line-chart"),p=(new Chart(d,{type:"line",options:{responsive:!0,maintainAspectRatio:!1,legend:{position:"top"},hover:{mode:"label"},scales:{xAxes:[{display:!0,gridLines:{color:r},scaleLabel:{display:!0}}],yAxes:[{display:!0,gridLines:{color:r},scaleLabel:{display:!0}}]},title:{display:!0,text:"World population per region (in millions)"}},data:{labels:[1500,1600,1700,1750,1800,1850,1900,1950,1999,2050],datasets:[{label:"Africa",data:[86,114,106,106,107,111,133,221,783,2478],borderColor:a,fill:!1},{data:[282,350,411,502,635,809,947,1402,3700,5267],label:"Asia",borderColor:e,fill:!1},{data:[168,170,178,190,203,276,408,547,675,734],label:"Europe",borderColor:o,fill:!1},{data:[40,20,10,16,24,38,74,167,508,784],label:"Latin America",borderColor:i,fill:!1},{data:[6,3,2,2,7,26,82,172,312,433],label:"North America",borderColor:t,fill:!1}]}}),$("#bar-chart")),c=(new Chart(p,{type:"bar",options:{elements:{rectangle:{borderWidth:2,borderSkipped:"left"}},responsive:!0,maintainAspectRatio:!1,responsiveAnimationDuration:500,legend:{display:!1},scales:{xAxes:[{display:!0,gridLines:{color:r},scaleLabel:{display:!0}}],yAxes:[{display:!0,gridLines:{color:r},scaleLabel:{display:!0},ticks:{stepSize:1e3}}]},title:{display:!0,text:"Predicted world population (millions) in 2050"}},data:{labels:["Africa","Asia","Europe","Latin America","North America"],datasets:[{label:"Population (millions)",data:[2478,5267,734,784,433],backgroundColor:n,borderColor:"transparent"}]}}),$("#horizontal-bar")),b=(new Chart(c,{type:"horizontalBar",options:{elements:{rectangle:{borderWidth:2,borderSkipped:"right",borderSkipped:"top"}},responsive:!0,maintainAspectRatio:!1,responsiveAnimationDuration:500,legend:{display:!1},scales:{xAxes:[{display:!0,gridLines:{color:r},scaleLabel:{display:!0}}],yAxes:[{display:!0,gridLines:{color:r},scaleLabel:{display:!0}}]},title:{display:!0,text:"Predicted world population (millions) in 2050"}},data:{labels:["Africa","Asia","Europe","Latin America","North America"],datasets:[{label:"Population (millions)",data:[2478,5267,734,784,433],backgroundColor:n,borderColor:"transparent"}]}}),$("#simple-pie-chart")),y=(new Chart(b,{type:"pie",options:{responsive:!0,maintainAspectRatio:!1,responsiveAnimationDuration:500,title:{display:!0,text:"Predicted world population (millions) in 2050"}},data:{labels:["Africa","Asia","Europe","Latin America","North America"],datasets:[{label:"My First dataset",data:[2478,5267,734,784,433],backgroundColor:n}]}}),$("#simple-doughnut-chart")),A=(new Chart(y,{type:"doughnut",options:{responsive:!0,maintainAspectRatio:!1,responsiveAnimationDuration:500,title:{display:!0,text:"Predicted world population (millions) in 2050"}},data:{labels:["Africa","Asia","Europe","Latin America","North America"],datasets:[{label:"My First dataset",data:[2478,5267,734,784,433],backgroundColor:n}]}}),$("#radar-chart")),u=(new Chart(A,{type:"radar",options:{responsive:!0,maintainAspectRatio:!1,responsiveAnimationDuration:500,legend:{position:"top"},tooltips:{callbacks:{label:function(a,e){return e.datasets[a.datasetIndex].label+": "+a.yLabel}}},title:{display:!0,text:"Distribution in % of world population"},scale:{reverse:!1,ticks:{beginAtZero:!0,stepSize:10}}},data:{labels:["Africa","Asia","Europe","Latin America","North America"],datasets:[{label:"1950",fill:!0,backgroundColor:"rgba(255,91,92,0.2)",borderColor:o,pointBorderColor:s,pointBackgroundColor:o,data:[8.77,55.61,21.69,6.62,6.82]},{label:"2050",fill:!0,backgroundColor:"rgba(255,91,92,0.2)",borderColor:o,pointBorderColor:s,pointBackgroundColor:o,data:[25.48,54.16,7.61,8.06,4.45]}]}}),$("#polar-chart")),g=(new Chart(u,{type:"polarArea",options:{responsive:!0,maintainAspectRatio:!1,responsiveAnimationDuration:500,legend:{position:"top"},title:{display:!0,text:"Predicted world population (millions) in 2050"},scale:{ticks:{beginAtZero:!0,stepSize:2e3},reverse:!1},animation:{animateRotate:!1}},data:{labels:["Africa","Asia","Europe","Latin America","North America"],datasets:[{label:"Population (millions)",backgroundColor:n,data:[2478,5267,734,784,433]}]}}),$("#bubble-chart")),C=(new Chart(g,{type:"bubble",options:{responsive:!0,maintainAspectRatio:!1,scales:{xAxes:[{display:!0,gridLines:{color:r},scaleLabel:{display:!0,labelString:"GDP (PPP)"}}],yAxes:[{display:!0,gridLines:{color:r},scaleLabel:{display:!0,labelString:"Happiness"},ticks:{stepSize:.5}}]},title:{display:!0,text:"Predicted world population (millions) in 2050"}},data:{animation:{duration:1e4},datasets:[{label:["China"],backgroundColor:"rgb(253, 172, 65,.2)",borderColor:i,data:[{x:21269017,y:5.245,r:15}]},{label:["Denmark"],backgroundColor:"rgba(57,218,138,0.2)",borderColor:e,data:[{x:258702,y:7.526,r:10}]},{label:["Germany"],backgroundColor:"rgba(0,0,0,0.2)",borderColor:"#000",data:[{x:3979083,y:6.994,r:15}]},{label:["Japan"],backgroundColor:"rgba(255,91,92,0.2)",borderColor:o,data:[{x:4931877,y:5.921,r:15}]}]}}),$("#scatter-chart"));new Chart(C,{type:"scatter",options:{responsive:!0,maintainAspectRatio:!1,responsiveAnimationDuration:800,title:{display:!1,text:"Chart.js Scatter Chart"},scales:{xAxes:[{position:"top",gridLines:{color:l,drawTicks:!1},scaleLabel:{display:!0,labelString:"x axis"}}],yAxes:[{position:"right",gridLines:{color:l,drawTicks:!1},scaleLabel:{display:!0,labelString:"y axis"}}]}},data:{datasets:[{label:"My First dataset",data:[{x:65,y:28},{x:59,y:48},{x:80,y:40},{x:81,y:19},{x:56,y:86},{x:55,y:27},{x:40,y:89}],backgroundColor:"#E6EAEE",borderColor:"transparent",pointBorderColor:"#E6EAEE",pointBackgroundColor:s,pointBorderWidth:2,pointHoverBorderWidth:2,pointRadius:4},{label:"My Second dataset",data:[{x:45,y:17},{x:25,y:62},{x:16,y:78},{x:36,y:88},{x:67,y:26},{x:18,y:48},{x:76,y:73}],backgroundColor:"rgba(90,141,238,.6)",borderColor:"transparent",pointBorderColor:"#5A8DEE",pointBackgroundColor:s,pointBorderWidth:2,pointHoverBorderWidth:2,pointRadius:4}]}})}));
