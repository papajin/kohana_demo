<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Footer Controller
 *
 * @author Игорь
 */
class Controller_Widgets_Footer extends Controller_Widgets {
    
    public $template = 'front/tplt/front_tplt_footer';    // Шаблон виждета
    
    public function action_index() {
        $menu_source = Kohana::find_file( 'content/assets', 'footer_menu', 'json' ); 
        $this->template->footer_menu = ( $menu_source )
                                            ? json_decode( file_get_contents( $menu_source ) ) 
                                            : NULL ;
    }
}