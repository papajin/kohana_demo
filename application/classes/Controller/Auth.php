<?php defined('SYSPATH') or die('No direct script access.');
/*
 * Авторизация
 */
class Controller_Auth extends Controller_Template {
    use Controller_Base;

    public function before()
    {
        $this->template = 'login';

        parent::before();

        $this->template_init();
    }
    
    public function action_index() 
    {
        $this->action_login();
    }

    public function action_login() 
    {
        $uri = $this->request->query( 'uri' );
        $uri = ( $uri ) ? urldecode ( $uri ) : 'admin';

        if( $this->_auth->logged_in() )
            HTTP::redirect( $uri );
        
        $data = Arr::extract( $this->request->post(), [ 'username', 'password', 'remember' ] );
        
        if ( $this->request->post( 'submit' ) )
        {
            if ( $this->_auth->login( $data[ 'username' ], $data[ 'password' ], ( bool ) $data[ 'remember' ] ) )
                HTTP::redirect( $uri );
//            else
//                $this->error[] = Kohana::message( 'auth/user', 'no_user' );
        }

        $this->template->uri = $uri;
        $this->template->data = $data;

        $this->styles += [ 'fontello', 'bootstrap', 'tether', 'style', 'light_green', 'animate' ];

        $this->template->page_title = __( 'Authentification' );
    }
    
    public function action_logout()
    {
        ( $this->_auth->logout() )
            ? HTTP::redirect()
            : HTTP::redirect( '/admin' );
    }
}