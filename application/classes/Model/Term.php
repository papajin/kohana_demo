<?php defined('SYSPATH') or die('No direct script access.');
class Model_Term extends ORM {

    protected $_primary_key = 'term_id';
    protected $_has_many =[
        'posts' => [
            'model'         => 'Post',
            'through'       => 'term_relationships',
            'foreign_key'   => 'term_taxonomy_id',
            'far_key'       => 'object_id'
        ],
        'articles' => [
            'model' => 'Article',
            'through' => 'articles_terms',
            'foreign_key' => 'term_id',
        ],
    ];
    protected $_belongs_to = [
        'taxonomy' => [
            'model'         => 'Taxonomy',
            'foreign_key'   => 'term_id'
        ]
    ];

    public function addTag ($tag, $article_id = FALSE)
    {
        try
        {
            $this->values( ['name' => $tag,
                            'slug' => TextHelper::transliterate ( $tag ),
                            'term_group' => 0] )->save();
            
            $cnt = 0;
            if ( $article_id )
            {
                $this->add( 'articles', $article_id );
                $cnt = 1;
            }

            $this->taxonomy->values( [  'term_id'=>$this->pk(),
                                        'taxonomy'=>'post_tag',
                                        'description'=>'',
                                        'parent'=>0,
                                        'count'=>$cnt ] )->save();
        }
        catch ( Exception $e )
        {
            return $e->getMessage();
        }
 
        return $this->pk();
    }
    
    /**
     * Get all articles tagged with @tag.
     * @param string $tag - slug of tag
     * @return array of ORM objects
     */
    public function getArticlesByTag ( $tag )
    {
        $t = $this->where( 'slug', '=', $tag )->find();
        
        return ( $t->loaded() )
                    ? $t->articles->where( 'status', '=', 1 )->find_all ()
                    : NULL;
    }


    /**
     * @return object of ORM Post model with last published post under $rubric topic.
     * @param mixed $rubric int rubric id | string rubric slug or name if $is_slug set FALSE.
     * @param bool $is_slug FALSE would mean that $rubric is name.
     * @throws Kohana_Exception
     */
    public function lastPost( $rubric, $is_slug = TRUE )
    {
        $field = ( $is_slug ) ? 'slug' : 'name';

        if ( is_int( $rubric ) AND $is_slug ) // get sure that no name-digit asked for
        {
            $field = 'term_id';
        }

        return $this->where( $field, '=', $rubric )
                                    ->find()
                                        ->posts
                                            ->where( 'post_status', '=', 'publish' )
                                            ->order_by( 'post_date', 'DESC' )
                                            ->find();
    }

    /**
     * @return object of ORM Post model with actual month horoscope or the last one.
     * @throws Kohana_Exception
     */
    public function thisMonthHoroscope ()
    {
        // One month ahead is usually ok. Let Check 3 last posts of the category.
        $limit = 3;
        $hors = $this->where( 'term_id', '=', 8 )
                    ->find()
                        ->posts
                            ->where( 'post_status', '=', 'publish' )
                            ->order_by( 'post_date', 'DESC' )
                            ->limit( $limit )
                            ->find_all()
                            ->as_array();

        /* Further on supposed uk language content only */
        $months =  i18n::get( 'months', 'uk' );
        $month_year = sprintf( '%s %d', UTF8::strtolower( $months[ date( 'n' ) ] ), date( 'Y' ) );

        for( $i = 0; $i < $limit; $i++ )
        {
            if ( strpos( UTF8::strtolower( $hors[ $i ]->post_title ), $month_year ) !== FALSE )
            {
                return $hors[ $i ];
            }
        }

        return $hors[ 0 ];
    }
    
    /**
     * Return actual post objects: Today's, of Yesterday and of Tomorrow, if exist.
     * Or just the last ones.
     * @param int $cnt number of posts to return (1 by default).
     * @return mixed object or array of ORM objects
     */
    public function getActualHoroscopes( $cnt = 1 )
    {
        // Number of last posts in category to check (the bigger the more server load but less error).
        $limit = 20;

        // Get horoscopes pool for testing
        $hors = $this   ->where( 'term_id', '=', 9 )
                        ->find()
                            ->posts
                                ->where( 'post_status', '=', 'publish' )
                                ->order_by( 'post_date', 'DESC' )
                                ->limit( $limit )
                                ->find_all()
                                ->as_array();

        $today = -1;
        for( $i = 0; $i < $limit; $i++ )
        {
            if ( $this->isActualHoro( $hors[ $i ] ) )
            {
                $today = $i;
                break;
            }
        }

        // Request for single horoscope
        if ( $cnt == 1 )
        {
            return ( $today >= 0 )
                    ? $hors[ $today ]
                    : reset( $hors );
        }

        /* Seems like one post is not enough */
        // If no actual post, the last ones do.
        $offset = $today - floor( $cnt / 2 );
        $offset >= 0 OR $offset = 0;
        
        return array_slice( $hors, $offset, $cnt );
    }

    /**
     * Tests if the passed post object is actual horoscope for today.
     * @param $hor ORM object of Post model to test
     * @return bool
     */
    private function isActualHoro( $hor )
    {
        // Further on month string could be Ukrainian only
        $months = i18n::get( 'months_to_number', 'uk' );
//                  Arr::merge( i18n::get( 'months_to_number', 'uk' ),
//                              i18n::get( 'months_to_number', 'ru' ) );

        // Today's date in handy-to-compare format
        $today = date( 'Ymd' );

        // Time span is set by start and end dates, separated by dash
        $dates = preg_split( '/(?:-|–|(?:&ndash;)|(?:&mdash;))/', $hor->post_title );

        // Day, month, year (or some of them) of first date separated with space
        $date1 = explode(' ', trim( $dates[ 0 ] ) );

        // Add leading zero for day, if need be
        if ( strlen( $date1[ 0 ] ) == 1 )
            $date1[ 0 ] = '0' . $date1[ 0 ];

        // Just for any case...
        ! isset( $date1[ 1 ] ) OR $date1[ 1 ] = UTF8::strtolower( $date1[ 1 ] );

        // Either we have month name or it's an empty string
        $first_month = ( isset( $date1[ 1 ] ) AND ! empty( $months[ $date1[ 1 ] ] ) )
                                ? $months[ $date1[ 1 ] ]
                                : '';

        // If we do have end date, then it is also separated with spaces
        if ( count( $dates ) > 1 ) {
            $date2 = explode( ' ', trim( $dates[ 1 ] ) );

            // Add leading zero for day, if need be
            if ( strlen( $date2[ 0 ] ) == 1 )
                $date2[ 0 ] = '0' . $date2[ 0 ];

            ! isset( $date2[ 1 ] ) OR $date2[ 1 ] = UTF8::strtolower( $date2[ 1 ] );

            // Either we have month name or it's an empty string
            $second_month = ( isset( $date2[ 1 ] ) AND ! empty( $months[ $date2[ 1 ] ] ) )
                ? $months[ $date2[ 1 ] ]
                : '';

            $first_month OR $first_month = $second_month;

            /* If start date has only 1 part, then month and year are same to those of end date */
            $d1_cnt = count( $date1 );

            if ( $d1_cnt == 1 )
            {
                $date1[] = $date2[ 1 ];
                $date1[] = $date2[ 2 ];
            }
            elseif ( $d1_cnt == 2 )
            {
                /* Usually year is same, unless this is around New Year.
                   Sometimes Olya misses a year part for start date, hence... */
                $date1[] = ( $first_month > $second_month )
                            ? $date2[ 2 ] - 1
                            : $date2[ 2 ];
            }


            
            // Horoscope is actual, if today is between start and end dates...
            return  ( strcmp( $today, $date1[ 2 ] . $first_month . $date1[ 0 ] ) >= 0 )
                    AND ( strcmp( $today, $date2[ 2 ] . $second_month . $date2[ 0 ] ) <= 0 );
        }

        // ...or of same date, if no time span
        return ( strcmp( $today, $date1[ 2 ] . $first_month . $date1[ 0 ] ) == 0 );
    }

    /**
     * @return string categori uri
     */
    public function make_uri( $for_map = false )
    {
        return sprintf( 'blog/cat/%s/', $this->slug );
    }

//    public function essays()
//    {
//        $essay_parent = 437;
//
//        return $this->with( 'taxonomy' )
//                    ->where( 'taxonomy.parent', '=', $essay_parent )
//                    ->find_all()
//                    ->as_array();
//    }

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
