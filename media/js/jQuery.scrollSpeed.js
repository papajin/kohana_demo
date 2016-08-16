// Custom scrolling speed with jQuery
// Source: github.com/ByNathan/jQuery.scrollSpeed
// Version: 1.0.2
(function($){jQuery.scrollSpeed=function(b,c,d){var f=$(document),$window=$(window),$body=$('html, body'),option=d||'default',root=0,scroll=false,scrollY,scrollX,view;if(window.navigator.msPointerEnabled)return false;$window.on('mousewheel DOMMouseScroll',function(e){var a=e.originalEvent.wheelDeltaY,detail=e.originalEvent.detail;scrollY=f.height()>$window.height();scrollX=f.width()>$window.width();scroll=true;if(scrollY){view=$window.height();if(a<0||detail>0)root=(root+view)>=f.height()?root:root+=b;if(a>0||detail<0)root=root<=0?0:root-=b;$body.stop().animate({scrollTop:root},c,option,function(){scroll=false})}if(scrollX){view=$window.width();if(a<0||detail>0)root=(root+view)>=f.width()?root:root+=b;if(a>0||detail<0)root=root<=0?0:root-=b;$body.stop().animate({scrollLeft:root},c,option,function(){scroll=false})}return false}).on('scroll',function(){if(scrollY&&!scroll)root=$window.scrollTop();if(scrollX&&!scroll)root=$window.scrollLeft()}).on('resize',function(){if(scrollY&&!scroll)view=$window.height();if(scrollX&&!scroll)view=$window.width()})};jQuery.easing.default=function(x,t,b,c,d){return-c*((t=t/d-1)*t*t*t-1)+b}})(jQuery);