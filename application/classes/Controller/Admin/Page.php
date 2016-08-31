<?php defined('SYSPATH') or die('No direct script access.');
/*
 * Page Management Controller
 */
class Controller_Admin_Page extends Controller_Admin {
    
    public function before() {
        parent::before();

        $this->page_title = __( 'Pages' );
    }

    public function action_index() 
    {
        $pages = ORM::factory( 'Page' )
                                ->order_by( 'parent_id' )
                                ->order_by( 'order' )
                                ->find_all()
                                ->as_array();
        
        $this->content[] = View::factory( 'admin/a_page_index', [ 'pages' => $pages ] );

        array_push( $this->scripts, 'sortable', 'page_index' );
    }
    
    public function action_edit()
    {
        if ( Cookie::get( 'from_delete' ) )
        {
            $this->error[] = __( 'Page delete failure' );
            Cookie::delete( 'from_delete' );
        }
        
        if ( !$this->request->param('id') )
            HTTP::redirect ( 'admin/page' );
        
        if ( $this->request->post( 'submit' ) !== NULL )
        {
            $data = Arr::extract( $_POST, [ 'id',
                                            'parent_id',
                                            'title',
                                            'keywords',
                                            'description',
                                            'content',
                                            'alias',
                                            'status',
                                            'menu'  ] );

            
                $a = ORM::factory( 'Page', $this->request->param( 'id' ) );
                $data[ 'alias' ] = $a->slug_it ( $data[ 'alias' ], $data[ 'title' ] );
            try
            {    
                $a->values( $data )->save();
                
                $this->success = __( 'Data saved' );
            }
            catch( ORM_Validation_Exception $e )
            {
                $this->error = $e->errors( 'validation' );
            }
        }
        else
        {
            $data = ORM::factory( 'Page', $this->request->param( 'id' ) )->as_array();
        }
        $options = ORM::factory( 'Page' )->listPages();
        $options[ 0 ] = sprintf( '- %s -', __( 'Please, choose' ) );
        
        $this->content[] = View::factory( 'admin/a_page_edit',
                                           [ 'data'     => $data,
                                             'options'  => $options ] );
        
        $this->page_title .= ':: ' . __( 'editing' );
        array_push( $this->scripts, 'ckeditor', 'page_edit' );
    }
    
    public function action_add()
    {
        $this->content[] = Widget::load( 'Submenu', [ 'param' => 'page_edit' ] );

        $data = Arr::extract( $_POST, [ 'parent_id',
                                        'title',
                                        'keywords',
                                        'description',
                                        'content',
                                        'alias',
                                        'status',
                                        'menu'  ] );
        
        if ( $this->request->post( 'submit' ) !== NULL )
        {
            $a = ORM::factory( 'Page' );
            
            $data[ 'alias' ] = $a->slug_it ( $data[ 'alias' ], $data[ 'title' ] );
            $data[ 'order' ] = 1;
            
            $data[ 'order' ] += ORM::factory( 'Page' )
                                    ->where( 'parent_id', '=', $data[ 'parent_id' ] )
                                    ->order_by( 'order', 'DESC' )
                                    ->find()
                                    ->order;
                        
            try
            {
                $a->values( $data )->save();
                
                HTTP::redirect( Route::url( 'admin', [ 'controller' => strtolower( $this->request->controller() ),
                                                        'action'    => 'edit',
                                                        'id'        => $a->pk() ] ) );
            }
            catch( ORM_Validation_Exception $e )
            {
                $this->error = $e->errors( 'validation' );
            }
        }
        $data[ 'id' ] = NULL;
        
        $options = ORM::factory( 'Page' )->listPages();
        $options[ 0 ] = sprintf( '- %s -', __( 'Please, choose' ) );
        
        $this->content[] = View::factory( 'admin/a_page_edit',
                                            [ 'data'    => $data,
                                              'options' => $options ] );
        
        $this->page_title .= ':: ' . __( 'new' );
        array_push( $this->scripts, 'ckeditor', 'page_edit' );
    }
}