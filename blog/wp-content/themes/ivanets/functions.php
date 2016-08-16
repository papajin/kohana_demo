<?php
/**
 * @package WordPress
  * @subpackage Ivanets_Theme
 */

// Remove unnecessary...
remove_action('wp_head', 'rsd_link');
remove_action('wp_head', 'wlwmanifest_link');
remove_action('wp_head', 'index_rel_link');
remove_action('wp_head', 'wp_generator');

if ( function_exists( 'register_sidebar' ) )
{
	register_sidebar( [
        'id'            => 'sidebar-1',
		'before_widget' => '',
		'after_widget'  => '',
		'before_title'  => '<h3 class="title">',
		'after_title'   => '</h3><div class="separator"></div>'
	] );
}

function ivanets_load_theme_textdomain() {
    load_theme_textdomain( 'ivanets', FALSE, basename( dirname( __FILE__ ) ) . '/languages/' );
}
add_action( 'after_setup_theme', 'ivanets_load_theme_textdomain' );

if ( ! function_exists( 'ivanets_comment' ) ) :
/**
 * Template for comments and pingbacks.
 */
function ivanets_comment( $comment, $args, $depth ) {
	$GLOBALS[ 'comment' ] = $comment;
	switch ( $comment->comment_type ) :
		case 'pingback' :
		case 'trackback' :
	?>
	<div class="post pingback">
		<p><?php _e( 'Pingback:', 'ivanets' ); ?> <?php comment_author_link(); ?><?php edit_comment_link( __( 'Edit' ), '<span class="edit-link">', '</span>' ); ?></p>
	<?php
			break;
		default :
	?>
	<div <?php comment_class('comment clearfix'); ?> id="li-comment-<?php comment_ID(); ?>">
                <div id="comment-<?php comment_ID(); ?>" class="comment-avatar">
                    <?= get_avatar( $comment, 55 );?>
                </div>
                <div class="comment-content clearfix">
                  <h3><?= get_comment_author_link(); ?></h3>
                  <footer class="comment-meta">
                      <?php printf( '%s %s', 
                                    sprintf( '%s %s', get_comment_date(), get_comment_time() ),
                                    ( ( $comment->comment_approved == '0' ) ? '| ' . __( 'Your comment is awaiting moderation.' ): '') );
                            echo edit_comment_link( NULL, ' | <i class="icon-pencil"></i> '); ?>
                  </footer>
                  <div class="comment-body">
                    <?php comment_text(); ?>
                    <?php comment_reply_link( [ 'reply_text' => '<i class="icon-reply"></i>' . __( 'Reply' ), 'depth' => $depth, 'max_depth' => $args['max_depth'] ] ); ?><!-- .reply -->
                  </div>
                </div>
	<?php
			break;
	endswitch;
        echo '</div>';
}
        
endif; // ends check for ivanets_comment()

function the_breadcrumb() {

    echo '<ol class="breadcrumb">';
    echo '<li><i class="icon-home pr-10"></i><a href="/">' . __( 'Home' ) . '</a></li>';

    if ( is_category() )
    {
        global $cat;
        if ( cat_is_ancestor_of( 437, $cat ) )
            echo '<li>' . get_category_parents( $cat, TRUE, '</li><li>' ) . '</li>';
        else
            echo single_cat_title( '<li class="active">', FALSE ).'</li>';
    }
	elseif ( is_single() )
    {
        echo '<li>';
        the_category( '<li></li>' );
        echo '</li>';
	}
	echo '</ol>';
}

function ivanets_bookmarks() {
    $url = str_replace( '/blog', '', get_site_url() );
    $url .= filter_input( INPUT_SERVER, 'REQUEST_URI' );

    if ( is_category() )
        $title = single_cat_title( '', FALSE );
    elseif ( is_front_page() OR is_home() )
        $title = get_bloginfo( 'name', FALSE );
    else
        $title = wp_title( '', FALSE );

    echo '<div id="share">';
    echo '<ul class="social-links clearfix">';
    echo '<li class="facebook"><div class="fb-like" data-href="' . $url . '" data-layout="box_count" data-action="recommend" data-size="small" data-show-faces="false" data-share="true"></div></li>';
//    echo '<a id="fb_submit_link" href="https://www.facebook.com/plugins/like/connect?action=recommend&t=' . trim( $title ) . '&u=' . $url . '&social_plugin_action=recommend" rel="nofollow"><i class="icon-facebook"></i></a>';
    echo '<li class="googleplus"><a href="https://plus.google.com/share?url=' . $url . '" rel="nofollow"><i class="icon-gplus"></i></a></li>';
    echo '<li class="twitter"><a href="http://twitter.com/timeline/home/?status=' . $url . '" rel="nofollow"><i class="icon-twitter"></i></a></li>';
    echo '</ul></div>';
}


if ( ! function_exists( 'ivanets_log' ) ) {
    function ivanets_log ( $log )  {
        error_log( 'THIS IS THE START OF MY CUSTOM DEBUG' );
        if ( true === WP_DEBUG ) {
            if ( is_array( $log ) || is_object( $log ) ) {
                error_log( print_r( $log, true ) );
            } else {
                error_log( $log );
            }
        }
        error_log( 'THIS IS THE END OF MY CUSTOM DEBUG' );
    }
}

// customizing avatar to bootstrap media component
add_filter('comment_reply_link','ammend_reply_link_class');

function ammend_reply_link_class( $link ) {
    return str_replace('comment-reply-link', 'comment-reply-link btn btn-gray more pull-right', $link);
}   // end ammend_reply_link_class

add_filter( 'next_comments_link_attributes', 'comments_link_attributes' );
add_filter( 'previous_comments_link_attributes', 'comments_link_attributes' );

function comments_link_attributes() {
    return 'class="btn btn-secondary btn-sm"';
}

function ivanets_delete_front_page_cache()
{
    file_get_contents ( sprintf( '%s/ajax/refresh_front_page', str_replace( '/blog', '', get_site_url() ) ), false,
        stream_context_create (
            [ 'http' => [ 'method'  => 'GET'
                        , 'header'  => "X-Requested-With: XMLHttpRequest\r\n" ] ]
        )
    );
}
add_action( 'publish_post', 'ivanets_delete_front_page_cache' );
?>
