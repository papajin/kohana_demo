<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Comments carousel template.
 */
    $h2 = HTML::anchor( '/blog/references/#title_comments', sprintf( '<i class="icon-quote-right"></i>%s', __( 'References' ) ) );
?>
<div class="section gray-bg clearfix">
    <div class="owl-carousel content-slider">
        <?php foreach ( $comments as $c ): ?>
        <div class="testimonial">
            <div class="container">
                <div class="row">
                    <div class="col-md-8 col-sm-offset-2">
                        <h2 class="title"><?= $h2; ?></h2>
                        <div class="testimonial-body">
                            <p><?= $c->short(); ?></p>
                            <div class="testimonial-info-1"><?= sprintf( '- %s', $c->comment_author ); ?></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>