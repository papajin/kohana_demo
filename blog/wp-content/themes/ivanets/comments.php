<?php
/**
 * @package WordPress
 * @subpackage Ivanets_Theme
 */
 
//$comments = array_reverse($comments);
if ( post_password_required() ) : ?>
<p><?php _e('Enter your password to view comments.'); ?></p>
<?php return; endif; ?>
<!-- comment start -->
<div class="comments">
    <h2 id="title_comments" class="title"><?php comments_number(); ?></h2>
    <?php if ( have_comments() ) : ?>
        <?php
        
            /* Loop through and list the comments. Tell wp_list_comments()
             * to use ivanets_comment() to format the comments.
             * See ivanets_comment() in functions.php for more.
             */
            wp_list_comments( [ 'callback' => 'ivanets_comment' ] );
        ?>

        <?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : // are there comments to navigate through ?>
        <nav id="comment-nav-below" class="clearfix">
            <div class="nav-previous pull-left"><?php previous_comments_link( '<i class="icon-angle-double-left m-r-1"></i>' . __( 'Older comments' ) ); ?></div>
            <div class="nav-next pull-right"><?php next_comments_link( __( 'Newer comments' ) . '<i class="icon-angle-double-right m-l-1"></i>' ); ?></div>
        </nav>
        <?php endif; // check for comment navigation ?>

    <?php endif; ?>
</div>
<?php if ( comments_open() ) : ?>
        <div id="postcomment" class="comments-form">
            <?php if ( get_option('comment_registration') && !$user_ID ) : ?>
            <p><?php printf(__('You must be <a href="%s">logged in</a> to post a comment.'), get_option('siteurl')."/wp-login.php?redirect_to=".urlencode(get_permalink()));?></p>
            <?php else : ?>
            <?php 
            $commenter = wp_get_current_commenter();
            $req = get_option( 'require_name_email' );
            $aria_req = ( $req ? " aria-required='true'" : '' );

            $fields =  array(
                'author' =>
                  '<div class="form-group"><label for="author">' . __( 'Name' ) . '</label> ' .
                  ( $req ? '<span class="text-danger">*</span>' : '' ) .
                  '<div class="input-group"><input id="author" name="author" type="text" class="form-control" value="' . esc_attr( $commenter['comment_author'] ) .
                  '"' . $aria_req . ' /><span class="input-group-addon"><i class="icon-user"></i></span></div></div>',

                'email' =>
                  '<div class="form-group"><label for="email">' . __( 'Email' ) . '</label> ' .
                  ( $req ? '<span class="text-danger">*</span>' : '' ) .
                  '<div class="input-group"><input id="email" name="email" type="email" class="form-control" value="' . esc_attr(  $commenter['comment_author_email'] ) .
                  '"' . $aria_req . ' /><span class="input-group-addon"><i class="icon-at"></i></span></div></div>',

                'url' =>
                  '<div class="form-group"><label for="url">' . __( 'Site Address (URL)' ) . '</label>' .
                  '<div class="input-group"><input id="url" name="url" type="text" class="form-control" value="' . esc_attr( $commenter['comment_author_url'] ) .
                  '" /><span class="input-group-addon"><i class="icon-link"></i></span></div></div>',
            );
            
            comment_form( [
                    'fields'=>$fields,
                    'comment_field'=>'<div class="form-group"><label for="comment">'._x( 'Comment', 'noun' ).'</label> <span class="text-danger">*</span><div class="input-group"><textarea id="comment" name="comment" class="form-control" rows="8" tabindex="4" aria-required="true"></textarea><span class="input-group-addon"><i class="icon-mail"></i></span></div></div>',
                    'comment_notes_after'=>'',
                    'class_submit'=>'btn btn-default'
                ] );

            endif; ?>
        </div>

	<?php else : // Comments are closed ?>
		<p><?php _e('Sorry, the comment form is closed at this time.'); ?></p>

	<?php endif; ?>
