<?php

/*
 * Template for thumbnail gallery.
 */

if ( $gallery->loaded() ):

    $slides = $gallery->slides->where( 'published', '=', 1 )->order_by( 'order' )->find_all();

    $cnt = count( $slides );
    $cols = ( !empty( $params->cols ) )
            ? $params->cols
            : 6;
?>

<?php if( $gallery->caption ): ?>
<!-- Gallery caption -->
<p class="lead text-center">
    <?= $gallery->caption; ?>
</p>
<?php endif;?>
<div class="gallery">
<?php foreach($slides as $slide): ?>
    <?php $thumb = ($slide->thumb) ? $slide->thumb : $slide->path; ?>
    <div<?=HTML::attributes([ 'class' => 'm-b-1 col-sm-'.(12/$cols).' col-xs-'.(24/$cols).' col-lg-'.(6/$cols) ] ); ?>>
        <div class="overlay-container">
            <?= HTML::image( $thumb, [ 'alt' => $slide->title ] ); ?>
            <?= HTML::anchor( $slide->path, '<i class="icon-zoom-in"></i>', [ 'class' => 'overlay small popup-img', 'title' => $slide->caption ] );?>
        </div>
    </div>
<?php endforeach; ?>
</div>

<?php endif;?>