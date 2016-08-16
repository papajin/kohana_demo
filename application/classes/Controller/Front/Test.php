<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Test controller
 *
 * @author Игорь
 */
class Controller_Front_Test extends Controller_Front {
    public function before()
    {
        parent::before();
        
        $this->_page = ORM::factory('Page')
                                        ->where('alias', '=', 'index')
                                        ->find();
    }
    
    public function action_index ()
    {
        $b = 5 * 30 . 7;

        $this->content[] = sprintf( '<h3>Var now is "%s"</h3>', $b );
    }

    public function action_check()
    {
        $res = ORM::factory( 'Taxonomy' )
                ->where( 'taxonomy', '=', 'post_tag')->find_all();
        foreach ( $res as $tax )
            $this->content[] = sprintf( '"%s" => "%s",', $tax->term->slug, $tax->term->name ) . PHP_EOL;
    }
}