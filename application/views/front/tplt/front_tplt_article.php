<?php
    $months = array_flip( __( 'months_to_number' ) );
    $time = strtotime( $page->date );
?>
<!-- main-container start -->
<!-- ================ -->
<section class="main-container">

    <div class="container">
        <div class="row">

            <!-- main start -->
            <!-- ================ -->
            <article class="main col-md-8">
                <h1 class="page-title"><?= $page->title; ?></h1>
                <hr>

                <?= $page->content(); ?>

                <footer>
                    <i class="icon-calendar pr-5"></i><?= sprintf( '%d %s %d', date( 'd', $time ), $months [ date( 'm', $time ) ], date( 'Y', $time ) ); ?>
                </footer>
            </article>
            <!-- main end -->

            <!-- sidebar start -->
            <aside class="col-md-3 col-md-offset-1">
                <div class="sidebar">
                    <?php if ( ! empty( $sidebar ) ): ?>
                        <?php foreach ( $sidebar as $widget ): ?>
                            <?= $widget; ?>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </aside>
            <!-- sidebar end -->

        </div>
    </div>
</section>
<!-- main-container end -->