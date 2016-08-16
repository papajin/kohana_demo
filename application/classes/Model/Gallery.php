<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Model class of galleries control (Galleries DB table) 
 */

class Model_Gallery extends ORM {

    protected $_has_many = [
        'slides' => [],
    ];

    public function rules(){
        return [
            'title' => [
                [ 'not_empty' ],
            ],
        ];
    }


    public function labels()
    {
        return array(
            'title'     => __( 'name' ),
            'thumb'     => __( 'Path to thumb' ),
            'caption'   => __( 'Caption' ),
        );
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
        ];
    }
    
    public function delete()
    {
        foreach( $this->slides->find_all() as $slide )
            $slide->delete();
        
        parent::delete();
    }
} 
