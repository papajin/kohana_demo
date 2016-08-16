<?php defined('SYSPATH') OR die('No direct script access.');
/**
 * Abstract controller class for automatic templating.
 *
 * @package    Kohana
 * @category   Controller
 * @author     Kohana Team
 * @copyright  (c) 2008-2012 Kohana Team
 * @license    http://kohanaframework.org/license
 */
abstract class Controller_Template extends Controller {

	/**
	 * @var  View  page template
	 */
	public $template;

	/**
	 * @var  boolean auto render template
	 **/
	public $auto_render = TRUE;
        
	/**
	 * @var  int lifetime of cache. NULL sets default value from config.
	 **/
	public $cache_lifetime;

	/**
	 * Loads the template [View] object.
	 */
	public function before()
	{
		$this->template 
			OR $this->template = strtolower( $this->request->directory() ) . '/index';
        
		parent::before();
                
		View::bind_global( 'settings', $this->_settings );

		if ( $this->auto_render === TRUE )
		{
			// Load the template
			$this->template = View::factory( $this->template );
		}
                
	}

	/**
	 * Assigns the template [View] as the request response.
	 */
	public function after()
	{
            ! $this->auto_render OR $this->_page_cache = $this->cache_page();
            
            parent::after();
	}
        
	/**
	 * Caches page content if PRODUCTION environment and not admin area.
	 * Lifetime is configs default or overwriten in [action].
	 * 0 timelife restricts caching.
	 *
	 * @return string of the page content.
	 */
	public function cache_page()
	{
			$page = $this->template->render();

			// Page is going to be cached if not admin area and not restricted for caching
			if ( Kohana::PRODUCTION === Kohana::$environment
					AND strtolower( $this->request->directory() ) != 'admin'
					AND $this->cache_lifetime !== 0 )
			{
				$this->_cache->set ( strtolower( $this->request->uri() ), $page, $this->cache_lifetime );
			}

			return $page;
	}
}
