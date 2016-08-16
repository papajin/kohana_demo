<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Main Menu Controller
 *
 * @author Игорь
 */
class Controller_Widgets_Html extends Controller_Widgets {

    public function before()
    {
        $this->template = 'front/misc/front_misc_' . $this->request->param( 'param' );
        parent::before();
        $this->cache_lifetime = 0;
    }

    public function action_index() {}
}