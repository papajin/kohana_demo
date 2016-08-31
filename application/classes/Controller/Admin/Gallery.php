<?php defined('SYSPATH') or die('No direct script access.');
/*
 * Gallery Handling Controller
 */
class Controller_Admin_Gallery extends Controller_Admin {

    public function action_index() 
    {
        $data = Arr::extract( $this->request->post(), [ 'id', 'thumb', 'title', 'caption' ] );
        
        if ( ! is_null( $this->request->post( 'gal_save' ) ) )
        {
            try
            {
                $g = ORM::factory ( 'Gallery', $data[ 'id' ] )->values ( $data )->save ();
                $data[ 'id' ] = $g->pk();
            }
            catch( ORM_Validation_Exception $ex )
            {
                $this->error = $ex->errors( 'validation' );
            }
        }
        
        $galleries = Arr::merge( [ NULL => __( 'Please, choose' ) ],
                                ORM::factory( 'Gallery' )->find_all()->as_array( 'id', 'title' ) );


        $this->content[] = View::factory( 'admin/w/gallery_controls',
                                          [ 'galleries' => $galleries,
                                            'id'        => $data[ 'id' ] ] );
        
        $this->content[] = View::factory( 'admin/a_gallery', [ 'data' => $data ] );
        
        $this->page_title = sprintf( '%s:: %s', __( 'Galleries' ), __( 'editing' ) );
        array_push( $this->scripts, 'sortable', 'gallery' );
    }
}