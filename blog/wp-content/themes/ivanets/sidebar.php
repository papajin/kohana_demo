<?
/**
 * @package WordPress
 * @subpackage Ivanets_Theme
 */
 ?>
<aside class="col-md-3 col-md-offset-1">
    <div class="sidebar">
        <?php ivanets_bookmarks(); ?>
        <?php if ( is_active_sidebar( 'sidebar-1' ) ) : ?>
        <div class="block clearfix widget-area" role="complementary">
            <?php dynamic_sidebar( 'sidebar-1' ); ?>
        </div><!-- #secondary -->
        <?php endif; ?>
        <div class="block clearfix"><?php ivanetsMenu::echo_widget( 'YandexVer' ); ?></div>
    </div>
</aside>