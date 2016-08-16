<?php defined('SYSPATH') or die('No direct script access.');
class Model_Post extends ORM {

    protected $_primary_key = 'ID';
    
    protected $_has_many = [
        'terms' => [
            'model'         => 'Term',
            'through'       => 'term_relationships',
            'foreign_key'   => 'object_id',
            'far_key'       => 'term_taxonomy_id',
        ],
	    'comments' => [ 'foreign_key' => 'comment_post_ID', ],
    ];

    /**
     * Shortening Post content to $length chars adding "..." as ending.
     * Uses UTF8::strlen and UTF8::substr.
     * @papam int $length defaults to 400.
     * @return string comment excerpt.
     */
    public function short( $length = 400 )
    {
        $post_content = strip_tags( $this->post_content );

        return ( $length * 1.1 >= UTF8::strlen( $post_content ) )
            ? $post_content
            : UTF8::substr( $post_content, 0, $length ) . '&hellip;';
    }

    /**
     * For simplicity reason default WP link style used (not "nice uri"). Redirect should do. 
     * @return string post uri
     */
    public function make_uri( $for_map = false )
    {
        return sprintf( '/blog/?p=%d', $this->ID );
    }

    /**
     * @param array $rubrics to check for latest post published.
     * @param bool $is_slug default TRUE means that array of slugs passed; names otherwise.
     * @return ORM Post object.
     * @throws Kohana_Exception
     */
    public function lastFromRubrics(array $rubrics, $is_slug = TRUE )
    {
        $field = ( $is_slug ) ? 'slug' : 'name';
        is_string( $rubrics[ 0 ] ) OR $field = 'term_id';

        // Make query for the ID of latest post in all passed rubrics.
        $post_id = DB::select( [ 'p.ID', 'id'] )
            ->from( [ $this->table_name(), 'p' ] )
            ->join( [ 'term_relationships', 'rel' ] )->on('p.ID', '=', 'rel.object_id')
            ->join( [ 'terms', 't' ] )->on( 't.term_id', '=', 'rel.term_taxonomy_id' )
            ->where( 'p.post_status', '=', 'publish' )
            ->where( 't.'.$field, 'IN', $rubrics )
            ->order_by( 'p.post_date', 'DESC' )
            ->limit( 1 );

        // ... and pass it as a parameter of where filter.
        return $this->where( 'ID', '=', DB::expr( $post_id->execute()->get('id') ) )->find();
    }
    
    public function essays()
    {
        $essay_parent = 437;

        $posts = DB::select( [ DB::expr( "MAX(:t)", [ ':t' => DB::expr( $this->_db->table_prefix() . "r.object_id") ] ), 'id' ], [ 't.term_id', 'term' ]  ) // For unknown reason table prefix gets missing in MAX function (in COUNT it works OK)
                ->from( $this->table_name() )
                    ->join( [ 'term_relationships', 'r' ] )->on( 'posts.ID', '=', 'r.object_id' )
                    ->join( [ 'terms', 't' ] )->on( 't.term_id', '=', 'r.term_taxonomy_id' )
                    ->join( [ 'term_taxonomy', 'tx' ] )->on( 't.term_id', '=', 'tx.term_id' )
                ->where( 'tx.parent', '=', $essay_parent )
                ->where( 'posts.post_status', '=', 'publish' )
                ->group_by( 't.term_id' );

        return $this->where( 'ID', 'IN', $posts->execute()->as_array( NULL, 'id') )
                    ->order_by( 'post_date', 'DESC')
                    ->find_all();
    }
}
