<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Forms controller for forms, sent by $_POST, processing
 *
 * @author Ihor
 */
class Controller_Front_Form extends Controller_Front {
    private $driver;    // Instance of form processing class
    
    public function before()
    {
        parent::before();
        
        $this->driver = Model::factory( 'Form' );

        $this->driver->empty_post() OR $this->cache_lifetime = 0;           // No caching of form with data
    }
    
    public function action_contact ()
    {
        $errors = [];

        // Driver needs settings and container for errors.
        // Returns form data validation object or empty array.
        $data = $this->driver->contact( $this->_settings, $errors );
        
        $this->content[] = View::factory( 'front/tplt/front_tplt_contact' )
                                        ->set( 'is_spam', $this->driver->get_config( 'is_spam' ) )
                                        ->bind( 'errors', $errors )
                                        ->bind( 'data', $data );
        
        $this->_page->title = __( 'Contact us' );
        $this->scripts[] = 'check_form';
    }
    
    public function action_anketa ()
    {
        $errors = [];
        $data = $this->driver->anketa( $this->_settings, $errors );

        $this->content[] = View::factory( 'front/tplt/front_tplt_anketa' )
                                        ->set( 'nq', $this->driver->get_config( 'nq' ) )
                                        ->bind( 'errors', $errors )
                                        ->bind( 'data', $data );

        $this->_page->title = __( 'Submit inquiry for consultation of astrologer' );

        /* Zebra DatePicker */
        $this->scripts[] = 'zebra';
        $this->styles[] = 'zebra';
    }
}