$((function(){"use strict";var t=$("#tour");t.length&&t.on("click",(function(){var t,e,a,n=new Shepherd.Tour({defaultStepOptions:{classes:"shadow-md",scrollTo:!1,cancelIcon:{enabled:!0}},useModalOverlay:!0});(t=n,e="btn btn-sm btn-outline-primary",a="btn btn-sm btn-primary btn-next",t.addStep({title:"Navbar",text:"This is your navbar",attachTo:{element:".navbar",on:"bottom"},buttons:[{action:t.cancel,classes:e,text:"Skip"},{text:"Next",classes:a,action:t.next}]}),t.addStep({title:"Card",text:"This is a card",attachTo:{element:"#basic-tour .card",on:"top"},buttons:[{text:"Skip",classes:e,action:t.cancel},{text:"Back",classes:e,action:t.back},{text:"Next",classes:a,action:t.next}]}),t.addStep({title:"Footer",text:"This is the footer",attachTo:{element:".footer",on:"top"},buttons:[{text:"Back",classes:e,action:t.back},{text:"Finish",classes:a,action:t.cancel}]}),t).start()}))}));
