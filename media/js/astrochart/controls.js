/* 
 * Interactivity manager of the astrochart.
 */
(function(){
    // Links are buttons...
    $('#btn-toolbar a').click(function(event){event.preventDefault();});
    
    $(document).ready(function(){
        // Some "responsive" settings.
        if ( $( window ).width() < 1119 & $( window ).width() > 767)
            _default.radius = 250;
        else if ( $( window ).width() < 768)
            _default.radius = $( window ).width() / 2 - 60;
        
        // jQuery selectors for corresponding buttons.
        var target = {elem_: '[class ^= "elem_"]', aspect: '.major, .dot, .minor'};
        var _info_window = info_window();
        
        // Build chart.
        Chart().run();
        
        // Buttons click binding.
        $('button[data-toggle]').click(function(){
            if ($(this).is('.active')) {
                $('[class^=' + $(this).attr('id') + ']').fadeIn();
                if ( $(this).is('[data-target]') ) {
                    $(target[$(this).data('target')]).fadeIn();
                    $(':checkbox[name="'+$(this).data('target')+'"]').prop('checked', true);
                }
            }
            else {
                $('[class^=' + $(this).attr('id') + ']').fadeOut();
                if ( $(this).is('[data-target]') ) {
                    $(target[$(this).data('target')]).fadeOut();
                    $(':checkbox[name="'+$(this).data('target')+'"]').prop('checked', false);
                }
            }
        });

        // Checkboxs binding.
        $('#btn-toolbar :checkbox').change(function(){
            var needle = $(this).val();
            needle = $(this).is('[name = "elem_"]')
                    ? '.elem_' + needle
                    : '.' + needle;
            
            $(this).is(':checked')
                ? $(needle).fadeIn()
                : $(needle).fadeOut();
        });
        
        $('.btn-group-vertical a').on('mouseover mouseout click', 
            function (e) {
                e = typeof (e) !== 'undefined' ? e : event;
                var $link = $(e.target);
                var targs = $.parseJSON($link.attr('href'));
                var clicks = !!$link.data('clicks');
                
                if ( e.type === 'click' ) {
                    if ( $link.is( '.active' )) {
                        $link.removeClass('active');
                    }
                    else {
                        $('#togglers .active').removeClass('active');
                        $link.addClass('active');
                    }
                    $('[class ^= "hl_"]:not(".hidden")').addClass('hidden').removeAttr('data-on');
                    $('.btn-group-vertical a[data-clicks]').data('clicks', false);
                    clicks 
                        ? $.each(targs, function(){
                                $('.hl_'+this).addClass('hidden').removeAttr('data-on');
                            })
                        : $.each(targs, function(){
                                $('.hl_'+this).removeClass('hidden').attr('data-on', true);
                            });

                    $link.data('clicks', !clicks);
                }
                else if ( !clicks ) {
                    e.type === 'mouseover'
                        ? $.each(targs, function(){
                            $('.hl_'+this).removeClass('hidden');
                         })
                        : $.each(targs, function(){
                            if ( !$('.hl_'+this).is('[data-on]') )
                                $('.hl_'+this).addClass('hidden');
                         });
                }
        });
        
        // Info window upon button click.
        $('.line, .aspect, .sign')
            .click(function() {
                _info_window.init($(this));
        })  .tooltip({
                title: function(){
                    return $(this).attr('title') ? $(this).attr('title') : i18n[$(this).attr('id').capitalize()];
            }
        });
    });
})();

