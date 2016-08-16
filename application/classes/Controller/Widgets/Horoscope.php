<?php defined('SYSPATH') or die('No direct script access.');
/*
 * Widget "Horoscope for Today"
 */
class Controller_Widgets_Horoscope extends Controller_Widgets {

    // Widget template
    public $template = 'front/misc/front_misc_feedback';
    
    public function action_index()
    {
        $this->cache_lifetime = 0;
        // Get horoscops for yesterday, today and tomorrow
        $this->template->horos = ORM::factory('Term')->getActualHoroscops();

        // To strip bold text
        $patterns = array();
        $patterns[0] = '/<strong>.*?<\/strong>/i';
        $patterns[1] = '/<b>.*?<\/b>/i';
        $this->template->pattern = $patterns;
    }
}