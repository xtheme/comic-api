$(document).ready((function(){new Swiper(".swiper-default"),new Swiper(".swiper-navigations",{navigation:{nextEl:".swiper-button-next",prevEl:".swiper-button-prev"}}),new Swiper(".swiper-paginations",{pagination:{el:".swiper-pagination"}}),new Swiper(".swiper-progress",{pagination:{el:".swiper-pagination",type:"progressbar"},navigation:{nextEl:".swiper-button-next",prevEl:".swiper-button-prev"}}),new Swiper(".swiper-multiple",{slidesPerView:3,spaceBetween:30,pagination:{el:".swiper-pagination",clickable:!0}}),new Swiper(".swiper-multi-row",{slidesPerView:3,slidesPerColumn:2,spaceBetween:30,slidesPerColumnFill:"row",pagination:{el:".swiper-pagination",clickable:!0}}),new Swiper(".swiper-centered-slides",{slidesPerView:"auto",centeredSlides:!0,spaceBetween:30,navigation:{nextEl:".swiper-button-next",prevEl:".swiper-button-prev"}}),new Swiper(".swiper-centered-slides-2",{slidesPerView:"auto",centeredSlides:!0,spaceBetween:30}),new Swiper(".swiper-fade-effect",{spaceBetween:30,effect:"fade",pagination:{el:".swiper-pagination",clickable:!0},navigation:{nextEl:".swiper-button-next",prevEl:".swiper-button-prev"}}),new Swiper(".swiper-cube-effect",{effect:"cube",grabCursor:!0,cubeEffect:{shadow:!0,slideShadows:!0,shadowOffset:20,shadowScale:.94},pagination:{el:".swiper-pagination"}}),new Swiper(".swiper-coverflow",{effect:"coverflow",grabCursor:!0,centeredSlides:!0,slidesPerView:"auto",coverflowEffect:{rotate:50,stretch:0,depth:100,modifier:1,slideShadows:!0},pagination:{el:".swiper-pagination"}}),new Swiper(".swiper-autoplay",{spaceBetween:30,centeredSlides:!0,autoplay:{delay:2500,disableOnInteraction:!1},pagination:{el:".swiper-pagination",clickable:!0},navigation:{nextEl:".swiper-button-next",prevEl:".swiper-button-prev"}});var e=new Swiper(".gallery-thumbs",{spaceBetween:10,slidesPerView:4,freeMode:!0,watchSlidesVisibility:!0,watchSlidesProgress:!0}),i=(new Swiper(".gallery-top",{spaceBetween:10,navigation:{nextEl:".swiper-button-next",prevEl:".swiper-button-prev"},thumbs:{swiper:e}}),new Swiper(".swiper-parallax",{speed:600,parallax:!0,pagination:{el:".swiper-pagination",clickable:!0},navigation:{nextEl:".swiper-button-next",prevEl:".swiper-button-prev"}}),new Swiper(".swiper-lazy-loading",{lazy:!0,pagination:{el:".swiper-pagination",clickable:!0},navigation:{nextEl:".swiper-button-next",prevEl:".swiper-button-prev"}}),new Swiper(".swiper-responsive-breakpoints",{slidesPerView:5,spaceBetween:50,pagination:{el:".swiper-pagination",clickable:!0},breakpoints:{1200:{slidesPerView:5,spaceBetween:50},1024:{slidesPerView:4,spaceBetween:40},768:{slidesPerView:3,spaceBetween:30},640:{slidesPerView:2,spaceBetween:20},320:{slidesPerView:1,spaceBetween:10}}}),600),n=1,t=new Swiper(".swiper-virtual",{slidesPerView:3,centeredSlides:!0,spaceBetween:30,pagination:{el:".swiper-pagination",type:"fraction"},navigation:{nextEl:".swiper-button-next",prevEl:".swiper-button-prev"},virtual:{slides:function(){for(var e=[],i=0;i<600;i+=1)e.push("Slide "+(i+1));return e}()}});$(".slide-1").on("click",(function(e){e.preventDefault(),t.slideTo(0,0)})),$(".slide-250").on("click",(function(e){e.preventDefault(),t.slideTo(249,0)})),$(".slide-500").on("click",(function(e){e.preventDefault(),t.slideTo(499,0)})),$(".prepend-2-slides").on("click",(function(e){e.preventDefault(),t.virtual.prependSlide(["Slide "+--n,"Slide "+--n])})),$(".append-slide").on("click",(function(e){e.preventDefault(),t.virtual.appendSlide("Slide "+ ++i)}))}));