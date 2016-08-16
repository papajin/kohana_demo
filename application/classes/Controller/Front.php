<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Front end base controller
 *
 * @author Ihor
 */
class Controller_Front extends Controller_Template {
    use Controller_Base;

    protected $_alias;
    protected $_page;
    protected $_model = 'Page';
    protected $_sidebar = [];   // Sidebar container
    protected $_url;            // Full page URL (for bookmarks, ...)

    public function before()
    {
        parent::before();
        
        $this->_alias = $this->alias();

        $this->get_page_object();

        // Bind styles and scripts, pass lists of styles and scripts.
        $this->template_init();

        /* Some template global vars */
        $this->template->bind_global( 'page', $this->_page );
        $this->template->bind_global( 'url', $this->_url );
        $this->template->bind_global( 'lang', I18n::$lang );
        $this->template->bind_global( 'sidebar', $this->_sidebar );

        /*
         * Frontend common styles and scripts.
         * More can be added in children controllers (lists are in corresponding configs).
         */
        $this->styles += [ 'fontello', 'bootstrap', 'tether', 'style', 'light_green', 'animate' ];
        $this->scripts += [ 'tether', 'bootstrap', 'modernizr', 'front' ];
        
        $this->content[] = Widget::load( 'Topper' );
                
        $this->content[] = Widget::load( 'Header' );

        // Sidebar widgets
        $this->_sidebar[] = $this->bookmarks();     // Share buttons
        $this->_sidebar[] = $this->call();          // 'Замовити консультацію' button

//                            Widget::load( 'YandexVer' );
    }
    
    public function after()
    {
        $this->_url = $this->_page->make_full_url();

//        $this->content[] = HTML::wrap( $this->main_block, [ 'class' => 'container-fluid', 'role' => 'main' ] );
        $this->content[] = HTML::wrap( Widget::load( [ 'Footer', 'Subfooter' ] ), [ 'id' => 'footer'], 'footer' );

        parent::after();
    }

    /**
     * Creating of page object, containing title, keywords, description, content etc.
     * @throws HTTP_Exception 404 page not found
     * @throws Kohana_Exception
     */
    protected function get_page_object()
    {
        $this->_page = ORM::factory( ucfirst( $this->_model ) )
                            ->where( 'alias', '=', $this->_alias )
                            ->where( 'status', '>', 0 )
                            ->find();

        // If no such a record in DB, then 404
        if ( ! $this->_page->loaded() )
            throw HTTP_Exception::factory(
                404,
                'The requested URL :uri was not found on this server.',
                [ ':uri' => $this->request->uri() ]
            )->request( $this->request );
    }

    protected function alias ()
    {
        $alias = $this->request->param( 'id' );

        // If no id param, alias is an action.
        $alias OR $alias = $this->request->action();

        return $alias;
    }
    
    /**
     * Default language is Ukrainian unless otherwise is set in query or cookie.
     * New langauge preference is stored in cookie.
     */
    protected function set_language()
    {
        $lang = Arr::get( $_COOKIE, 'lang', I18n::lang() );
        
        // Language cannot be different from what we have files for in i18n folder.
        // We get list of language files and strip path and .php ending.
        $lang_arr = array_map( 
                        function( $str ){ return basename( $str, '.php' ); }, 
                        Kohana::list_files( 'i18n', [ APPPATH ] )
                    );

        $change_lang = Validation::factory( $_GET );
        $change_lang->rule( 'lang', 'not_empty' )
                    ->rule( 'lang', 'in_array', [ ':value', $lang_arr ] );

        if ( $change_lang->check() ) {
            $lang = $change_lang[ 'lang' ];
            setcookie( "lang", $lang, time() + 3600 * 24 * 365, '/' );
        }
        
        I18n::lang( $lang );
    }

    /**
     * Breadcrumbs widget generation.
     * @param array $inner_links title => href pairs for links.
     * @param mixed $active_title FALSE removes active page title from the widget, TRUE (default) sets to current article title, string sets new title.
     * @param bool $show_home hides Home page link if FALSE (default is TRUE).
     * @return View
     */
    protected function breadcrumbs( $inner_links, $active_title = TRUE, $show_home = TRUE )
    {
        if ( $active_title === TRUE ) $active_title = $this->_page->title;

        return View::factory( 'front/tplt/front_tplt_breadcrumbs', [
            'inner'         => $inner_links,
            'active_title'  => $active_title,
            'show_home'     => $show_home
        ] );
    }
    
    protected function bookmarks()
    {
        return View::factory( 'front/misc/front_misc_bookmark_btns' );
    }

    protected function call()
    {
        return View::factory( 'front/misc/front_misc_call' );
    }
    
    /**
     * Function renders accordion template with articles.
     * @param array ORM objects $articles
     * @return object of View class.
     */
    protected function getAccordion( $articles ) 
    {
        return View::factory (  'front/tplt/front_tplt_accordion', 
                                [ 'articles' => $articles ] );
    }
}