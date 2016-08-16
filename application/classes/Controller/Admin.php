<?php defined('SYSPATH') or die('No direct script access.');
/*
 * Base class of the backend
 */
class Controller_Admin extends Controller_Template {
    use Controller_Base;
    
    protected $page_title;

    // @alerts
    protected $success = [];
    protected $information = [];
    protected $warning = [];
    protected $error = [];
    
    public function  before() 
    {
        parent::before();
        
        $this->template_init();

        $this->cache_lifetime = 0; // No caching for admin area supposed
        $this->page_title = __( 'Administration' );
        
        if ( !$this->_auth->logged_in( 'admin' ) )
            HTTP::redirect( 'login?uri='.urlencode( $this->request->uri() ) );

        // To template
        $this->styles += [ 'fontello', 'bootstrap', 'tether', 'animate', 'admin' ];
        $this->scripts += [ 'tether', 'bootstrap', 'modernizr', 'noty', 'admin', 'admin.' . I18n::lang() ];
        
        $this->template->bind( 'page_title', $this->page_title );
        $this->template->menu_admin = Widget::load( 'Menuadmin' );
        
        if( Kohana::find_file( 'views/admin/w', strtolower( $this->request->controller() ) . '_' . $this->request->action() ) )
            $this->content[] = Widget::load( 'Submenu' );
    }
    
    public function after()
    {
        $this->handle_alerts();
        parent::after();
    }

    /**
     * Alerts processing: string is pushed straight into the array with the
     * corresponding type flag; array of strings is pushed one by one.
     */
    private function handle_alerts()
    {
        $alert_types = [ 'success', 'information', 'warning', 'error' ];
        $alerts = [];

        foreach ( $alert_types as $alert )
        {
            if ( Valid::not_empty( $this->$alert ) )
            {
                if( is_array( $this->$alert ) )
                    foreach ( $this->$alert as $record )
                        array_push ( $alerts, [
                            'type' => $alert,
                            'text' => $record
                        ]);
                else
                    array_push ( $alerts, [
                        'type' => $alert,
                        'text' => $this->$alert
                    ]);
            }
        }


        /**
         * Empty array becomes NULL. Then json encode the alerts for hidden form field.
         */
        $alerts = json_encode( $alerts );

        $this->template->bind( 'alerts', $alerts );
    }
    
    public function action_delete()
    {
        $controller = strtolower( $this->request->controller() );
        $inst = ORM::factory( ucfirst( $controller ), $this->request->param( 'id' ) );
        try
        {
            $inst->delete();
        }
        catch ( Exception $ex )
        {
            Cookie::set( 'from_delete', TRUE );
            
            HTTP::redirect( Route::url( 'admin', [ 'controller' => $controller,
                                                    'action'    => 'edit',
                                                    'id'        => $this->request->param( 'id' ) ] ) );
        }
        
        HTTP::redirect( Route::url( 'admin', [ 'controller' => $controller ] ) ); 
    }
}