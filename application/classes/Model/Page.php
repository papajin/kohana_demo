<?php defined('SYSPATH') or die('No direct script access.');

class Model_Page extends ORM {
    
    protected $_cacheable = TRUE;
    
    protected $_has_many = [
        'children' => [
            'model'         => 'Page',
            'foreign_key'   => 'parent_id',
            'far_key'       => 'id'
        ]
    ];
    
    protected $_belongs_to = [
        'parent' => [ 'model' => 'Page' ],
        'category' => []
    ];
    
    public function rules()
    {
        return [
            'title'     => [ [ 'not_empty' ] ],
            'alias'     => [ [ 'alpha_dash' ] ],
            'order'     => [ [ 'digit' ] ],
            'parent_id' => [ [ 'digit' ] ]
        ];
    }

    public function labels()
    {
        return [
            'title'     => __( 'title' ),
            'alias'     => __( 'alias' ),
            'order'     => __( 'order' ),
            'parent_id' => __( 'parent page' )
        ];
    }

    public function filters()
    {
        return [
            TRUE    => [ [ 'trim' ] ],
            'status' => [ [ 'ORM::int', [ ':value' ] ] ]
        ];
    }

    public function menu()
    {
        $needle = 'lang="' . I18n::lang() . '">';
        $start = UTF8::strpos ( $this->menu, $needle );

        if ( $start === FALSE ) return strip_tags( $this->menu );

        $start += UTF8::strlen( $needle );

        $length = UTF8::strpos ( $this->menu, '</', $start ) - $start;
        return UTF8::substr( $this->menu, $start, $length );
    }
    
    public function listPages()
    {
        return $this->find_all()->as_array( 'id', 'title' );
    }
    
    public function has_children()
    {
        return (bool)$this->children->where( 'status', '>', 0 )->count_all();
    }
} 
