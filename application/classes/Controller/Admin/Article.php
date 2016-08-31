<?php defined('SYSPATH') or die('No direct script access.');
/*
 * Article Management Controller
 */
class Controller_Admin_Article extends Controller_Admin {
    
    private $folder_path;
    
    public function before() {
        parent::before();
        
        $this->folder_path = APPPATH . 'content' . DIRECTORY_SEPARATOR . 'articles' . DIRECTORY_SEPARATOR;

        $this->page_title = __( 'Articles' );
    }

    public function action_index() 
    {
        // Articles are sorted by category (cat #6 is default)
        $category_id = $this->request->param( 'id' ) OR $category_id = 6;
        
        // Options for the category selector
        $options = ORM::factory( 'Category' )->listArticleCategories();
        $options[ 2 ] = __( 'all articles' );
        
        $a = ORM::factory( 'Article' );
        
        if ( $category_id != 2 )
            $a->where( 'category_id', '=', $category_id );
        
        $articles = $a->and_where_open()
                        ->where( 'parent_id', '=', 0 )
                        ->or_where( 'parent_id', '=', NULL )
                    ->and_where_close()
                    ->order_by( 'order' )
                    ->find_all();
        
        $this->content[] = View::factory( 'admin/a_article_index', 
                                            [ 'articles'    => $articles,
                                              'options'     => $options,
                                              'category_id' => $category_id ] );
        
        array_push( $this->scripts, 'sortable', 'page_index', 'article_index' );
    }
    
    public function action_edit()
    {
        if ( Cookie::get( 'from_delete' ) )
        {
            $this->error[] = __( 'Page delete failure' );
            Cookie::delete( 'from_delete' );
        }
        
        if ( ! $this->request->param( 'id' ) )
            HTTP::redirect ( 'admin/article' );
        
        $file_path = $this->folder_path . $this->request->param( 'id' );
        
        if ( $this->request->post( 'submit' ) !== NULL )
        {
            $values = [ 'category_id',
                        'parent_id',
                        'alias',
                        'title',
                        'keywords',
                        'description',
                        'intro',
                        'date',
                        'status',
                        'menu',
                        'widget'  ];
            
            $data = Arr::extract( $_POST, array_merge( $values, [ 'id', 'text' ] ) );
            
            $a = ORM::factory( 'Article', $this->request->param( 'id' ) );
            $data[ 'alias' ] = $a->slug_it ( $data[ 'alias' ], $data[ 'title' ] );
            if ( $this->request->post( 'text' ) )
            {
                if ( ! file_put_contents( $file_path, $this->request->post( 'text' ) ) )
                    $this->error[] = __( 'failure of saving article text into file' );
            }
            elseif ( file_exists( $file_path ) )
                unlink ( $file_path );
            try
            {    
                $a->values( $data, $values )->save();
                
                $this->success[] = __( 'Data saved' );
            }
            catch( ORM_Validation_Exception $e )
            {
                $this->error = $e->errors( 'validation' );
            }
        }
        else
        {
            $data = ORM::factory( 'Article', $this->request->param('id') )->as_array();
            $data[ 'text' ] = ( file_exists( $file_path ) ) ? file_get_contents( $file_path ) : '';
        }
        
        $options = ORM::factory( 'Article' )->listArticles( $data[ 'category_id' ] );
        $options[0] = __( 'Please, choose' );
        
        $categories = ORM::factory( 'Category' )->listArticleCategories();
        $categories[0] = __( 'Please, choose' );
        
        $this->content[] = View::factory( 'admin/a_article_edit',
                                              [ 'data'          => $data,
                                                'options'       => $options,
                                                'categories'    => $categories,
                                                'tags'          => Widget::load( 'Tags' ) ] );
        
        $this->page_title .= ':: ' . __( 'editing' );

        array_push( $this->scripts, 'ckeditor', 'page_edit', 'article_edit' );
    }
    
    public function action_add()
    {
        $this->content[] = Widget::load( 'Submenu', [ 'param' => 'article_edit' ] );
        $values = [ 'category_id',
                    'parent_id',
                    'alias',
                    'title',
                    'keywords',
                    'description',
                    'intro',
                    'date',
                    'status',
                    'menu',
                    'widget'  ];
        
        $data = Arr::extract( $this->request->post(), array_merge( $values, [ 'id', 'text' ] ) );
        
        if ( $this->request->post( 'submit' ) !== NULL )
        {
            $a = ORM::factory( 'Article' );
            
            $data[ 'alias' ] = $a->slug_it ( $data[ 'alias' ], $data[ 'title' ] );
            $data[ 'order' ] = 1;
            
            $data[ 'order' ] += ORM::factory( 'Article' )
                                    ->where( 'parent_id', '=', $data[ 'parent_id' ] )
                                    ->order_by( 'order', 'DESC' )
                                    ->find()
                                    ->get( 'order' );
            
            try
            {
                $a->values( $data, $values )->save();
                
                if ( $data[ 'text' ] )
                    file_put_contents( $this->folder_path . $a->pk(), $data[ 'text' ] );
                
                HTTP::redirect( Route::url( 'admin', [ 'controller' => strtolower( $this->request->controller() ),
                                                       'action'    => 'edit',
                                                       'id'        => $a->pk() ] ) );
            }
            catch( ORM_Validation_Exception $e )
            {
                $this->error = $e->errors( 'validation' );
            }
        }
        $data[ 'id' ] = $tags = NULL;
        $data[ 'category_id' ] OR $data[ 'category_id' ] = 4;

        $options = ORM::factory( 'Article' )->listArticles( $data[ 'category_id' ] );
        $options[ 0 ] = __( 'Please, choose' );
        
        $categories = ORM::factory( 'Category' )->listArticleCategories();
        $categories[ 0 ] = __( 'Please, choose' );
        
        $this->content[] = View::factory( 'admin/a_article_edit',
                                            [ 'data'    => $data,
                                              'options' => $options,
                                              'tags'    => $tags,
                                              'categories' => $categories ] );
        
        $this->page_title .= ':: ' . __( 'new' );
        array_push( $this->scripts, 'ckeditor', 'article_edit' );
    }
}