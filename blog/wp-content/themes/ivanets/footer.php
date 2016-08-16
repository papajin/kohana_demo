<?php
/**
 * @package WordPress
 * @subpackage Ivanets_Theme
 */
?>

                <!-- begin of #sidebar1 -->
                <?php get_sidebar(); ?> 
                <!-- end of #sidebar1 -->
                </div>
            </div>
        <!-- end .main-container -->
        </section>
<!-- begin footer -->
    <footer id="footer">
        <?php ivanetsMenu::echo_widget( 'footer' ); ?>
        <?php ivanetsMenu::echo_widget( 'subfooter' ); ?>
    </footer>
</div>
<!-- scripts -->
<?php wp_footer(); ?>

</body>
</html>