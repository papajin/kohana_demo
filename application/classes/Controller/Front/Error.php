<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Error controller
 *
 * @author Игорь
 */
class Controller_Front_Error extends Controller_Front {
    public function before()
    {
        parent::before();

        $this->_page->keywords = '';
        $this->_page->description = '';
        $this->cache_lifetime = 0;
    }
    
    public function action_index ()
    {
        $code = $this->request->param( 'id' );
        switch ( $code )
        {
            case 404:
                $this->_page->title = sprintf( '%s | %s', __( 'Page not found' ), $this->_settings->author );
                $tplt = 'errors/v_404';
                break;
            case 500:
                $this->_page->title = sprintf( '%s | %s', __( 'Server error' ), $this->_settings->author );
                $tplt = 'errors/v_500';
                break;
            default :
                $this->_page->title = sprintf( '%s | %s', __( 'Error :code', [ ':code' => $code ] ), $this->_settings->author );
                $tplt = 'errors/v_default';
        }

        $this->content[] = View::factory( $tplt, [ 'code' => $code ] );
    }

    protected function alias ()
    {
        return 'index';
    }
}