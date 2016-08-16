<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Widget for social icons Pluso
 *
 * @author Игорь
 */
class Controller_Front_WPHelper extends Controller {
    
    private $widgets_list = [ 'Header', 'Footer', 'Subfooter', 'Topper' ]; // [ 'Header', 'Footer', 'Pluso', 'YandexVer' ]
    private $path = 'content/assets/uk/blog_widgets_cache.json';
    
    public function action_tpltBlox()
    {
        $widgets = [];
        
        foreach ( $this->widgets_list as $widget )
        {
            $widgets[ strtolower( $widget ) ] = htmlentities ( Widget::load( $widget ) );
        }
        
        file_put_contents( APPPATH . $this->path, json_encode( $widgets ) );
    }
    
    /**
     * Override of the parent method.
     * Language cannot be different from what we have files for in i18n folder.
     * If param is OK, then reset language to the passed param and change the folder
     * for widgets store.
     */
    protected function set_language() {
        
        $lang = $this->request->param( 'id' );

        // Get list of language files and strip path and .php ending.
        $lang_arr = array_map( 
                        function( $str ){ return basename( $str, '.php' ); }, 
                        Kohana::list_files( 'i18n', [ APPPATH ] )
                    );
                        
        if ( in_array( $lang, $lang_arr ) ) {
            I18n::lang( $lang );
            $this->path = str_replace( '/uk/', DIRECTORY_SEPARATOR . $lang . DIRECTORY_SEPARATOR, $this->path );
        }
    }
}