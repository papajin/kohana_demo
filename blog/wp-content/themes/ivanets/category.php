<?php
/**
 * The template for displaying Category Archive pages.
 *
 * @package WordPress
 * @subpackage Ivanets_Theme
 */

get_header(); ?>

<div class="main col-md-8">
    <h1 class="page-title screen-reader-text"><?php single_cat_title(); ?></h1>
    <div class="separator-2"></div>
    <?php if ( cat_is_ancestor_of( 437, $cat ) ): ?>
        <img src="/images/essay/<?= $category_name; ?>.jpg" class="img-fluid">
    <?php endif; ?>
    <?php
        $category_description = category_description();
        empty( $category_description ) OR print '<div class="lead">' . $category_description . '</div>';
    ?>
    
    <?php if (have_posts()) : ?>
    <?php while (have_posts()) : the_post(); ?>

    <article <?php post_class( 'clearfix blogpost object-non-visible animated object-visible fadeInUpSmall' ) ?> id="post-<?php the_ID(); ?>">
        <div class="blogpost-body">
            <div class="blogpost-content">
                <header>
                    <h3 class="storytitle"><a href="<?php the_permalink() ?>" rel="bookmark"><?php the_title(); ?></a><?php edit_post_link( sprintf('<span class="btn btn-success-outline m-l-2" title="%s"><i class="icon-pencil"></i>', __( 'Edit' ) ), '', '</span>' ); ?></h3>
                    <div class="submitted"><?php the_time( __( 'j F Y' ) ) ?></div>
                </header>
                <?php the_excerpt( '' ); // empty string param means that we want no readmore link here ?>
            </div>
        </div>
        <footer class="clearfix">
            <ul class="links pull-left">
                    <li><i class="icon-comment-empty pr-5"></i> <a href="<?php the_permalink() ?>#title_comments"><?= comments_number(); ?></a></li>
            </ul>
            <a class="pull-right link" href="<?php the_permalink();?>"><span><?php _e('Read more...');?></span></a>
        </footer>    
    </article>
            
    <?php endwhile; else: ?>
    <p><?php _e('Sorry, no posts matched your criteria.'); ?></p>
    <?php endif; ?>
    <?php wp_pagenavi(); ?>
</div><!-- #content -->

<?php get_footer();