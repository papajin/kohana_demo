<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Handling of front end ajax requests
 *
 * @author Игорь
 */
class Controller_Front_Ajax extends Controller_Ajax {

    public function action_refresh_front_page()
    {
        $this->clear_page_cache( '/' );
    }
}