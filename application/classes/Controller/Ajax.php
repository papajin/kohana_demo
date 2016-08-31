<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Handling of open ajax requests 
 *
 * @author Игорь
 */
class Controller_Ajax extends Controller {

    public function before() 
    {
        // No direct access expected
        $this->request->is_ajax() OR  die( ' --No access' );

        parent::before();

        $this->response->headers( [
            'Expires' => 'Mon, 23 May 1995 02:00:00 GTM',
            'Last-Modified' => gmdate( "D, d M Y H:i:s" ) . " GTM",
            'Cache-Control' => 'no-cache, must-revalidate',
            'Pragma' => 'no-cache'
        ] );
    }
}