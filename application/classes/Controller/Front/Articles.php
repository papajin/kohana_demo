<?php defined('SYSPATH') or die('No direct script access.');
/*
 * Articles controller
 */
class Controller_Front_Articles extends Controller_Front {

    public function before()
    {
        parent::before();

        // FAQ page uses ajax request
        if ( $this->request->is_ajax() )
            exit( $this->answer_ajax() );
    }
    
    public function action_index ()
    {
        $this->content[] = $this->breadcrumbs( [ $this->_page->category->title => sprintf( '/%s.html', $this->_page->category->page->alias ) ] );

        $this->content[] = View::factory( 'front/tplt/front_tplt_article' );
        
        $terms = $this->_page->terms->find_all();
        $this->_sidebar[] = View::factory( 'front/misc/front_misc_tags', [ 'post_tags' => __( 'post_tag' ) ] )
                                        ->bind( 'tags', $terms );

        $this->scripts[] = 'modal';
    }
     
    public function action_tag ()
    {
        $t = ORM::factory('Term')
                ->where( 'slug', '=', $this->request->param( 'id' ) )
                ->find();

        $articles = $t->articles
                        ->where( 'status', '=', 1 )
                        ->find_all ();

        if( ! count( $articles ) )
            HTTP::redirect( '/articles.html' );

        // Reset page title and meta
        $this->_page->title = __( 'Articles with :param tag', [ ':param' => __( 'post_tag' )[ $t->slug ] ] );
        $this->_page->keywords = $t->name;
        $this->_page->description = $this->_page->title;
        $this->_page->content = HTML::wrap( $this->_page->title, [ 'class' => 'page-title' ], 'h1' ) . PHP_EOL . '<hr>' . PHP_EOL
                                . $this->getAccordion( $articles );

        $this->content[] = $this->breadcrumbs([]);
        $this->content[] = View::factory( 'front/tplt/front_tplt_library' );
    }

    public function action_category()
    {
        // TODO :list of articles from single category, if need be
    }

    /**
     * For single article (index action) "Article" is a model.
     * For tag action either page suits. We are going to use home page.
     * @return string
     */
    protected function alias ()
    {
        // For tag action either page suits.
        // But Article model has no "index" alias, which is going to be default, if no id parameter.
        if ( $this->request->action() == 'tag' )
        {
            return 'index';
        }
        else
        {
            $this->_model = Inflector::singular( strtolower( $this->request->controller() ) );
            return parent::alias();
        }
    }

    /**
     * Ajax request expected for FAQ articles, hence prepare title and intro only, wrapped with info div.
     * @return string response
     */
    protected function answer_ajax()
    {
        $answer = sprintf( '<div class="alert alert-info alert-dismissible"><h1 class="title">%s</h1>%s</div>', $this->_page->title, $this->_page->intro );

        if ( Kohana::PRODUCTION === Kohana::$environment )
        {
            try
            {
                Cache::instance( 'ajax' )->set ( strtolower( $this->request->uri() ), $answer, $this->cache_lifetime );
            }
            catch ( Cache_Exception $e )
            {
                Kohana::$log->add( Log::ERROR, $e->getMessage() );
            }
        }

        return $answer;
    }
}