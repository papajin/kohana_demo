<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Header Controller
 *
 * @author Игорь
 */
class Controller_Widgets_Header extends Controller_Widgets {
    
    public $template = 'front/tplt/front_tplt_header';    // widget template
    
    public function action_index()
    {
        $menu_path = Kohana::find_file( 'content/assets', 'menu', 'json' );
        $this->template->menu = ( $menu_path ) 
                ? json_decode( file_get_contents( $menu_path ) )
                : NULL;
    }
}