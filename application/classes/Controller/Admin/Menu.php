<?php defined('SYSPATH') or die('No direct script access.');
/*
 * Menu Controller
 */
class Controller_Admin_Menu extends Controller_Admin {

    public function action_index() 
    {
        $classes = array(
                NULL => 'нет',
                'brand' => 'Брэнд',
                'divider' => 'Горизонтальный разделитель',
                'divider-vertical' => 'Вертикальный разделитель',
                'nav-header' => 'Подзаголовок',
            );
        
        $menu_path = Kohana::find_file('content/assets', 'menu', 'json');
        
        if ( !$menu_path )
        {
            $this->information [] = 'Рабочий файл меню отсутствует. Меню загружено из резервной копии.';
            $menu_path = Kohana::find_file('content/defaults', 'menu', 'json');
        }
        if ( !$menu_path )
        {
            $this->information = NULL;
            $this->error[] = 'Отсутствуют файлы меню - основной и резервная копия';
            $menu = NULL;
        }
        else
        {
            $menu = json_decode( file_get_contents($menu_path) );
        }
        
        $this->content[] = View::factory('admin/a_menu_index', array('classes'=>$classes, 'menu'=>$menu));
        
        // Заголовок страницы
        $this->page_title = 'Меню:: Главное';
        $this->scripts[] = 'media/js/bs.sortable.min.js';
    }
    
    public function action_restore()
    {
        if ( copy ( APPPATH.'content'.DIRECTORY_SEPARATOR.'defaults'.DIRECTORY_SEPARATOR.'menu.json', APPPATH.'content'.DIRECTORY_SEPARATOR.'assets'.DIRECTORY_SEPARATOR.'menu.json' ) )
            HTTP::redirect('admin/menu');
        else 
            $this->error[] = 'Не удалось восстановить файл меню из резервной копии. '.HTML::anchor('/admin/menu', 'Редактирование Главного меню');
    }
}