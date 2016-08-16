<?php defined('SYSPATH') OR die('No direct script access.');

class Controller extends Kohana_Controller {
    /**
     * @var auth object
     */
    public $_auth = NULL;

    /**
     * @var object of active User
     */
    public $_user = FALSE;
    
    /**
     * @var Config with basic settings
     */
    public $_settings;

    /**
     * @var Cache driver
     */
    public $_cache;
    
    /**
     * @var string cached page content
     **/
    public $_page_cache;
    
    /**
     * @var session
     **/
    public $_session;
    
    /**
     * The developer may need to see page performance data (stats). Then just set this TRUE. 
     */
//    protected $profiler = FALSE;

    /**
     * Executes the given action and calls the [Controller::before] and [Controller::after]
     * methods - if no valid cache for requested page.
     *
     * Can also be used to catch exceptions from actions in a single place.
     *
     * 1. Before the controller action is called, the [Controller::before] method
     * will be called.
     * 2. Next the controller action will be called.
     * 3. After the controller action is called, the [Controller::after] method
     * will be called.
     *
     * @throws  HTTP_Exception_404
     * @return  Response
     */
    public function execute()
    {
        // Sometimes session exeption was thrown on live server (some bugs on server side).
        // Don't want to see it again.
        do
        {
            try
            {
                $this->_session = Session::instance();
            }
            catch ( Session_Exception $ex )
            {
                Kohana::$log->add( Log::ERROR, $ex->getMessage() );
            }
        } while ( ! $this->_session );
        
        
        $request_uri = strtolower( $this->request->uri() );
        
        // Settings may depend on language, hence language to be set first.
        $this->set_language();
        $this->_settings = Kohana::$config->load( 'settings' );
        
        
        /*
         * Widgets cache stored in widgets dir, pages - in pages dir (default)
         * of corresponding language, which is set in cache config.
         */
        $cache_conf = ( $this->request->current()->is_ajax() )
                        ? 'ajax'
                        : ( ( strtolower( $this->request->directory() ) === 'widgets' )
                            ? 'widgets'
                            : NULL );

        $this->_cache = Cache::instance( $cache_conf );
        
        // Any form submit is good reason to process the request. 
        // Otherwise check for cache.
        if( ! Valid::not_empty( $_POST ) )
        {
            // Get page cache. Delete cache if corrupted.
            try
            {
                $this->_page_cache = $this->_cache->get( $request_uri );
            }
            catch ( Cache_Exception $ex )
            {
                $this->_cache->delete( $request_uri );
            }
        }
        
        // If no cached page, then proceed with its generating.
        if ( ! Valid::not_empty( $this->_page_cache ) )
        {
            // Execute the "before action" method
            $this->before();

            // Determine the action to use
            $action = 'action_' . $this->request->action();

            // If the action doesn't exist, it's a 404
            if ( ! method_exists( $this, $action ) )
            {
                throw HTTP_Exception::factory( 
                        404,
                        'The requested URL :uri was not found on this server.',
                        [ ':uri' => $this->request->uri() ]
                )->request( $this->request );
            }

            // Execute the action itself
            $this->{$action}();

            // Execute the "after action" method
            $this->after();
        }
        
        /**
         * Profiler stats presentation generated in case flag set TRUE
         * and initial request - to avoid multiple stats instanses.
         */
        $stats = ( Kohana::$profiling AND $this->request->is_initial() )
                        ? View::factory( 'profiler/stats' )
                        : '';
        
        // @page_cache contains html - cached or live. That is the response body.
        $this->response->body( $this->_page_cache . $stats );
        
        return $this->response;
        
    }
    
    public function before()
    {
        parent::before();
        
        /**
         * Authentication and user are used by Admin and Admin_Ajax
         */
        $this->_auth = Auth::instance();
        $this->_user = $this->_auth->get_user();
    }
    
    public function after()
    {
        parent::after();
    }
    
    /**
     * Default language set in bootstrap, but can be overriden.
     */
    protected function set_language(){}

    public function languages()
    {
        return array_map(
                    function( $str ){ return basename( $str, '.php' ); },
                    Kohana::list_files( 'i18n', [ APPPATH ] )
                );
    }

    /**
     * Delete all cache files (for all configurations) - Kohana native and custom.
     * @param array $mes optional container for messages.
     * @return bool result: TRUE on success for all cache configs.
     * @throws Kohana_Exception
     */
    public function clear_all_cache()
    {
        $res = TRUE;

        $configs = array_keys( Kohana::$config->load('cache')->as_array() );

        try
        {
            foreach ( $configs as $config )
            {
                if ( $config == 'file' OR $config == 'widgets' ) continue;
                Cache::instance( $config )->delete_all() OR $res = FALSE;
            }
                
        }
        catch( Kohana_Cache_Exception $e )
        {
            $res = FALSE;
            Kohana::$log->add( Log::WARNING, $e->getMessage() );
        }

        self::clear_blog_widgets_cache() OR $res = FALSE;

        return $res;
    }
    
    public function clear_widgets_cache( $lang_arr = NULL )
    {
        $res = TRUE;

        $lang_arr !== NULL OR $lang_arr = $this->languages();

        foreach ( $lang_arr as $lang )
            if ( ! Cache::instance( $lang . '_widgets' )->delete_all() )
                $res = FALSE;

        return $res;
    }

    public function clear_pages_cache( $lang_arr = NULL )
    {
        $res = TRUE;

        $lang_arr !== NULL OR $lang_arr = $this->languages();

        foreach ( $lang_arr as $lang )
            if ( ! Cache::instance( $lang . '_pages' )->delete_all() )
                $res = FALSE;

        return $res;
    }

    public function clear_page_cache( $uri, $lang_arr = NULL )
    {
        $res = TRUE;

        $lang_arr !== NULL OR $lang_arr = $this->languages();

        foreach ( $lang_arr as $lang )
            if ( ! Cache::instance( $lang . '_pages' )->delete( $uri ) )
                $res = FALSE;

        return $res;
    }

    /**
     * Delete custom cache files (blog widgets cache) for passed language(s) or ALL languages.
     * @return bool result of operation: FALSE if any file delete failure.
     */
    public function clear_blog_widgets_cache( $lang_arr = NULL )
    {
        $res = TRUE;

        $lang_arr !== NULL OR $lang_arr = $this->languages();

        foreach ( $lang_arr as $lang )
        {
            foreach ( glob( sprintf( '%1$scontent%2$sassets%2$s%3$s%2$s*', APPPATH, DIRECTORY_SEPARATOR, $lang ) ) as $path )
            {
                if ( ! unlink( $path ) ) $res = FALSE;
            }
        }

        return $res;
    }

    /**
     * Converts array of ORM objects to array of arrays.
     * @param array ORM objects $arrObjs
     * @return array
     */
    public function arrObjs_to_arrArrs( $arrObjs )
    {
        return array_map(
            function( $obj ) { return $obj->as_array(); },
            $arrObjs
        );
    }
} // End Controller