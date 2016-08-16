<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Masonry block template for full width pages.
 */
$in = ' in';
?>
    <div class="section clearfix">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <h1 class="text-xs-center"><?= __( 'Astrology without mysticism for business' ); ?></h1>
                    <div class="separator"></div>
                    <p class="lead text-xs-center"><?= __( 'A horoscope is a tool for measuring the time quality' ); ?></p>
                    <br>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="panel-group panel-dark" id="accordion">
                                <?php foreach ( $horoscopes as $key => $essay ): ?>
                                    <?php if( $essay->loaded() ): ?>
                                        <?php $term = $essay->terms->find(); ?>
                                        <div class="panel panel-default">
                                            <div class="panel-heading">
                                                <h4 class="panel-title">
                                                    <a<?= ($in) ? '' : ' class="collapsed"' ; ?> data-toggle="collapse" data-parent="#accordion" href="#<?= $key; ?>">
                                                        <i class="icon-<?= $key; ?>"></i><?= $term->name; ?>
                                                    </a>
                                                </h4>
                                            </div>
                                            <div id="<?= $key; ?>" class="panel-collapse collapse<?= $in; ?>"> <?php $in = ''; ?>
                                                <div class="panel-body clearfix">
                                                    <?= HTML::anchor( $term->make_uri(), '<i class="icon-right"><i class="icon-ellipsis-vert"></i></i>', [ 'class' => 'pull-xs-right', 'title' => __( 'Go to rubric' ), 'data-toggle' => 'tooltip' ] ); ?>
                                                    <h6><?= HTML::anchor( $essay->make_uri(), $essay->post_title, [ 'class' => 'title' ] ); ?></h6>
                                                    <?= $essay->short(); ?>
                                                    <?= HTML::anchor( $essay->make_uri(), __( 'More' ), [ 'class' => 'link pull-xs-right' ] ); ?>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <?= $page->content; ?>
                            <div class="space hidden-md hidden-lg"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?= $feedback; ?>

    <?php if( count( $essays ) ): ?>
    <div class="section clearfix">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <h2><?= HTML::anchor( '/blog/cat/essay', $parent->name ); ?></h2>
                    <div class="separator-2"></div>
                    <p><?= $parent->taxonomy->description; ?></p>
                    <div class="owl-carousel carousel">
                    <?php foreach ( $essays as $essay ): ?>
                        <?php $term = $essay->terms->find(); ?>
                        <div class="image-box object-non-visible" data-animation-effect="fadeInLeft" data-effect-delay="300">
                            <div class="overlay-container">
                                <?= HTML::image( sprintf( '/images/essay/thumb/%s.jpg', $term->slug ) ); ?>
                                <?= HTML::anchor( $term->make_uri(), sprintf( '<i class="icon-link"></i><span class="small">%s</span>', $term->name ), [ 'class' => 'overlay' ] ); ?>
                            </div>
                            <div class="image-box-body">
                                <h3 class="title">
                                    <?= HTML::anchor( $essay->make_uri(), $essay->post_title ); ?>
                                </h3>
                                <p><?= $essay->short( 200 ); ?></p>
                                <?= HTML::anchor( $essay->make_uri(), __( 'More' ), [ 'class' => 'link' ] ); ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>

     <!-- main-container start -->
     <!-- ================ -->
     <section class="section clearfix">
         <div class="container">
            <div class="row">
                <div class="main col-md-12">
                    <!-- masonry grid start -->
                    <div class="masonry-grid row">
                        <?php foreach ( $widgets as $widget ): ?>
                            <div class="masonry-grid-item col-sm-6 col-md-4">
                                <?= $widget; ?>
                            </div>
                        <?php endforeach; ?>
                        <?php foreach ( $articles as $article ):
                            $img = $article->image();
                            $uri = $article->make_uri();
                            $a_time = strtotime( $article->date ); ?>
                        <div class="masonry-grid-item col-sm-6 col-md-4">
                        <article class="blogpost">
                            <?php if ( $img ): ?>
                                <div class="overlay-container">
                                    <?= $img; ?>
                                    <?= HTML::anchor( $uri, '<i class="icon-link"></i>', [ 'class' => 'overlay' ] ); ?>
                                </div>
                            <?php endif; ?>
                            <div class="blogpost-body">
                                <div class="post-info">
                                    <span class="day"><?= date( 'j', $a_time ); ?></span>
                                    <span class="month"><?= sprintf( '%s %d', $months[ date( 'm', $a_time ) ], date( 'Y', $a_time ) ); ?></span>
                                </div>
                                <div class="blogpost-content">
                                    <header>
                                        <h2 class="title">
                                            <?= HTML::anchor( $uri, $article->title ); ?>
                                        </h2>
                                    </header>
                                    <p><?= $article->short(); ?></p>
                                </div>
                            </div>
                            <footer class="clearfix">
                                <?= HTML::anchor( $uri, sprintf( '<span>%s</span>', __( 'More' ) ), [ 'class' => 'pull-xs-right link' ] ); ?>
                            </footer>
                        </article>
                        </div>
                        <?php endforeach; ?>
                    </div><!-- masonry-grid end -->
                </div>
            </div> <!-- /.row -->
         </div> <!-- /.container -->
     </section> <!-- /.main-container -->