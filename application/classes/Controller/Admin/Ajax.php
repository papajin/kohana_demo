<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Handling of ajax requests for authorized uzer
 *
 * @author Игорь
 */
class Controller_Admin_Ajax extends Controller_Ajax {

    protected $answer = [];

    public function before() 
    {
        parent::before();
        $this->_auth->logged_in( 'admin' ) OR  die(' --No access');
        $this->answer[ 'res' ] = FALSE;
    }

    public function after()
    {
        $this->_page_cache = json_encode( $this->answer );
        parent::after();
    }

    public function action_get_material()
    {
        $model = ucfirst( Inflector::singular( $this->request->param( 'id' ) ) );
        $obj = Arr::extract( $this->request->post(), [ 'field', 'value' ] );

        $this->answer = ORM::factory( $model )
                            ->where( $obj[ 'field' ], '=', $obj[ 'value' ] )
                            ->find()->as_array();
    }

    public function action_delete_cache ()
    {
        $controller = $this->request->post( 'model' );
        $controller OR $controller = Inflector::plural(
                            str_replace( '/admin/', '', URL::site( $this->request->referrer() ) )
                        );

        $controller = ( $controller == 'pages' ) ? NULL : $controller;

        $uri =  trim (
            Route::get( 'articles' )->uri( [ 'controller' => $controller, 'id' => $this->request->post( 'alias' ) ] ),
            '/'
        );

        if ( $uri == 'index.html' ) $uri = '/';

        try
        {
            $this->clear_page_cache( $uri );
            $this->answer[ 'res' ] = TRUE;
        }
        catch( Exception $e )
        {
            $this->answer[ 'mes' ][] = $e->getMessage();
        }
    }

    public function action_delete_all_cache ()
    {
        try
        {
            $this->answer[ 'res' ] = $this->{sprintf( 'clear_%s_cache', $this->request->post('param') )}();
        }
        catch( Exception $e )
        {
            $this->answer['mes'][] = $e->getMessage();
        }

        $this->answer[ 'res' ] OR $this->answer['mes'][] = __( 'Cannot delete some file(s)' );
    }

    public function action_generate_map ()
    {
        if ( Model::factory('Xmlmap')->generateMap() )
            $this->answer['res'] = TRUE;
        else
            $this->answer['mes'][] = __( 'XML sitemap filure. See log for details.' );
    }

    public function action_save_order ()   // TODO
    {
        $params = Validation::factory( $this->request->post() );
        $params ->rule( 'param', 'not_empty' )
                ->rule( 'model', 'alpha' );
        
        if ( $params->check() )
        {
            $data = json_decode( $params[ 'param' ] );
            
            $model = ( ! empty( $params[ 'model' ] ) )
                    ? $params[ 'model' ]
                    : str_replace( '/admin/', '', URL::site( $this->request->referrer() ) );
            
            $model = ucfirst( $model );
            try
            {
                foreach ( $data as $id => $order )
                    ! Valid::digit( $order ) OR ORM::factory( $model, intval( $id ) )->set( 'order', $order )->update();

                $this->answer['res'] = TRUE;
            }
            catch ( ORM_Validation_Exception $e )
            {
                $this->answer[ 'mes' ] = $e->getMessage();
            }
        }
        else
        {
            $this->answer[ 'mes' ] = $params->errors( 'validation' );
        }
    }

    public function action_get_cat_articles ()
    {
        $this->answer['data'] = ORM::factory( 'Article' )
             ->listArticles( $this->request->post( 'param' ) );

        $this->answer['res'] = TRUE;
    }

    public function action_add_tag ()
    {
        $tag_slug = array_flip( __( 'post_tag' ) )[ $this->request->post( 'param' ) ];
        try
        {
            ORM::factory( 'Article', $this->request->post( 'article_id' ) )->add_tag( [ 'slug' => $tag_slug ] );
            $this->answer['res'] = TRUE;
        }
        catch ( ORM_Validation_Exception $ex )
        {
            $this->answer[ 'mes' ][] = $ex->errors( 'validation' );
        }
    }
    
    public function action_remove_tag ()
    {
        $tag_slug = array_flip( __( 'post_tag' ) )[ $this->request->post( 'param' ) ];
        try
        {
            ORM::factory( 'Article', $this->request->post( 'article_id' ) )->remove_tag( [ 'slug' => $tag_slug ] );
            $this->answer['res'] = TRUE;
        }
        catch ( ORM_Validation_Exception $ex )
        {
            $this->answer[ 'mes' ][] = $ex->getMessage();
        }
    }

    public function action_get_tag ()
    {
        $tag_slug = array_flip( __( 'post_tag' ) )[ $this->request->post( 'param' ) ];
        $tag = ORM::factory( 'Term', [ 'slug' => $tag_slug ] );
        if ( $tag->loaded() )
        {
            $desc = $tag->taxonomy->description;
            $this->answer[ 'data' ] = $tag->as_array();
            $this->answer[ 'data' ][ 'description' ] = $desc;
            $this->answer[ 'res' ] = TRUE;
        }
        else
        {
            $this->answer[ 'mes' ][] = __( 'tag not found' );
        }
    }

    public function action_save_tag()
    {
        $tag = new Validation( $this->request->post( 'param' ) );
        $tag    ->rule( 'term_id', 'not_empty' )
                ->rule( 'term_id', 'numeric' )
                ->rule( 'name', 'not_empty' );
        if ( $tag->check() )
        {
            $term = ORM::factory( 'Term', $tag[ 'term_id' ] );

            if ( $term->loaded() )
            {
                $term->name = $tag[ 'name' ];
                $term->slug = $term->slug_it( $tag[ 'slug' ], $tag[ 'name' ] );
                $term->taxonomy->description = $tag[ 'description' ];
                try
                {
                    $term->taxonomy->save();
                    $term->save();
                }
                catch ( ORM_Validation_Exception $ex )
                {
                    $this->answer[ 'mes' ][] = $ex->errors( 'validation' );
                }

                $this->answer[ 'res' ] = TRUE;
            }
            else
            {
                $this->answer[ 'mes' ][] = __( 'tag not found' );
            }
        }
        else
        {
            $this->answer[ 'mes' ] = $tag->errors( 'validation' );
        }
    }

    public function action_article_widget()
    {
        try
        {
            $a = ORM::factory( 'Article', $this->request->param( 'id' ) );
            if ( $a->loaded() )
            {
                $a->set( 'widget', $this->request->post( 'w' ) )->update();
                $this->request->post( 'model', 'pages' );
                $this->request->post( 'alias', 'index' );
                $this->action_delete_cache();
            }
        }
        catch ( ORM_Validation_Exception $ex )
        {
            $this->answer[ 'mes' ][] = $ex->errors( 'validation' );
        }
    }

    public function action_gallery_get ()
    {
        $this->answer['gallery'] = ORM::factory( 'Gallery', $this->request->param('id') );

        if ( $this->answer[ 'gallery' ]->loaded() )
        {
            $this->answer[ 'slides' ] = $this->arrObjs_to_arrArrs ( $this->answer[ 'gallery' ]->slides->order_by( 'order' )->find_all()->as_array() );
            $this->answer[ 'gallery' ] = $this->answer[ 'gallery' ]->as_array();
            $this->answer[ 'res' ] = TRUE;
        }
        else
        {
            $this->answer['mes'][] = __( 'gallery not found' );
        }
    }

    public function action_toggle_slide ()
    {
        try
        {
            ORM::factory( 'Slide', $this->request->param( 'id' ) )
                            ->set( 'published', $this->request->post( 'val' ) )
                            ->update();
            $this->answer[ 'res' ] = TRUE;
        }
        catch ( ORM_Validation_Exception $ex )
        {
            $this->answer[ 'mes' ] = $ex->errors( 'validation' );
        }
    }

    public function action_slide_delete ()
    {
        try
        {
            ORM::factory( 'Slide', $this->request->param( 'id' ) )->delete();
            $this->answer[ 'res' ] = TRUE;
        }
        catch ( Exception $ex )
        {
            $this->answer[ 'mes' ] = $ex->getMessage();
        }
    }

    public function action_gallery_delete ()
    {
        try
        {
            ORM::factory( 'Gallery', $this->request->param( 'id' ) )->delete();
            $this->answer[ 'res' ] = TRUE;
        }
        catch ( Exception $ex )
        {
            $this->answer[ 'mes' ][] = $ex->getMessage();
        }
    }


    public function action_save_slide ()
    {
        $slide = json_decode( $this->request->post( 'slide' ), TRUE );
        $slide[ 'published' ] = ( ! empty( $slide[ 'published' ] ) ) ? 1 : 0;  // 'on' if checked and no index otherwise

        $res = ORM::factory( 'slide', $slide[ 'id' ] );
        try
        {
            $res->values( $slide, [ 'gallery_id', 'path', 'thumb', 'title', 'caption', 'published', 'order' ] )->save();
            $this->answer[ 'res' ] = TRUE;
            $this->answer[ 'id' ] = $res->pk();
        }
        catch ( ORM_Validation_Exception $ex )
        {
            $this->answer[ 'mes' ] = $ex->errors( 'validation' );
        }
    }
    /*
    public function action_get_editor()
    {
        $ref = Route::get( 'articles' )->matches( Request::factory( URL::site( $this->request->referrer() ) ) );
        if ( $ref !== FALSE )
        {
            $ref[ 'controller' ] = ( $ref[ 'controller' ] == 'Index' )
                                    ? 'Page'
                                    : Inflector::singular( $ref[ 'controller' ] );
            $ref [ 'id' ] = ORM::factory( $ref[ 'controller' ], [ 'alias' => $ref [ 'id' ] ] )->get( 'id' );

            $this->answer['src'] = URL::site( Route::get( 'admin' )->uri( [
                'controller' => $ref[ 'controller' ],
                'action'     => 'edit',
                'id'         => $ref [ 'id' ]
            ] ) );

            $this->answer[ 'res' ] = TRUE;
        }
    }*/
}