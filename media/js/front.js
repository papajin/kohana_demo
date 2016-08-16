(function($) {
    $("body").removeClass("no-trans");
    // jQuery.scrollSpeed(100, 800);
    $(function () {
        $('[data-toggle="tooltip"]').tooltip();
      });
    //Show dropdown on hover only for desktop devices
    //-----------------------------------------------
    var delay=0, setTimeoutConst;
    if ( ( Modernizr.mq( 'only all and (min-width: 768px)' ) && !Modernizr.touch ) || $( "html.ie8" ).length > 0 ) {
            $( '.main-navigation .navbar-nav>li.dropdown, .main-navigation li.dropdown>ul>li.dropdown' ).hover(
            function() {
                    var $this = $( this );
                    setTimeoutConst = setTimeout( function(){
                            $this.addClass( 'open' ).slideDown();
                            $this.find( '.dropdown-toggle' ).addClass( 'disabled' );
                    }, delay );

            },	function(){
                    clearTimeout( setTimeoutConst );
                    $( this ).removeClass( 'open' );
                    $( this ).find( '.dropdown-toggle' ).removeClass( 'disabled' );
            });
    };

    //Show dropdown on click only for mobile devices
    //-----------------------------------------------
    if ( Modernizr.mq( 'only all and (max-width: 767px)' ) || Modernizr.touch ) {
            $( '.main-navigation [data-toggle=dropdown], .header-top [data-toggle=dropdown]' ).on( 'click', function( event ) {
            // Avoid following the href location when clicking
            event.preventDefault();
            // Avoid having the menu to close when clicking
            event.stopPropagation();
            // close all the siblings
            $( this ).parent().siblings().removeClass( 'open' );
            // close all the submenus of siblings
            $( this ).parent().siblings().find( '[data-toggle=dropdown]' ).parent().removeClass( 'open' );
            // opening the one you clicked on
            $( this ).parent().toggleClass( 'open' );
            } );
    };

    /**
     * Set class active for li elements, containing actual page link.
     * This is mainly for top menu, but could be useful somewhere else.
     */
    var uri = window.location.href
        .split(window.location.host)[1]
        .split('#')[0]; // if id link

    $('a[href = "'+uri+'"], a[href = "'+uri.slice(0, -1)+'"]').each(function(){
        $(this).parents('li').addClass('active');
    });

    // Scroll totop
    // &
    // Fixed header
    //-----------------------------------------------
    var	headerTopHeight = $( ".header-top" ).outerHeight(),
    headerHeight = $( "header.header.fixed" ).outerHeight();
    $( window ).scroll( function() {
        if ( $( ".header.fixed" ).length > 0 ) {
            if( $( this ).scrollTop() > headerTopHeight+headerHeight && $( window ).width() > 767 ) {
                $( "body" ).addClass( "fixed-header-on" );
                $( ".header.fixed" ).addClass( 'animated object-visible fadeInDown' );  // Take into account WP admin toolbar
                if ( $('#wpadminbar').length ) $( ".header.fixed" ).css( "top", $('#wpadminbar').outerHeight() + "px" );
                if ( ! $( ".header.transparent" ).length > 0 ) {
                    if ( $( ".banner:not(.header-top)" ).length > 0 ) {
                            $( ".banner" ).css( "marginTop", headerHeight + "px" );
                    } else if ( $( ".page-intro" ).length > 0 ) {
                            $( ".page-intro" ).css( "marginTop", headerHeight + "px" );
                    } else if ( $( ".page-top" ).length > 0 ) {
                            $( ".page-top" ).css( "marginTop", headerHeight + "px" );
                    } else {
                            $( "section.main-container" ).css( "marginTop", headerHeight + "px" );
                    }
                }
            } else {
                $( "body" ).removeClass( "fixed-header-on" );
                $( "section.main-container" ).css( "marginTop", 0 + "px" );
                $( ".banner" ).css( "marginTop", 0 + "px" );
                $( ".page-intro" ).css( "marginTop", 0 + "px" );
                $( ".page-top" ).css( "marginTop", 0 + "px" );
                $( ".header.fixed" ).removeClass( 'animated object-visible fadeInDown' );
            }
        };

        if( $( this ).scrollTop() !== 0 ) {
                $( ".scrollToTop" ).fadeIn();
        } else {
                $( ".scrollToTop" ).fadeOut();
        }
    } );

    $( ".scrollToTop" ).click( function() {
            $( "body,html" ).animate( { scrollTop: 0 },800 );
    } );


    $( document ).ready( function() {
        if ( $.isFunction ( $.fn.magnificPopup ) ) {
            $('.ajax-popup-link').magnificPopup( {
                type: 'ajax'
            } );
            $('.gallery').each( function() {
                $(this).magnificPopup( {
                    delegate: 'a',
                    type: 'image',
                    gallery: {
                        enabled: true
                    },
                    image: {
                        titleSrc: 'title'
                    }
                } )
            } );
        }
        if ( $.isFunction ( $.fn.masonry ) )
            $('.masonry-grid').masonry({ itemSelector: '.masonry-grid-item' });

        // Animations
        //-----------------------------------------------
        if (($("[data-animation-effect]").length>0) && !Modernizr.touch) {
            $("[data-animation-effect]").each(function() {
                var item = $(this),
                    animationEffect = item.attr("data-animation-effect");

                if(Modernizr.mq('only all and (min-width: 768px)') && Modernizr.csstransitions) {
                    item.appear(function() {
                        if(item.attr("data-effect-delay")) item.css("effect-delay", delay + "ms");
                        setTimeout(function() {
                            item.addClass('animated object-visible ' + animationEffect);

                        }, item.attr("data-effect-delay"));
                    }, {accX: 0, accY: -130});
                } else {
                    item.addClass('object-visible');
                }
            });
        };
    });

    $('#fb_submit_link').click(function(){
        event.preventDefault();
        var iframe = $('.fb-like iframe').contents();
        console.log( iframe );//.getElementById('u_0_1')
    });

})(this.jQuery);