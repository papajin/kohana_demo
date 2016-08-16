<?php

/*
 * Template for gallery.
 * Thumb to trigger magnific-popup slide show.
 */

if ( $gallery->loaded() ):

    $slides = $gallery->slides->where( 'published', '=', 1 )->order_by( 'order' )->find_all();
?>

<div class="card">
    <div class="overlay-container gallery">
        <?= HTML::image( $gallery->thumb ?? $slides[0]->thumb,
        [ 'class' => 'card-img-top center-block', 'alt' => $gallery->title ] ); ?>
        <?= HTML::anchor( $slides[ 0 ]->path, '<i class="icon-zoom-in"></i>', [ 'class' => 'overlay popup-img', 'title' => $slides[ 0 ]->caption ] ); ?>
        <div class="invisible">
            <?php foreach ( $slides as $i => $slide ): ?>
                <?php if ( $i ) echo HTML::anchor( $slides[ $i ]->path, '', [ 'class' => 'overlay popup-img', 'title' => $slides[ $i ]->caption ] ); ?>
            <?php endforeach; ?>
        </div>

    </div>
    <div class="card-block">
        <h2><?= $gallery->title; ?></h2>
        <?php if( $gallery->caption ): ?>
        <p><?= $gallery->caption; ?></p>
        <?php endif; ?>
    </div>
</div>
<?php endif; ?>