<?php defined('SYSPATH') or die('No direct script access.');
/*
 * Widget for displaying recent feedback from customer.
 */
class Controller_Widgets_Feedback extends Controller_Widgets {

    // Widget template
    public $template = 'front/misc/front_misc_feedback';
    
    public function action_index()
    {
        $this->template->comments = ORM::factory( 'Post', [ 'post_name' => 'references' ] )
                                        ->comments
                                            ->where( 'comment_approved', '=', 1 )
                                            ->where( 'comment_author_email', 'NOT IN', [ $this->_settings[ 'author_email' ], $this->_settings[ 'author_email_2' ], $this->_settings[ 'admin_email' ] ] )
                                            ->order_by( 'comment_date', 'DESC')
                                            ->limit( 5 )
                                            ->find_all()
                                            ->as_array();
    }
}