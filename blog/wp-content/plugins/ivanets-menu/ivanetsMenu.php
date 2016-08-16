<?php
/*
Plugin Name: Ivanets Custom Menu
Plugin URI: http://ihor.ivanets.com/
Description: desplays custom widgets, taken from the parent site; prepares some important constants for locale and theme.
Version: 2.1 (2016)
Author: Ihor Ivanets, UA
Author URI: http://ihor.ivanets.com/
*/

/*
Copyright 2013  Ihor Ivanets, UA  (http://ihor.ivanets.com/)
*/

class ivanetsMenu {
    private static $widgets;                    // Container for templates
    private $uri = 'WPHelper/tpltBlox/' . WPLG; // Parent site URI that triggers generation of needed templates.
    private $wp_dir = 'blog';                   // Path to WP directory relative to the site root.
    private $path = KOHANA_APP . 'content/assets/' . WPLG . '/blog_widgets_cache.json'; // Path to the file, which stores generated templates.
    
    public function __construct ()
    {
        if ( ! self::$widgets )
        {
            if ( is_file( $this->path ) )
            {
                self::$widgets = json_decode( file_get_contents( $this->path ) );
            }
            else
            {
                $url = substr( get_site_url(), 0, strpos( get_site_url(), $this->wp_dir ) ) . $this->uri;

                if ( self::get_url_contents( $url ) )
                {
                    $this->__construct ();
                }
            }
        }
    }
    
    public function get_widget( $w_name )
    {
        $w_name = strtolower( $w_name );

        if ( empty( self::$widgets ) OR ! property_exists( self::$widgets, $w_name ) )
                return NULL;
        
        return html_entity_decode( self::$widgets->$w_name );
    }
    
    public static function echo_widget( $w_name )
    {
        self::$widgets OR new ivanetsMenu();
        $w_name = strtolower( $w_name );

        echo ( empty( self::$widgets ) OR ! property_exists( self::$widgets, $w_name ) )
                ? ''
                : html_entity_decode( self::$widgets->$w_name );
    }

    public static function get_url_contents ( $url )
    {
        $crl = curl_init();
        if ( !$crl )
        {
            return file_get_contents( $url );
        }
        else
        {
            $timeout = 5;
            curl_setopt ( $crl, CURLOPT_URL, $url );
//            curl_setopt ($crl, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt ( $crl, CURLOPT_CONNECTTIMEOUT, $timeout );
            $ret = curl_exec( $crl );
            curl_close( $crl );
            return $ret;
        }
    }
    
    /**
     * Set front end interface language according to WPLANG
     * @param type $lang
     * @return type
     */
    public static function set_locale( $lang ) {
        if ( ! is_admin() ) $lang = WPLANG;
        
        return $lang;
    }
}

/**
 * Ukrainian supposed default language unless Russian set by $_GET param or cookie.
 * For theme templates WPLANG, WPLG, PARENTROOT and KOHANA_APP constants needed.
 */
class ivanetsPrepare {
    
    public function __construct ()
    {
        defined( 'WPLANG' ) OR define ( 'WPLANG', $this->get_language() );
        defined( 'WPLG' ) OR define ( 'WPLG', substr ( WPLANG, 0, 2 ) );
        defined( 'PARENTROOT' ) OR define( 'PARENTROOT', substr( ABSPATH, 0, strpos( ABSPATH, 'blog') ) );
        defined( 'KOHANA_APP' ) OR define( 'KOHANA_APP', PARENTROOT . 'application' . DIRECTORY_SEPARATOR );
    }
    
    /**
     * Check cookie and $_GET for lang parameter, which is called to set front end language.
     */
    private function get_language ()
    {
        $lang = filter_input ( INPUT_COOKIE, 'lang', FILTER_CALLBACK, [ 'options' => [ $this, 'filter_lang' ] ] );
	$new_lang = filter_input ( INPUT_GET, 'lang', FILTER_CALLBACK, [ 'options' => [ $this, 'filter_lang' ] ] );
	if ( $new_lang ) {
		$lang = $new_lang;
		setcookie( "lang", substr ( $lang, 0, 2 ), time() + 3600 * 24 * 365, '/' );
	}
	
	return $lang ?? 'uk';
    }
    
    /**
     * Check if parameter fits.
     * For the moment we can choose between uk and ru only.
     * @param string $val to be tested.
     * @return string properly formated for .mo packages available.
     */
    private function filter_lang( $val )
    {
        return ( $val === 'ru' ) ? 'ru_RU' : ( ( $val === 'uk' ) ? $val : NULL );
    }
    
    /**
     * Initialization of theme pre-definings.
     * Since tied to locale trigger, needs $lang parameter.
     * @param string $lang default WP language, set in admin panel.
     * @return string new language code.
     */
    public static function init ( $lang )
    {
        new ivanetsPrepare();
        is_admin() OR $lang = WPLANG;
        
        return $lang;
    }
}

add_filter( 'locale', [ 'ivanetsPrepare', 'init' ] );