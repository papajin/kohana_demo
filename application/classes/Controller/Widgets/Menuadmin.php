<?php defined('SYSPATH') or die('No direct script access.');
/*
 * Виджет "Меню админа"
 */
class Controller_Widgets_Menuadmin extends Controller_Widgets {

    public $template = 'admin/w/w_menuadmin';    // Шаблон виждета
    
    public function action_index()
    {
        $this->cache_lifetime = 0;
        $this->template->select = strtolower( Request::initial()->controller() );

        $this->template->menu = [
            __( 'Home' )    => [ 'main' ],
            __( 'Content' ) => [ __( 'Pages' )      => 'page',
                                 __( 'Articles' )   => 'article',
                                 __( 'Menu' )       => 'menu' ],
            __( 'Galleries' ) => [ 'gallery' ]
        ];
    }
}