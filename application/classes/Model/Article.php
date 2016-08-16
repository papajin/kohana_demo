<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Model class of articles control (Articles DB table) 
 */

class Model_Article extends ORM {
    
    protected $_cacheable = TRUE;

    protected $_has_many = [
        'terms' => [
            'model' => 'Term',
            'through' => 'articles_terms',
            'foreign_key' => 'article_id',
        ],
        'children' => [
            'model' => 'Article',
            'foreign_key' => 'parent_id',
            'far_key' => 'id',
        ]
    ];
    
    protected $_belongs_to = [
        'category' => [],
        'parent' => [ 'model' => 'Article' ],
    ];

    public function rules(){
        return [
            'title' => [ [ 'not_empty' ] ],
            'order' => [ [ 'digit' ] ],
            'alias' => [
                [ 'not_empty' ],
                [ 'alpha_dash' ],
            ],
        ];
    }


    public function labels()
    {
        return [
            'title' => __( 'title' ),
            'alias' => __( 'alias' ),
            'intro' => __( 'intro' ),
        ];
    }

    public function filters()
    {
        return [
            TRUE            => [ [ 'trim' ] ],
            'title'         => [ [ 'strip_tags' ] ],
            'alias'         => [ [ [ $this, 'slug_it' ], [ ':value', $this->title ] ] ],
            'keywords'      => [ [ 'strip_tags' ] ],
            'description'   => [ [ 'strip_tags' ] ],
            'widget'        => [ [ 'intval' ] ]
        ];
    }

    public function has_children()
    {
        return (bool) $this->children->where( 'status', '>', 0 )->count_all();
    }
    
    public function get_children()
    {
        return array_map( 
                        [ $this, 'article_with_tags_as_array' ],
                        $this->children->order_by( 'order' )->find_all()->as_array()
                    );
    }
    
    /**
     * Adds relation to the terms table.
     * If no such a tag, it is going to be created and added to the article.
     * @param mixed $tag - id of the tag or array( 'name' => 'tag_name' ) to identify the tag.
     * @throws ORM_Validation_Exception object
     */
    public function add_tag( $tag )
    {
        $term = ORM::factory( 'Term', $tag );
        if ( ! $term->loaded() )
        {
            if ( empty( $tag[ 'name' ] ) )
                throw new ORM_Validation_Exception( '', new Validation([]), __( ':field must not be empty', [ ':field' => __( 'name' ) ] ) );
            else
                $term->addTag ( $tag[ 'name' ], $this->pk() );
        }
        else
        {
            $this->add( 'terms', $term );
            $term->taxonomy->count++;
            $term->taxonomy->save();
        }
    }
    
    /**
     * Removes relation to the terms table with term counter decrement.
     * @param mixed $tag - id of the tag or array('name'=>'tag_name') (or array('slug'=>'some_slug')) to identify the tag.
     */
    public function remove_tag( $tag )
    {
        $term = ORM::factory( 'Term', $tag );
        if ( $term->loaded() )
        {
            $this->remove( 'terms', $term );
            $term->taxonomy->count--;
            $term->taxonomy->save();
        }
    }

    /**
     * Overridden parent method.
     * Terms handling added.
     */
    public function delete() 
    {
        // Find all article tags, unlink them from the article and decrement their counters
        $tags = $this->terms->find_all();

        foreach ( $tags as $tag )
        {
            $this->remove( 'terms', $tag );
            $term = ORM::factory( 'Term', [ 'term_id' => $tag->term_id ] );
            $term->taxonomy->count--;
            $term->taxonomy->save();
        }

        parent::delete();
    }
    
    public function article_with_tags_as_array( $obj )
    {
        $tags = implode( ', ', $obj->terms->find_all()->as_array( 'term_id', 'name' ) );
        $res = $obj->as_array();
        $res[ 'tags' ] = $tags;
        
        return $res;
    }
    
    public function listArticles( $cat_id = NULL )
    {
        if ( $cat_id )
            $this->where ( 'category_id', '=', $cat_id );
        
        return $this->find_all()->as_array( 'id', 'title' );
    }

    /**
     * Articles marked for widget.
     * @param mixed $cnt int number of articles
     * @return array of ORM Article objects
     * @throws Kohana_Exception
     */
    public function widgets( $cnt = FALSE )
    {
        $widgets = $this->where( 'widget', '=', 1 )
                        ->where('status', '=', 1);

        if ( $cnt ) $widgets->limit( $cnt );

        return $widgets->find_all()->as_array();
    }

    /**
     * @return string article text either full (if exists) or intro.
     */
    public function content()
    {
        return ( $path = Kohana::find_file( 'content/articles', $this->id, '' ) )
            ? file_get_contents( $path )
            : nl2br( $this->intro );
    }

    /**
     * Shortening article content to $length chars adding "..." as ending.
     * Uses UTF8::strlen and UTF8::substr.
     * @papam int $length defaults to 400.
     * @return string comment excerpt.
     */
    public function short( $length = 400 )
    {
        $content = strip_tags( $this->content() );

        return ( $length * 1.1 >= UTF8::strlen( $content ) )
            ? $content
            : UTF8::substr( $content, 0, $length ) . '&hellip;';
    }

    /**
     * Image of article either assigned or first found in the content.
     * @param array $params containing class, alt, title and other image attributes
     * @return NULL|string image node or null, if nothing found
     */
    public function image( $params = [] )
    {
        $image = $this->image;

        if ( ! $image )
        {
            $res = [];
            preg_match( '/<img.+src=[\'"](?P<src>.+?)[\'"].*>/i', $this->content(), $res );
            empty( $res ) OR $image = $res[ 'src' ];
        }

        return ( $image ) ? HTML::image( $image, $params ) : $image;
    }
} 
