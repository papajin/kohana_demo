<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Model class of XML map 
 */
class Model_Xmlmap extends Model {
    private $map;
    private $path;
    private $pref;
    
    public function __construct()
    {
        $this->path = DOCROOT.'map.xml';

        if( ! file_exists( $this->path ) )
            copy (APPPATH . 'content' . DIRECTORY_SEPARATOR . 'defaults' . DIRECTORY_SEPARATOR . 'map.xml', $this->path );

        $this->map = simplexml_load_file( $this->path );
        $namespaces = $this->map->getDocNamespaces();
        $this->map->registerXPathNamespace( 'c', $namespaces[''] );
        $this->pref = URL::base( TRUE );
    }
    
    /**
     * Add entry to the sitemap
     * @param string $uri
     * @param string $date
     * @param bool $save index for wright the map to file (while map generation no file wrighting needed) 
     * @return bool 
     */
    public function addEntry ( $uri, $date = NULL, $save = TRUE, $is_url = FALSE )
    {
        if ( !$date )
            $date = date( 'Y-m-d' );
        
        $url = ( $is_url ) ? $uri : $this->pref . $uri;          // make full path if it is not yet
        
        if ( ! $this->entryExists( $url ) )
        {
            try
            {
                // create the node and add the data.
                $new_url_node = $this->map->addChild( 'url' );
                $new_url_node->addChild( 'loc', $url );
                $new_url_node->addChild( 'lastmod', $date );

                if ( $save )
                    $this->save();
            }
            catch (Exception $e)
            {
                Kohana::$log->add( LOG_ERR, $e->getMessage() );
                return FALSE;
            }
        }
        
        return TRUE;
    }
    
    public function removeEntry( $uri, $save = TRUE, $is_url = FALSE )
    {
        $url = ( $is_url ) ? $uri : $this->pref . $uri;          // make full path if it is not yet
        $res = TRUE;
        
        if ( $this->entryExists( $url ) )
        {
            try
            {
                // Find the node, convert it into a DOMElement and remove.
                // c: - namespace declared in the class constructor
                $node = $this->map->xpath( "//c:loc[.='$url']/parent::*" );

                $dom = dom_import_simplexml( $node[ 0 ] );
                $dom->parentNode->removeChild( $dom );

                if ( $save )
                    $res = $this->save();
            }
            catch ( Exception $e )
            {
                Kohana::$log->add( LOG_ERR, $e->getMessage() );
                $res = FALSE;
            }
        }
        
        return $res;
    }
    
    public function updateEntry( $uri, $date = NULL )
    {
        $url = $this->pref . $uri;          // get full path
        
        // Today's date used if no date passed.
        if ( !$date )
            $date = date( 'Y-m-d' );
        
        // Find the node and set new date
        $node = $this->map->xpath( "//c:loc[.='$url']/parent::*" );

        if( isset ( $node[ 0 ] ) )
            $node[ 0 ]->lastmod = $date;
        else
            $this->addEntry ( $uri, NULL, FALSE );
        
        return $this->save();
    }

    /**
     * Creating sitemap.
     * 1) Get all pages, all articles, all lessons etc.
     * 2) Merge the result arrays and extract urls out of all instances.
     * 3) Check if the links are actual and remove not valid once.
     * 4) Refill the map with new actual links.
     * 5) Save the file.
     */
    public function generateMap()
    {
        try
        {
            // Select all published articles and pages.
            // Lessons and other new stuff to be added upon needed.
            $pgs = ORM::factory( 'Page' )
                    ->where( 'status', '>', 0 )
                    ->find_all()->as_array();

            $articles = ORM::factory( 'Article' )
                    ->where( 'status', '>', 0 )
                    ->find_all()->as_array();
            
            // Place found URLs into the container array.
            $pages = [];
            foreach ( array_merge( $pgs, $articles ) as $value )
                array_push ( $pages, $value->make_full_url () );
            
            // Select whatever is already in the map...
            $urls = [];
            foreach ( $this->map->children() as $node )
               $urls[] = ( string )$node->loc;

            // Delete redundant links
            foreach ( array_diff( $urls, $pages ) as $url )
                $this->removeEntry( $url, FALSE, TRUE );

            // Add missing links.
            foreach ( array_diff( $pages, $urls ) as $uri )
                $this->addEntry ( $uri, NULL, FALSE, TRUE );

            // Save result into the file.
            return $this->save();
        }
        catch ( Exception $e )
        {
            Kohana::$log->add( LOG_ERR, $e->getMessage() );
            return FALSE;
        }
    }


    private function entryExists( $url )
    {
        return count( $this->map->xpath( "//c:loc[.='$url']" ) ) > 0;
    }
    
    private function save ()
    {
        return $this->map->asXML( $this->path );
    }
}
