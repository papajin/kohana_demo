<?php
/**
 * @package WordPress
 * @subpackage Ivanets_Theme
 */

get_header();

?>
<div class="main col-md-8">
    <h1 class="page-title screen-reader-text"><?php bloginfo ( 'name' ); ?></h1>
    <div class="separator-2"></div>
    <p class="lead"><?php bloginfo ( 'description' ); ?></p>
    <?php if ( have_posts() ) : ?>
    <?php while (have_posts()) : the_post(); ?>

    <article <?php post_class( 'clearfix blogpost object-non-visible animated object-visible fadeInUpSmall' ) ?> id="post-<?php the_ID(); ?>">
        
        <div class="blogpost-body">
            <div class="blogpost-content">
                <header>
                    <h3 class="storytitle"><a href="<?php the_permalink() ?>" rel="bookmark"><?php the_title(); ?></a><?php edit_post_link( sprintf('<span class="btn btn-success-outline m-l-2" title="%s"><i class="icon-pencil"></i>', __( 'Edit' ) ), '', '</span>' ); ?></h3>
                    <div class="submitted"><?php the_time( __( 'j F Y' ) ) ?></div>
                </header>
                
                <?php the_content(''); // no readmore here ?>
                
            </div>
        </div>
        
        <footer class="clearfix">
            <ul class="links pull-left">
                    <li><i class="icon-comment-empty pr-5"></i> <a href="<?php the_permalink() ?>#title_comments"><?= comments_number(); ?></a> |</li>
                    <li><i class="icon-tags pr-5"></i> <?= strip_tags ( get_the_category_list( '', '', FALSE ), '<a>' ); ?> </li>
            </ul>
            <a class="pull-right link" href="<?php the_permalink();?>"><span><?php _e('Read more...');?></span></a>
        </footer>
        
    </article>
    <?php endwhile; else: ?>
    <p><?php _e('Sorry, no posts matched your criteria.'); ?></p>
    <?php endif; ?>

    <?php wp_pagenavi(); // [ 'wrapper_tag' => 'ul', 'wrapper_class' => 'pagination', 'before' => '<li>', 'after' => '</li>', 'pages_text'    => NULL ] ?>
</div>

<?php get_footer();