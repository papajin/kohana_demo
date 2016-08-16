<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Subtemplate for sitemap with masonry layout.
 */
?>
<div class="masonry-grid row" style="position: relative; height: 2641.16px;">

    <!-- masonry grid item start -->
    <div class="masonry-grid-item col-sm-6">
        <h2><?= __( 'Pages' ); ?></h2>
        <ul class="list">
            <?php foreach ( $pages as $p ): ?>
                <li><?= HTML::anchor( $p->make_uri(), '<i class="icon-angle-right"></i>' . $p->menu ); ?></li>
            <?php endforeach; ?>
            <?php foreach ( $blog_pages as $p ): ?>
                <li><?= HTML::anchor( sprintf( '/blog/%s', $p->post_name ), '<i class="icon-angle-right"></i>' . $p->post_title ); ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
    <!-- masonry grid item end -->

    <!-- masonry grid item start -->
    <div class="masonry-grid-item col-sm-6">
        <h2><?= __( 'Blog' ); ?></h2>
        <ul class="list">
            <?php foreach ( $blog_cats as $cat ): ?>
                <li><?= HTML::anchor( sprintf( '/blog/cat/%s', $cat->slug ), '<i class="icon-angle-right"></i>' . $cat->name ); ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
    <!-- masonry grid item end -->

    <?php foreach ( $categories as $category ): ?>

        <!-- masonry grid item start -->
        <div class="masonry-grid-item col-sm-6">
            <h2><?= HTML::anchor( $category->make_uri(), $category->title ); ?></h2>
            <ul class="list">
                <?php foreach ( $category->articles->where( 'status','>',0 )->where( 'parent_id','=',0 )->find_all()->as_array() as $article ): ?>
                    <li>
                        <?= HTML::anchor( $article->make_uri(), '<i class="icon-angle-right"></i>' . ( ( $article->menu ) ? $article->menu . ' | ' . $article->title : $article->title ) ); ?>
                        <?php if( $article->has_children() ): ?>
                            <ul>
                                <?php foreach ( $article->children->where( 'status', '>', 0 )->find_all()->as_array() as $child ): ?>
                                    <li><?= HTML::anchor( $child->make_uri(), $child->title ); ?></li>
                                <?php endforeach;?>
                            </ul>
                        <?php endif; ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
        <!-- masonry grid item end -->

    <?php endforeach; ?>

    <!-- masonry grid item start -->
    <div class="masonry-grid-item col-sm-6">
        <h2><?= $vocab->menu; ?></h2>
        <ul class="list">
                <li><?= HTML::anchor( $vocab->make_uri(), '<i class="icon-angle-right"></i>' . $vocab->title ); ?></li>
            <?php foreach ( $vocab->children->where( 'status','>',0 )->find_all()->as_array() as $article ): ?>
                <li><?= HTML::anchor( $article->make_uri(), '<i class="icon-angle-right"></i>' . ( ( $article->menu ) ? $article->menu . ' | ' . $article->title : $article->title ) ); ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
    <!-- masonry grid item end -->

</div>