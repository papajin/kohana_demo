/**
 *
 * Pages list
 */

var page = {
    sortable_table: $( ".sortable" ).sortable({
                        containerSelector: 'tbody',
                        itemSelector: 'tr',
                        placeholder: '<i class="icon-right text-danger"></i>',
                        handle: 'tr td:first-child'
                    }),
    run_ajax: function(url, data) {
        _timer.up();
        $.ajax( {
            url: url,
            data: data,
            type: "POST"
        } ).done( function( response ) {
            response = $.parseJSON( response );
            if( ! response.res )
                error( response.mes );
        })
            .fail( function( jqXHR, textStatus, errorThrown ) {
                error( errorThrown );
            })
            .always( _timer.over );
    },
    delete_cache: function(){
        event.preventDefault();

        // Get alias from previous cell
        var alias = $(event.currentTarget).parents('td').prev().text();
        page.run_ajax('/admin/ajax/delete_cache', { alias: alias })
    },
    extend: function(){}, // extra functions to supplement init
    init: function () {
        $('.delete_cache').click(this.delete_cache);
        $( 'button[name="save"]' ).click( function(){
            var data = page.sortable_table.sortable('serialize').get();
            var order = {};

            $.each( data, function(){
                $.each( this, function( key, val ){
                    order[ val.id ] = key;
                });
            } );
            page.run_ajax('/admin/ajax/save_order', {param: JSON.stringify( order )});
        });
        this.extend();
    }
};
$(document).ready(function(){page.init();});

