<?php defined('SYSPATH') OR die('No direct script access.');

class ORM extends Kohana_ORM {
    
    protected $_cacheable = FALSE;      // TRUE if model requires cache and xml map update
    
    protected $_cache_clear = FALSE;    // Cache clear flag (TRUE if cache cleared) for loops
    protected $_xmlmap_updated = FALSE; // xml map update flag (TRUE if map is updated) for loops
        
    public function delete()
    {
        $this->delete_cache();
        $this->update_sitemap();
        
        parent::delete();
    }
    
    public function save( Validation $validation = NULL )
    {
        parent::save( $validation );
        
        $this->delete_cache();
        $this->update_sitemap();
        
        return $this;
    }

    public function json()
    {
        return json_encode(  $this->as_array() ); //, JSON_UNESCAPED_UNICODE
    }

    /**
     * Deletes cache within default config and ajax for both languages (or as per param) (not for widgets).
     * To ensure function called once for multiple columns update @_cache_clear flag is used
     * @param array $langs languages cache config to be used. All languages cleared by default.
     * @return bool result TRUE on success and FALSE on failure.
     */
    public function delete_cache( $langs = [ 'uk', 'ru' ] )
    {
        if( ! $this->_cache_clear AND $this->_cacheable )
        {
            $uri = $this->make_uri();       // Cache id
            $uri == '/' OR $uri = trim( $uri, '/' );

            $this->_cache_clear =  TRUE;

            // Delete cache and set clear cache flag.
            foreach ( $langs as $lang )
                $this->_cache_clear = ( $this->_cache_clear
                                    AND Cache::instance( $lang . '_pages' )->delete( $uri ) );

            // Set cache config to ajax and delete cache there. Result merged into the flag.
            $this->_cache_clear = ( $this->_cache_clear
                                    AND Cache::instance( 'ajax' )->delete( $uri ) );
        }

        return $this->_cache_clear;
    }
    
    /**
     * Updates sitemap file (map.xml) for cacheable pages.
     * To ensure function called once for multiple columns update 
     * @_xmlmap_updated flag is used (xmlmap is resources consuming).
     */
    public function update_sitemap()
    {
        if( ! $this->_xmlmap_updated AND $this->_cacheable )
        {
            $this->_xmlmap_updated = ( $this->status )
                    ? Model::factory( 'Xmlmap' )->updateEntry( $this->make_uri( TRUE ) )
                    : Model::factory( 'Xmlmap' )->removeEntry( $this->make_uri( TRUE ) );
        }
    }
    
    /**
     * Makes uri for the instance.
     * @param boolean $for_map - for xmlmap main page need no "/"
     * @return string uri
     */
    public function make_uri( $for_map = FALSE )
    {
        $controller = ( $this->_object_name == 'page' )
                        ? NULL
                        : Inflector::plural( $this->_object_name );
        
        $uri = Route::get( 'articles' )->uri( [ 'controller' => $controller,
                                                'id'         => $this->alias  ] );
        
        if ( $uri == '/index.html' )
             $uri = ( $for_map ) ? '' : '/';
        
        return $uri;
    }
    
    /**
     * Makes full url for the instance.
     * @return string uri
     */
    public function make_full_url()
    {
        if ( $this->_object_name == 'page' AND $this->alias == 'index' )
            return URL::base( TRUE );
        
        $controller = ($this->_object_name == 'page') ? NULL : Inflector::plural($this->_object_name);
        
        return Route::url( 'articles', array(   'controller' => $controller,
                                                'id'         => $this->alias  ), TRUE);
    }
    
    public function uniq_alias( $value, $field )
    {
        $res =  ORM::factory( $this->_object_name )
                            ->where( $field, '=', $value )
                            ->and_where( $this->_primary_key, '!=', $this->pk() )
                            ->count_all();
        return !( bool )$res;
    }
    
    public function slug_it ( $slug, $name )
    {
        $field = ($this->_object_name != 'term') ? 'alias' : 'slug';
        $slug = ( $slug ) ? $slug : self::translit($name);
        $i = 1;
        while ( ! $this->uniq_alias( $slug, $field ) )
        {
            if ( ! preg_match('/\d+$/', $slug) )
            {
                $slug .= '-'.$i;
            }
            $slug = preg_replace('/\d+$/', $i++, $slug);
        }

        return $slug;
    }
    
    /**
     * Cyrillic to latin string translitaration
     * @param string $str
     * @return string 
     */
    public static function translit($str)
    {
        $aAlphabet = array (
            "а" => "a", "б" => "b", "в" => "v", "г" => "g", "д" => "d", "е" => "e",
            "ё" => "e", "ж" => "zh", "з" => "z", "и" => "i", "й" => "j", "к" => "k",
            "л" => "l", "м" => "m", "н" => "n", "о" => "o", "п" => "p", "р" => "r",
            "с" => "s", "т" => "t", "у" => "u", "ф" => "f", "х" => "h", "ц" => "c",
            "ч" => "ch", "ш" => "sh", "щ" => "sch", "ъ" => "", "ь" => "", "э" => "e",
            "ы" => "y", "ю" => "yu", "я" => "ya", " " => "_", 'і' => 'i', 'ї' => 'yi',
                                    );
        $str = strtr( mb_strtolower($str,'UTF-8'), $aAlphabet );
        $str = preg_replace('~[^-a-z0-9_]+~u', '', $str);

        return trim($str);
    }

    /**
     * Build almafont icon '<i class="alma-sun-alt"></i>'
     * @param string $ico icon class extension after hyphen (for example, moon or moon-alt). Default is "sun".
     * @return string icon <i /> node.
     */
    public function alma_icon( $ico = 'sun' )
    {
        return HTML::wrap( '', [ 'class' => sprintf( 'alma-%s', $ico ) ], 'i' );
    }

    public static function int( $val )
    {
        return ( int ) $val;
    }
}
