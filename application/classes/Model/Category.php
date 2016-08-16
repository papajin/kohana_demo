<?php defined('SYSPATH') or die('No direct script access.');

class Model_Category extends ORM {

    protected $_has_many = [
        'articles' => [
            'model' => 'Article',
            'foreign_key' => 'category_id',
        ],
        'children' => [
            'model' => 'Category',
            'foreign_key' => 'parent_id',
            'far_key' => 'id',
        ]
    ];

    protected $_has_one = [
        'page' => [
            'model' => 'Page',
            'foreign_key' => 'id',
        ]
    ];
    
    protected $_belongs_to = [
        'parent' => [
            'model' => 'Category',
        ],
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
        return [
            'title'     => __( 'name' ),
            'parent_id' => __( 'parent category' ),
        ];
    }

    public function filters()
    {
        return [
            TRUE => [
                [ 'trim' ],
            ],
            'title' [
                [ 'strip_tags' ]
            ]
        ];
    }
    
    public function getCategories( $top, &$arr )
    {
        $arr[ $top ] = $this->where( 'parent_id', '=', $top )->find_all();
        
        foreach ( $arr[ $top ] as $c )
        {
            $this->getCategories ( $c->id, $arr );
            if ( ! $arr[ $c->id ] )
                unset ( $arr[ $c->id ] );
        }
    }
    
    public function listArticleCategories()
    {
        return $this->where( 'id', '!=', 3 )
                    ->and_where( 'parent_id', '!=', 3 )
                    ->find_all()->as_array( 'id', 'title' );
    }

    /**
     * Makes uri for the instance: page, if old cats and default route for new ones.
     * @param boolean $for_map - for xmlmap main page need no "/"
     * @return string uri
     */
    public function make_uri( $for_map = FALSE )
    {
        return ( in_array( $this->id, [ 2, 4, 6, 7, 8, 9 ] ) )
                ? ORM::factory( 'Page', $this->id )->make_uri( $for_map )
                : ( ( $this->id == 5 )
                    ?  ORM::factory( 'Page', 122 )->make_uri( $for_map )
                    : Route::get( 'default' )->uri( [ 'controller' => 'articles',
                                                      'action'     => $this->_object_name,
                                                      'id'         => $this->id  ] ) );
    }
} 
