<?php
/**
 * @package WordPress
 * @subpackage Ivanets_Theme
 */

get_header();

?>
<div class="main col-md-8">
    <h1 class="page-title screen-reader-text"><?php single_post_title(); ?></h1>
    <hr>
<?php if ( have_posts() ) : ?>
	<?php while ( have_posts() ) : the_post(); ?>


<div id="post-<?php the_ID(); ?>">

    <?php the_content(); ?>

</div>

<?php comments_template(); // Get wp-comments.php template ?>

<?php endwhile; else: ?>
<p><?php _e('Sorry, no posts matched your criteria.'); ?></p>
<?php endif; ?>

<?php wp_pagenavi( [ 'pages_text'    => NULL ] ); ?>
<?php //print_r( paginate_links( [ 'type' => 'array' ] ) );//$pages_list =  ?>
<p class="clearfix"></p>

</div>

<?php get_footer();