<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Model class of slides control (Slides DB table) 
 */

class Model_Slide extends ORM {
    
    protected $_belongs_to = [
        'gallery' => [],
    ];

    public function rules(){
        return [
            'gallery_id' => [
                [ 'not_empty' ],
            ],
            'path' => [
                [ 'not_empty' ],
            ],
        ];
    }


    public function labels()
    {
        return [
            'gallery_id'    => __( 'Gallery id' ),
            'title'         => __( 'title' ),
            'path'          => __( 'Path to image' ),
            'thumb'         => __( 'Path to thumb' ),
            'caption'       => __( 'Caption' ),
        ];
    }

    public function filters()
    {
        return [
            TRUE => [
                [ 'trim' ],
            ],
            'title' => [
                [ 'strip_tags' ],
            ],
//            'thumb' => array(
//                array('str_replace', array('/images/noimage.jpg', '', ':value')),
//            ),
//            'path' => array(
//                array('str_replace', array('/images/noimage.jpg', '', ':value')),
//            ),
        ];
    }
    
    public function delete()
    {
        $folder = rtrim( DOCROOT, DIRECTORY_SEPARATOR );
        
        if ( $this->thumb AND file_exists( $folder . $this->thumb ) )
                unlink ( $folder . $this->thumb );

        if ( $this->path AND file_exists( $folder . $this->path ) )
                unlink ( $folder . $this->path );
        
        parent::delete();
    }
} 
