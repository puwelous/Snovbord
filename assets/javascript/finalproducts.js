            $(document).ready(function(){

                if (document.addEventListener) {
                    // IE9, Chrome, Safari, Opera
                    document.addEventListener("mousewheel", MouseWheelHandler, false);
                    // Firefox
                    document.addEventListener("DOMMouseScroll", MouseWheelHandler, false);
                }
                // IE 6/7/8
                else document.attachEvent("onmousewheel", MouseWheelHandler);

                var defaultAnimationStep = 100;
                //var animationStep = 100;

                function MouseWheelHandler(e) {

                    // cross-browser wheel delta
                    var e = window.event || e; // old IE support
                    //var delta = Math.max(-1, Math.min(1, (e.wheelDelta || -e.detail)));
                    //alert('e.wheelDelta = ' + e.wheelDelta + '-e.detail = ' + (-e.detail));
                    var delta = (e.wheelDelta || -e.detail);

                    //$(".gallery_row_wrapper").style.width = Math.max(50, Math.min(800, myimage.width + (30 * delta))) + "px";
                    //                    var x = $(".gallery_row_wrapper").offset().left;
                    //                    x = x + delta;
                    //                    $(".gallery_row_wrapper").css({left:x});
                    
                    // check actual position of a row wrapper, in case of attempt to move left
                    // further than gallery_wrapper' most left position, move it to the beginning
                    if( $(".gallery_row_wrapper").offset().left + delta > 150 ){
                        //alert( $(".gallery_row_wrapper").offset().left + ' ' + delta);
                        //return false;
                        //delta = -$(".gallery_row_wrapper").offset().left ;
                        delta = 0 ;
                    }else if( $(".gallery_row_wrapper").width() + $(".gallery_row_wrapper").offset().left + delta + 2 <  $( window ).width() ){
                        //delta = - 1 * ($(".gallery_row_wrapper").width() + $(".gallery_row_wrapper").offset().left - $( window ).width()) ;
                        //alert( $(".gallery_row_wrapper").width() + ", " + $(".gallery_row_wrapper").offset().left);
                        return false;
                    }
                   
                    if( $('.gallery_row_wrapper').is(':animated') ) {
                        // animation is already running, ingore mouse wheel event
                        //animationStep -= 50;
                    }else{
                        // animate wrapper
                        $(".gallery_row_wrapper").animate({
                            left: ("+=" + delta)
                        }, defaultAnimationStep);
                        //animationStep = defaultAnimationStep;
                    }
                    
                    return false;
                }
  
            });
