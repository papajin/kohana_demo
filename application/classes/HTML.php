<?php defined('SYSPATH') OR die('No direct script access.');

class HTML extends Kohana_HTML {
    /**
     * Wraps passed @blocks with node (div by default) with passed attributes.
     * 
     * Use: HTML::wrap(array('Some content', 'Another block'), array('class' => 'class1 class2', 'id' => 'the_id' ), 'article');
     * 
     * @param mixed $blocks content (string or array) to be wrapped with the node
     * @param array $attr ('class' => 'class1 class2', 'id' => 'the_id' )
     * @param string $node (div by default)
     * @return string
     */
    public static function wrap($blocks, $attr, $node = 'div')
    {
        if( is_array($blocks) )
            $blocks = implode('', $blocks);
        
        return '<'.$node.HTML::attributes($attr).'>'.$blocks.'</'.$node.'>';
    }
}
