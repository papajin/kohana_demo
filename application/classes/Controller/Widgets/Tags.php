<?php defined('SYSPATH') or die('No direct script access.');
/*
 * Tags Widget for Article editing/creating
 */
class Controller_Widgets_Tags extends Controller_Widgets {

    // Widget template
    public $template = 'admin/w/w_tags';
    
    public function action_index()
    {
        /* Get all tags for article, make them buttons and push into array */
        $tags = [];
        $article_id = Request::initial()->param( 'id' );
        
        $article_tags = ORM::factory( 'Article', $article_id )->terms->find_all()->as_array();
        
        foreach ( $article_tags as $tag )
            $tags[] = HTML::wrap ( __( 'post_tag' )[ $tag->slug ], [ 'class' => 'btn label label-default m-r-1' ], 'span' );

        /* Get all tags, make them buttons and push into array */
        $taxonomy = ORM::factory( 'Taxonomy' )->where( 'taxonomy', '=', 'post_tag' )->find_all();
        
        foreach ( $taxonomy as $t )
            $tag_list[] = HTML::wrap ( __( 'post_tag' )[ $t->term->slug ], [ 'class' => 'btn label label-default m-r-1' ], 'span' );

        // Remove already used tags from all tags list and sort both arrays alphabetically.
        $tag_list = array_diff( $tag_list, $tags );
        sort( $tag_list, SORT_STRING );
        sort( $tags, SORT_STRING );
        
        $this->template->tag_list = HTML::wrap( $tag_list, [ 'id' => 'source_tags' ] );
        $this->template->article_id = $article_id;
        $this->template->tags = HTML::wrap( $tags, [ 'id' => 'attached_tags', 'class' => 'p-a-1', 'style' => 'background-color: #f7f7f9' ] );
        $this->cache_lifetime = 0;
    }
}