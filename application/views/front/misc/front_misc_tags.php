<?php if ( count( $tags ) ): ?>
<div class="block clearfix">
    <h3 class="title"><?= __( 'Tags' ); ?></h3>
    <div class="separator"></div>
    <div class="tags-cloud">
        <?php foreach ( $tags as $tag ): ?>
        <div class="tag">
            <?= HTML::anchor( sprintf( '/articles/tag/%s', $tag->slug ) , $post_tags[ $tag->slug ] ); ?>
        </div>
        <?php endforeach; ?>
    </div>
</div>
<?php endif; ?>