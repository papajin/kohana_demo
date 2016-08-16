<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Default page template. ... At least for library pages.
 */
?>
     <!-- main-container start -->
     <!-- ================ -->
     <section class="main-container">
         <div class="container">
            <div class="row">
                <div class="main col-md-9">
                    <?= $page->content; ?>
                </div> <!-- /.main -->
                <aside class="col-md-3">
                    <div class="sidebar">
                        <?php if ( ! empty( $sidebar ) ): ?>
                            <?php foreach ( $sidebar as $widget ): ?>
                                <?= $widget; ?>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </aside> <!-- sidebar end -->
            </div> <!-- /.row -->
         </div> <!-- /.container -->
     </section> <!-- /.main-container -->