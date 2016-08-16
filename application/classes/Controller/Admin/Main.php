<?php defined('SYSPATH') or die('No direct script access.');
/*
 * Admin Controller
 */
class Controller_Admin_Main extends Controller_Admin {

    public function action_index() 
    {
        $this->content[] = View::factory( 'admin/a_main', [
            'pages' => json_encode( DB::select( 'id', 'alias', 'title' )
                                        ->from( 'pages' )
                                        ->execute()
                                        ->as_array() ),
            'articles' => json_encode( DB::select( 'id', 'alias', 'title', 'widget' )
                                        ->from( 'articles' )
                                        ->execute()
                                        ->as_array() )
        ] );
        
        // Вывод в шаблон
        $this->page_title = sprintf( '%s:: %s', __( 'Administration' ), __( 'Home' ) );
        $this->scripts[] = 'typeahead';
        $this->scripts[] = 'main';
    }
}