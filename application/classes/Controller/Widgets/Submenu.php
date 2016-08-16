<?php defined('SYSPATH') or die('No direct script access.');
/*
 * Submenu widget for admin pages
 */
class Controller_Widgets_Submenu extends Controller_Widgets {

    public $template = 'admin/w/';    // Path to widgets folder to be concatenated with file name
    
    public function before()
    {
        $this->template .= ( $this->request->param( 'param' ) )
                ?  $this->request->param( 'param' )
                : strtolower( $this->request->initial()->controller() ) . '_' . $this->request->initial()->action();
        parent::before();
        
        $this->cache_lifetime = 0;
    }

    public function action_index()
    {
        $this->template->instance = ORM::factory( $this->request->initial()->controller(), $this->request->initial()->param( 'id' ) );
    }

}