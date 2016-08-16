<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Header Topper Controller
 *
 * @author Игорь
 */
class Controller_Widgets_Topper extends Controller_Widgets {
    
    public $template = 'front/tplt/front_tplt_topper';    // widget template
    
    public function action_index()
    {
        $this->template->new_lang = ( I18n::lang() === 'uk' ) ? 'ru' : 'uk'; 
    }
}