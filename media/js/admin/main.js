(function ()
{
    var self = this;

    self.subject = '';
    self.substringMatcher = function( name ) {
            return function findMatches( q, cb ) {
                var source, matches, substringRegex;

                // source array
                source = self[ self.subject ].map( function( obj ){
                    return obj[ name ];
                });

                // an array that will be populated with substring matches
                matches = [];

                // regex used to determine if a string contains the substring `q`
                substrRegex = new RegExp( q, 'i' );

                // iterate through the pool of strings and for any string that
                // contains the substring `q`, add it to the `matches` array
                $.each( source, function( i, str ) {
                    if ( substrRegex.test( str ) ) {
                        matches.push( str );
                    }
                });

                cb( matches );
            };
        };

    /**
     * Callback function on typeahead value selected. Presents result in modal window.
     * @param {object} object of page or article
     * @returns {void}
     */
    self.modal_call = function( object ){

        var view_link = ( self.subject === 'pages' )
            ? ( object.alias === 'index') ? '' : object.alias + '.html'
            : self.subject + '/' + object.alias + '.html';

        var widget_link = ( self.subject === 'pages' )
            ? ''
            : '<a href="#" title="' + i18n.add_article_widget + '" class="btn btn-secondary icon-attention-circled text-info" onclick="self.w_article(1)" data-id="' + object.id + '"></a>';

        // html for modal body. Delete cache binding on click event for modal object.
        var mb_html = '<a class="btn btn-link btn-xs" title="' + i18n.edit + '" href="/admin/'+self.subject.substring(0, self.subject.length - 1)+'/edit/'+object.id+'">'+object.title+'</a> '
            + '<div class="btn-group btn-group-sm pull-xs-right"><a href="/'+view_link+'" title="' + i18n.view + '" class="btn btn-secondary"><i class="icon-search"></i></a> '
            + widget_link
            + '<a href="#" title="' + i18n.delete_cache + '" class="btn btn-secondary icon-arrows-cw" data-click="_delete_cache" data-param="' + object.alias + '"></a></div>';

        _modal.init({body:{html:mb_html}});
    };

    self.delete_cache = function(href){
        _timer.up();
        $.ajax({
            url: '/admin/ajax/delete_all_cache',
            data: { param: href },
            type: "POST"
        }).done(function(response) {
            response = $.parseJSON(response);
            if(!response.res)
                error(response.mes);
        })
            .fail(function(jqXHR, textStatus, errorThrown) {
                error(errorThrown);
            })
            .always(_timer.over);
    };

    self.generate_map = function(){
        _timer.up();
        $.ajax( {
            url: '/admin/ajax/generate_map',
            type: "POST"
        } ).done( function( response ) {
            response = $.parseJSON( response );
            if( !response.res )
                error( response.mes );
        } )
            .fail( function( jqXHR, textStatus, errorThrown ) {
                error( errorThrown );
            })
            .always( _timer.over );
    };

    /**
     * Add / remove article widget on home page.
     * @param w 0 - to removes; 1 - to add.
     */
    self.w_article = function (w) {
        var id = $( event.target ).data('id');

        _timer.up();
        $.ajax( {
            url: '/admin/ajax/article_widget/' + id,
            data: { w: w },
            type: "POST"
        } ).done( function( response ) {
            response = $.parseJSON( response );
            if( !response.res )
                error( response.mes );
            else {
                $.map( self.articles, function( obj, index ) {
                    if( obj.id == id ) { obj.widget = w; return; }
                });
                self.init_widget_articles();
            }
        } )
            .fail( function( jqXHR, textStatus, errorThrown ) {
                error( errorThrown );
            } )
            .always( _timer.over );
    };
    
    self.init_widget_articles = function () {
        var w_articles = self.articles.filter( function(el){ return parseInt(el.widget) } ),
            $w_articles_node;

        if ( ! $('#w_articles').length ) {
            $( '<h4 class="m-t-2" />' ).text( i18n.widget_articles ).append( $('<i onmouseover="(function(e){$(e.target).tooltip(\'toggle\')})(event)" class="icon-info m-l-1 text-info" />').attr( 'title', i18n.add_widget_articles_title )[0] ).appendTo( $( "#controls" ) );
            $( '<div id="w_articles" />' ).appendTo( $( "#controls" ) );
            $( '<ul class="list-group" />').appendTo( $( "#w_articles" ) );
        }

        $w_articles_node = $('#w_articles > ul');
        
        $w_articles_node.html('');

        $.each( w_articles, function(){
            $( '<li class="list-group-item"><i class="icon-cancel btn btn-sm pull-xs-right" data-id="' + this.id + '" onclick="self.w_article(0)"></i>' + this.title + '</li>' ).appendTo( $w_articles_node );
        });
    };

    self.pages    = $.parseJSON( $('#pages_source').val() );
    self.articles = $.parseJSON( $('#articles_source').val() );

    $( '#search_toggle > label' ).click( function() {
        self.subject = $( this ).find( 'input' ).attr( 'id' ).replace( 'option_', '' );

        $( '#fast_search input.typeahead' ).each( function() {
            var $input = $( this ),
                name = $input.data( 'name' );

            $input.typeahead( 'destroy' ).typeahead(
                {
                    hint: true,
                    highlight: true,
                    minLength: 1
                },
                {
                    name: self.subject + '_' + name,
                    source: self.substringMatcher( name )
                }
            );
        } )
    } );

    $( '.typeahead' ).bind( 'typeahead:select', function( ev, suggestion ) {

        _timer.up();

        $.ajax({
            url: '/admin/ajax/get_material/' + self.subject,
            data: { field: $(ev.target).data('name'), value: suggestion },
            type: "POST"
        }).done( function( response ) {
            self.modal_call( $.parseJSON( response ) );
        }).fail( function(jqXHR, textStatus, errorThrown ) {
                error(errorThrown);
        }).always( _timer.over );
    } );

    $('.delete_cache a').click( function( event ) {
        event.preventDefault();
        var href = $( this ).attr( 'href' ).replace( '#', '' );
        // console.log(href);
        self.delete_cache( href );
    });

    $('#generate_map').click( function( event ){
        event.preventDefault();
        self.generate_map();
    } );

    self.init_widget_articles();

})();

/**
 * Handling of delete cache button click.
 * Cache uses uri as id. Model and alias should be enough to make uri.
 */
var _delete_cache = function(){
    _timer.up();
    $.ajax( {
        url: '/admin/ajax/delete_cache',
        data: {
            model: $( '#search_toggle label.active' ).attr( 'for' ).replace( 'option_', '' ),
            alias: $( event.target ).data('param')
        },
        type: "POST"
    } ).done( function( response ) {
        response = $.parseJSON( response );
        if( !response.res )
            error( response.mes );
    } )
        .fail( function( jqXHR, textStatus, errorThrown ) {
            error( errorThrown );
        } )
        .always( _timer.over );
};