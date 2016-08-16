<?php
/**
 * The Template for displaying all single posts.
 *
 * @package WordPress
 * @subpackage Ivanets_Theme
 */

get_header();

wp_enqueue_script( 'fixedsticky', esc_url( get_template_directory_uri() ) . '/js/fixedsticky.min.js', [], '0.1.0', TRUE );
?>

<div id="content" role="main" class="main col-md-8">
<?php if ( have_posts() ): while ( have_posts() ) : the_post(); ?>    
        
    <h1><?php the_title(); edit_post_link( sprintf('<span class="btn btn-success-outline m-l-1" title="%s"><i class="icon-pencil"></i>', __( 'Edit' ) ), '', '</span>' ); ?></h1>
    <article id="post-<?php the_ID(); ?>" <?php post_class( 'clearfix blogpost full' ); ?>>

        <div class="blogpost-body">
            <div class="blogpost-content">
                <?php the_content(); ?>
                <?php wp_link_pages( [ 'before' => '<div class="page-link">', 'after' => '</div>' ] ); ?>
            </div>
            <footer class="clearfix">
                <div><?php next_post_link( '%link', '<span class="pull-left"><i class="icon-angle-double-left m-r-1"></i>%title</span>', TRUE ); ?></div>
                <div><?php previous_post_link( '%link', '<span class="pull-right">%title<i class="icon-angle-double-right m-l-1"></i></span>', TRUE ); ?></div>
            </footer>
        </div><!-- .blogpost-body -->

    </article><!-- #post-## -->


    <?php comments_template( '', true ); ?>

<?php endwhile; // end of the loop. ?>
<?php endif; ?>
</div><!-- #content -->

<?php get_footer();