<?php
/**
 * Base stuff for templates (styles, scripts and common blocks)
 * User: Ihor
 * Date: 27.04.2016
 * Time: 8:46
 */
trait Controller_Base {
    // @vars
    protected $styles = [], $scripts = [], $content = [];

    /**
     * Binds styles and scripts to the template, passes lists of styles and scripts
     * into the template. Can be used in either action (i.e. before, after or action itself).
     */
    protected function template_init() {
        $this->template->bind( 'styles', $this->styles );
        $this->template->bind( 'scripts', $this->scripts );
        $this->template->bind( 'content', $this->content );

        $this->template->style_list = Kohana::$config->load( 'styles' );
        $this->template->script_list = Kohana::$config->load( 'scripts' );
    }
}