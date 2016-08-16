<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Index controller
 *
 * @author Игорь
 */
class Controller_Front_Index extends Controller_Front {
    protected $subtplt;

    public function before()
    {
        parent::before();

        // Library subtemplate fits almost every page.
        $this->subtplt = 'front/tplt/front_tplt_library';
        $this->_page->title = __( $this->_page->title );
    }
    
    public function after()
    {
        // If not home page
        if ( $this->_alias !== 'index' )
        {

            $this->content[] = $this->breadcrumbs( [] );
            $this->content[] = View::factory( $this->subtplt );
        }

        parent::after();
    }
    
    public function action_index()
    {
        switch ( $this->_alias )
        {
            case 'index':
                    $this->home ();
                    break;
                
            case 'faq':
                    $this->faq ();
                    break;
                
            case 'map':
                    $this->map ();
                    break;
                
            case 'calendar':
                    $this->calendar ();
                    break;
                
            case 'schema':
            case 'flash_schema':
                    $this->schema ();
                    break;
                
            case 'anketa':
            case 'contact':
                    $this->form ();
                    break;
                
            case 'astro_vocabulary':
            case 'astro_vocabularyD':
            case 'astro_vocabularyL':
            case 'astro_vocabularyO':
            case 'astro_vocabularyR':
            case 'astro_vocabularyT':
                    $this->articles ( TRUE );
                    break;
            
            case 'articles_pop':
            case 'articles':
            case 'articles_consulting':
            case 'articles_img':
                    $this->articles ();
                    break;
            
            default:
                $this->default_page ();
        }
    }

    /**
     * Home page action
     */
    private function home()
    {
        // Get latest articles
        $horoscopes = [ 'sun'   => ORM::factory( 'Term' )->getActualHoroscopes(),
                        'moon'  => ORM::factory( 'Term' )->thisMonthHoroscope() ];

        
        $this->content[] = View::factory( 'front/tplt/front_tplt_home', [
                                        'months' => array_flip( __( 'months_to_number' ) ),
                                        'post_tags' => __( 'post_tag' ),
                                        'horoscopes'=> $horoscopes,
                                        'parent'    => ORM::factory( 'Term', 437 ),
                                        'essays'    => ORM::factory( 'Post' )->essays(),
                                        'articles'  => ORM::factory( 'Article' )->widgets(),
                                        'feedback'  => Widget::load( 'Feedback' ),
                                        'widgets'   => [
                                                        Widget::load( 'Html', [ 'param' => 'calendar' ] ),
                                                        Widget::load( 'Html', [ 'param' => 'schema' ] )
                                                    ]
        ] );

        // Styles
        $this->styles[]  = 'owl';

        // Scripts
        $this->scripts[] = 'appear';
        $this->scripts[] = 'masonry';
        $this->scripts[] = 'owl';
    }
    
    private function articles( $vocab = FALSE )
    {
        // Get tabs data
        $tab_list = ORM::factory('Page')
                        ->where('parent_id', '=', 6)
                        ->where('status', '>', 0)
//                        ->order_by('order')
                        ->find_all()
                        ->as_array();
        
        $this->_page->content .= View::factory( 'front/tplt/front_tplt_tabs',
                                                [ 'tabs'     => $tab_list,
                                                  'is_vocab' => $vocab ] );
        
        if ( $vocab )
        {
            $alfa = ( $a = str_replace( 'astro_vocabulary', '', $this->_alias ) )
                    ? strtolower( $a )
                    : 'a';

            $articles = ORM::factory( 'Article' )
                            ->where( 'category_id', '=', 9 )
                            ->and_where( 'status', '=', 1 )
                            ->where( 'title', '>', $this->_settings->index_leters[ $alfa ][ 'start' ] )
                            ->where( 'title', '<', $this->_settings->index_leters[ $alfa ][ 'end' ])
                            ->order_by( 'title' )
                            ->find_all();

            $menu = ( $this->_page->menu ) ? $this->_page->menu() : $this->_page->parent->menu();

            $this->_page->title =  sprintf( '%s | %s', $menu, $this->_page->title );
        }
        else 
        {
            $cat = ORM::factory('Category')
                    ->where( 'id', '=', $this->_page->id )
                    ->find();

            $articles = $cat->articles
                            ->where( 'parent_id', '=', 0 )
                            ->where( 'status', '>', 0 )
                            ->order_by( 'order' )
                            ->find_all();
        }

        $this->_page->content .= $this->getAccordion( $articles, TRUE );
    }
    
    private function faq () 
    {
        $this->cache_lifetime = 3600 * 24 * 365;

        $items = ORM::factory('Article')
                        ->where('category_id', '=', 5)
                        ->and_where('status', '=', 1)
                        ->order_by('order')
                        ->find_all();
        $list = '';
        foreach ( $items as $item )
            $list .= HTML::wrap( HTML::anchor( $item->make_uri(), $item->title, [ 'class' => 'ajax-popup-link' ] ), NULL, 'li' );

        $this->_page->content .= '<hr class="space-bottom">' . HTML::wrap($list, NULL, 'ul');
        $this->styles[] = 'magnific-popup';
        $this->scripts[] = 'magnific-popup';
    }
    
    private function map () 
    {
        // Blog categories
        $tax = ORM::factory('Taxonomy')
                ->where('taxonomy', '=', 'category')
                ->find_all();

        $terms = [];
        foreach ($tax as $t)
            $terms[] = $t->term;

        // Blog pages
        $blog_pages = ORM::factory( 'Post' )->where( 'post_type', '=', 'page' )->find_all();

        // Pages and their children
        $p = ORM::factory( 'Page' );

        $pages = $p->where( 'menu', '!=', '' )
                    ->where( 'parent_id', '=', 0 )
                    ->where( 'status', '>', 0 )
                    ->order_by( 'order' )
                    ->find_all()
                    ->as_array();
        

        $vocab = $p->where('id', '=', 9 )
                        ->where( 'status', '>', 0 )
                        ->find();
        
        


        // Article blocks
        $categories = ORM::factory( 'Category' )
                        ->where( 'parent_id', '=', 2 )
                        ->find_all();
                
        $this->main[] = $this->_page->content;
        $this->_page->content .= View::factory( 'front/tplt/front_tplt_map',
                                                [   'blog_cats'  => $terms,
                                                    'pages'      => $pages,
                                                    'vocab'      => $vocab,
                                                    'blog_pages' => $blog_pages,
                                                    'categories' => $categories  ] );

        $this->scripts[] = 'masonry';
    }
    
    private function default_page ()
    {
        $this->cache_lifetime = 3600 * 24 * 365;
//        $this->_page->content .= $this->getAccordion(
//            ORM::factory( 'Term' )->getArticlesByTag( $this->_alias )
//        );

        $this->scripts[] = 'modal';
    }
    
    private function calendar()
    {
        // Get available calendar images and select first and last of them.
        $calendars = array_map(
            function( $item ){ return basename( $item, '.png' ); },
            array_values ( Kohana::list_files( 'images' . DIRECTORY_SEPARATOR . 'calendar', [ DOCROOT ] ) )
        );
        
        $m = date( 'm' );
        $y = date( 'Y' );

        $actual  = array_search( sprintf( '%d_%s', $y, $m ), $calendars );

        $this->_page->content = View::factory( 'front/tplt/front_tplt_calendar',
                                        [
                                            'actual'    => $actual,
                                            'months'    => __( 'months' ),
                                            'm'         => $m,
                                            'y'         => $y,
                                            'calendars' => $calendars
                                        ] );

        $this->styles[]  = 'almafont';
        $this->styles[]  = 'magnific-popup';

        $this->scripts[] = 'magnific-popup';
        $this->scripts[] = 'calendar';
    }
    
    private function schema()
    {
        $this->cache_lifetime = 3600 * 24 * 365;
        
        if ( $this->request->param( 'id' ) == 'schema' )
        {
            $this->_page->content = View::factory( 'front/tplt/front_tplt_schema' )
                                    . HTML::wrap( $this->_page->content, [ 'class' => 'm-t-2' ] );

            $this->styles[]  = 'astrochart';

            $this->scripts[] = 'modal';
            $this->scripts[] = 'zodiac';
            $this->scripts[] = 'astrochart_' . I18n::lang();
            $this->scripts[] = 'astrochart_ctls';
        }
        else
        {
            $this->_page->content = View::factory('front/tplt/front_tplt_schema_flash')
                                    . HTML::wrap( $this->_page->content, [ 'class' => 'm-t-2' ] );
        }
        
        $this->main[] = $this->_page->content;
    }

    protected function alias()
    {
        $alias = parent::alias();

        return  ( $alias == 'schema_flash' ) ? 'schema' : $alias;
    }
}