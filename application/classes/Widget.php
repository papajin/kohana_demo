<?php defined('SYSPATH') or die('No direct script access.');
/*
 * Class to manage widgets
 */

class Widget {

    protected $_controllers_folder  = 'Widgets';    // Folder with widgets
    protected $_config_filename     = 'widgets';    // Widgets config file
    protected $_route_name          = 'widgets';    // File of widget default settings
    protected $_params              = [];           // Array of params
    protected $_widget_name;                        // Widget name (the controller)


     /*
      * Widget call Widget::factory('widget_name')->render();
      * @param   string  Widget name
      * @param   array   Array of the parameters transferred
      * @param   string  Route name of the widget
     */
    public static function factory($widget_name, array $params = NULL, $route_name = NULL)
    {
        return new Widget($widget_name, $params, $route_name);
    }



    /*
     * Widget call Widget::load('widget_name', array('param' => 'val'), 'route_name')
     * OR Widget::load(['widget_name1', 'widget_name2', ...], [ ['param' => 'val'], ['param' => 'val'], ...], ['route_name1', 'route_name2', ... ])
     * @param   mixed  Widget name
     * @param   array   Array of the parameters transferred
     * @param   string  Route name of the widget
     */
    public static function load($widget_name, array $params = NULL, $route_name = NULL)
    {
        if ( is_array( $widget_name ) )
        {
            $widgets = [];
            for ( $i = 0; $i < count( $widget_name ); $i++ )
            {
                $widgets[] = self::load( $widget_name[ $i ], ( $params[ $i ] ?? NULL ), ( $route_name[ $i ] ?? NULL ) );
            }
            return $widgets;
        }
        else
        {
            $widget = new Widget($widget_name, $params, $route_name);
            return $widget->render();
        }
    }


    public function __construct($widget_name, array $params = NULL, $route_name = NULL)
    {
        if ($params != NULL)
        {
            $this->_params = $params;
        }

        if ($route_name != NULL)
        {
            $this->_route_name = $route_name;
        }

        $this->_widget_name = $widget_name;
    }

    public function render()
    {
        // Getting the active controller and the action
        $controller = strtolower( Request::initial()->controller() );
        $widget_name = strtolower( $this->_widget_name );
        $action = strtolower( Request::initial()->action() );
        

        // Getting config settings
        $widget_config = Kohana::$config->load("$this->_config_filename.$widget_name.$controller");
        
        // Restrict the widget display in the actions that listed in config settings
        if ( $widget_config != NULL )
        {
            if ( in_array( $action, $widget_config ) OR in_array( 'all_actions', $widget_config ) )
            {
               return NULL;
            }
        }

       $this->_params[ 'controller' ] = $this->_widget_name;
       $url = Route::get( $this->_route_name )->uri( $this->_params );

       return Request::factory( $url )->execute();
    }

}
