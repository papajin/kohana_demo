var gallery = {
    get_gallery: function(){
        var id = $(this).val();
        $('input:hidden[name="id"]').val(id);

        if ( !id ) {
            $('#title').val('');
            $('#thumb').val('');
            $('#edit_form img').attr('src', '');
            $('#caption').val('');
            $('#slides').html('');
            $('.alert-info').removeClass('invisible');
            $('#slides').parent().addClass('invisible');
        }
        else {
            _timer.up();
            $.post(
                '/admin/ajax/gallery_get/' + id,
                function(response) {
                    response = $.parseJSON(response);
                    if (response.res) {
                        $('#slides').parent().removeClass('invisible');
                        $('.alert-info').addClass('invisible');
                        gallery.fill_gallery(response.gallery);
                        // gallery.slides = response.slides;
                        gallery.make_slides(response.slides);
                    }
                    else
                        error(response.mes);
                }
            ).fail(function(jqXHR, textStatus, errorThrown) {
                error(errorThrown);
            })
                .always(_timer.over);
        }
    },
    fill_gallery: function( gal ) {
        $('#title').val( gal.title );
        $('#thumb').val( gal.thumb );
        $('#edit_form img').attr( 'src', gal.thumb );
        $('#caption').val( gal.caption );
    },
    toggle_slide: function () {
        var $ch_box = $(event.target),
            val = $ch_box.is(':checked') ? 1 : 0,
            id = $ch_box.parents('[data-id]').data('id');

        if ( id === '' ) {
            $ch_box.prop( 'checked', !val );
            warning(i18n.alert_save_slide);
            return;
        }

        _timer.up();
        $.post(
            '/admin/ajax/toggle_slide/' + id,
            {val: val},
            function(response) {
                response = $.parseJSON(response);
                if (!response.res) {
                    error(response.mes);
                    $ch_box.prop('checked', !val);
                }
            }
        ).fail(function(jqXHR, textStatus, errorThrown) {
            error(errorThrown);
            $ch_box.prop('checked', !val);
        }).always(_timer.over);
    },
    make_slide: function (slide) {
        if ( ! $.isPlainObject( slide ) ) return;

        var new_slide = $('#slide-template > div').clone(true, true);

        new_slide.attr({"data-id": slide.id});
        new_slide.find('[name="slide_thumb"]').val(slide.thumb);
        new_slide.find('h4').html(slide.title);
        new_slide.find('p.card-text > i').attr('data-original-title', slide.caption);
        new_slide.find('img').attr('src', slide.path);
        // new_slide.find('a.btn-warning').click(function(){event.preventDefault(); self.edit_slide(ind);});
        // new_slide.find('a.btn-danger').click(function(){event.preventDefault(); _sure('Вы действительно хотите удалить слайд?', function(){self.delete_slide(ind);});});
        if ( !!parseInt(slide.published) ) new_slide.find(':checkbox').attr('checked', true);
        new_slide.find(':checkbox').change( gallery.toggle_slide );
        new_slide.find('.btn-secondary').click( gallery.edit_slide );
        new_slide.find('.btn-danger').click( gallery.delete_slide );
        new_slide.find('[title]').tooltip();
        return new_slide;
    },
    make_slides: function(slides) {
        var box = $('#slides').html('');
        $.each(slides, function(indx, slide){
            box.append(gallery.make_slide(slide));
        });
        box.sortable("destroy").sortable({
            // group: 'slides',
            containerSelector: '#slides',
            itemSelector: 'div[data-id]',
            tolerance: 8,
            placeholder: '<div class="col-sm-4 col-md-3 col-lg-2 bg-danger text-xs-center" style="height: 100px"><h1 class="icon-down-big"></h1></div>',
            handle: '.card-img-top'
        });
    },
    delete_slide: function () {
        event.preventDefault();
        var $node = $(event.target).parents('[data-id]'),
            id = $node.data('id');
        
        if ( id === '' ) {
            $node.find('[data-original-title]').tooltip('dispose');
            $node.remove();
            return;
        }

        _timer.up();
        $.post(
            '/admin/ajax/slide_delete/' + id,
            function(response) {
                response = $.parseJSON(response);
                if (response.res) {
                    $node.find('[data-original-title]').tooltip('dispose');
                    $node.remove();
                }
                else
                    error(response.mes);
            }
        ).fail(function(jqXHR, textStatus, errorThrown) {
            error(errorThrown);
        }).always(_timer.over);
    },
    edit_slide: function (e) {
        event.preventDefault();
        var $slide = ( e.hasOwnProperty('target') ) ? $(e.target).parents('[data-id]') : e,
            slide_form = $('<form/>', {id:'slide_form'})
                .append($('<input/>',{type:'hidden',name:'id',value:$slide.data('id')}))
                .append($('<input/>',{type:'hidden',name:'gallery_id',value:$('#the_gallery option:selected').val()}))
                .append($('<input/>',{type:'hidden',name:'order',value:$('[data-id]').index( $slide.eq(0))}));

        var title = $('<div/>', {class:'form-group row'});
        $('<label/>', {for:'slide_title', class:'col-sm-2 form-control-label', text:i18n.title}).appendTo(title);
        $('<div/>', {class:'col-sm-10', html:'<input id="slide_title" name="title" class="form-control" value="'+$slide.find('h4').text()+'" />'}).appendTo(title);

        var desc = $('<div/>', {class:'form-group row'});
        $('<label/>', {for:'slide_caption', class:'col-sm-2 form-control-label', text:i18n.description}).appendTo(desc);
        $('<div/>', {class:'col-sm-10', html:'<textarea id="slide_caption" name="caption" rows=3 class="form-control">'+$slide.find('.card-text i').data('original-title')+'</textarea>'}).appendTo(desc);

        var thumb = $('<div/>', {class:'form-group row'}), t_src = $slide.find('[name="slide_thumb"]').val();
        $('<label/>', {for:'slide_thumb', class:'col-sm-2 form-control-label', title:i18n.slide_icon, text:i18n.icon}).appendTo(thumb);
        $('<div/>', {class:'col-sm-6', html:'<input id="slide_thumb" name="thumb" class="form-control" value="'+t_src+'" />'}).appendTo(thumb);
        $('<div/>', {class:'col-sm-4', html:'<img id="pic_thumb" class="img-fluid" src="'+t_src+'" />'}).appendTo(thumb);

        var path = $('<div/>', {class:'form-group row'}), src = $slide.find('.card-img-top').prop('src');
        $('<label/>', {for:'slide_path', class:'col-sm-2 form-control-label', title:i18n.image_path, text:i18n.image}).appendTo(path);
        $('<div/>', {class:'col-sm-6', html:'<input id="slide_path" name="path" class="form-control" value="'+src+'" />'}).appendTo(path);
        $('<div/>', {class:'col-sm-4', html:'<img id="pic_path" class="img-fluid" src="'+src+'" />'}).appendTo(path);

        var ch_box = $('<div/>', {class:'form-group row'});
        $('<div/>', {class:'col-sm-offset-2 col-sm-10', html:'<div class="checkbox"><label><input type="checkbox" id="published" name="published" '+(!!$slide.find(':checkbox:checked').length ? ' checked' : '')+'/> - ' + i18n.published +'</label></div>'}).appendTo(ch_box);

        title.appendTo(slide_form);
        desc.appendTo(slide_form);
        thumb.appendTo(slide_form);
        path.appendTo(slide_form);
        ch_box.appendTo(slide_form);

        $.each(slide_form.find(':text'), function(){ if( $(this).val() === 'null' ) {$(this).val('');}; });
        thumb.find(':text').change(function(){thumb.find('img').attr('src', $(this).val());});

        _modal.init({header:{html: _modal.defaults.header.html + '<h3>'+i18n.edit_slide+'</h3>'},
            body:{html:slide_form[0]},
            footer:{html:'<button class="btn" data-dismiss="modal">'+i18n.close+'</button> <button class="btn btn-primary" onclick="gallery.save_slide()">'+i18n.save+'</button>'}});
    },
    add_slide: function () {
        var g_id = $('#the_gallery option:selected').val();
        if ( g_id ) {
            var new_slide = gallery.make_slide({id:'', gallery_id: g_id, title:'new title', caption:'new caption', path:'/images/noimage.jpg', thumb:'', published: false});
            $('#slides').append( new_slide );
            gallery.edit_slide(new_slide);
            // new_slide.find( 'a.btn-secondary' ).click();     // Open new slide for editing
        }
        else {
            warning( i18n.alert_no_gallery );
        }
    },
    save_slide: function () {
        var rez = {},
            $form = $('#slide_form');

        $.each($('#slide_form').serializeArray(), function(i, field){
            rez[field.name] = field.value;
        });
        rez.published = $form.find('#published').is(':checked') ? 1 : 0;

        var init_id = rez.id;

        _timer.up();
        $.post(
            '/admin/ajax/save_slide',
            {slide: JSON.stringify(rez)},
            function(response) {
                response = $.parseJSON(response);
                if (response.res) {
                    rez.id = response.id;
                    $('[data-id="'+init_id+'"]').replaceWith(gallery.make_slide(rez));
                    _modal.modal.modal('hide');
                }
                else
                    error(response.mes);
            }
        ).fail(function(jqXHR, textStatus, errorThrown) {
            error(errorThrown);
        }).always(_timer.over);
    },
    save_order: function () {
        if ( ! $( '#the_gallery option:selected' ).val() )
            return;

        var data = $('#slides').sortable('serialize').get();
        var order = [];

        $.each(data, function(){
            $.each(this, function(key, val){
                if ( 'id' in val && val.id )
                    order[val.id] = key;
            });
        });

        _timer.up();
        $.post(
            '/admin/ajax/save_order',
            {param: JSON.stringify(order), model:'Slide'},
            function(response) {
                response = $.parseJSON(response);
                if (!response.res)
                    error(response.mes);
            }
        ).fail(function(jqXHR, textStatus, errorThrown) {
            error(errorThrown);
        })
            .always(_timer.over);
    },
    delete_gallery: function() {
        _timer.up();
        $.post(
            '/admin/ajax/gallery_delete/' + $('#the_gallery option:selected').val(),
            function(response) {
                response = $.parseJSON(response);
                if (response.res) {
                    $('#the_gallery option:selected').remove();
                    $('#the_gallery').change();
                }
                else
                    error(response.mes);
            }
        ).fail(function(jqXHR, textStatus, errorThrown) {
            error(errorThrown);
        }).always(_timer.over);
    },
    init: function () {
        // Toggle collapse button icon
        $('.collapse').on('hidden.bs.collapse', function () {
            $('a[href="#' + $(this).attr('id') + '"] > i').removeClass('icon-minus-squared-alt').addClass('icon-plus-squared-alt')
        }).on('shown.bs.collapse', function () {
            $('a[href="#' + $(this).attr('id') + '"] > i').removeClass('icon-plus-squared-alt').addClass('icon-minus-squared-alt')
        });


        $('#the_gallery').change( gallery.get_gallery );            // Handle gallery selector manipulations
        $('button[name="slide_new"]').click( gallery.add_slide );
        $('button[name="save_order"]').click( gallery.save_order );
        $('button[name="gal_new"]').click(function(){
            $('#the_gallery').children().removeAttr("selected");
            $('#the_gallery').change();
        });
        $('button[name="gal_delete"]').click(function(){
            _sure(i18n.confirm_gallery_delete + $('#the_gallery option:selected').text()+'?', gallery.delete_gallery);
        });
    }
};
gallery.init();
