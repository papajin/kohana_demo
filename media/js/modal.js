(function(){
    var modal = $('<div class="modal fade" id="myModal">\n\
                    <div class="modal-dialog">\n\
                        <div class="modal-content">\n\
                            <div class="modal-body">\n\
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>\n\
                                <button type="button" class="toggle_zoom btn-white"><span class="icon-zoom-in"></span></button>\n\
                                <img src="#" style="width:100%;height:100%;" />\n\
                            </div>\n\
                        </div>\n\
                    </div>\n\
                </div>');
    $('body').append(modal);
    var modal_img = modal.find('img');
    
    $('a[href^="#"] > img').click(function(){
        var src = $(this).attr('src');
        modal_img.attr('src', src);
        modal.modal('show');
    });

    $('.overlay-container a').click(function(){
        var src = $(this).siblings('img').attr('src');
        modal_img.attr('src', src);
        modal.modal('show');
    });

    $('.entry-content a > img, .storycontent a > img, .blogpost-content a > img').click(function(event){
        event.preventDefault();
        
        var src = $(this).parent().attr('href');
        modal_img.attr('src', src);
        modal.modal('show');
    });
    
    $('.toggle_zoom').click(function(){
        var $btn = $(this);
        var $icon = $btn.find('[class ^= "icon-"]');
        if($icon.hasClass('icon-zoom-in'))
        {
            $btn.parents('.modal-dialog').addClass('modal-lg');
            $icon.removeClass('icon-zoom-in').addClass('icon-zoom-out');
        }
        else
        {
            $btn.parents('.modal-dialog').removeClass('modal-lg');
            $icon.removeClass('icon-zoom-out').addClass('icon-zoom-in');
        }
    });
})();

