<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Accordion template for articles list
 * based on Bootstrap Collapse
 */
 if ( count( $articles ) ):
?>
        <div id="articles" class="panel-group panel-transparent">

        <?php foreach( $articles as $key => $item ): ?>

            <?php if ( ! $item->parent_id ): ?>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <?= HTML::anchor(
                            sprintf( '#collapse-%d', $key ),
                            ( $item->menu ) ? sprintf( '%3$s%1$s%3$s %2$s', $item->title, $item->menu, '<i class="icon-ellipsis-vert"></i>' ) : $item->title,
                            [ 'data-toggle' => 'collapse', 'data-parent' => '#articles', 'class' => ( $key ) ? 'collapsed' : '' ]
                        ); ?>
                    </h4>
                </div>
                <div class="panel-collapse collapse<?php $key OR print ' in'; ?>" id="<?= sprintf( 'collapse-%d', $key ); ?>">
                    <div class="panel-body">
                        <?= ( $item->intro ) ? nl2br( $item->intro ) : nl2br( $item->description ); ?>
                        <?php if( $item->category_id != 10 ): ?>
                        <?= HTML::wrap( HTML::anchor (
                                $item->make_uri(),
                                __( 'More' ),
                                [ 'class' => 'pull-xs-right link' ]
                            ), [ 'class' => 'clearfix' ] ) ;?>
                        <?php endif; ?>
                        <?php if ( $item->has_children() ): ?>
                            <ul>
                            <?php foreach ( $item->get_children() as $child ): ?>
                                <li><?= HTML::anchor(
                                            Route::get( 'articles' )->uri( [ 'controller' => 'articles', 'id' => $child[ 'alias' ]  ] ),
                                            $child[ 'title' ],
                                            [ 'class' => 'link' ] ); ?></li>
                            <?php endforeach; ?>
                            </ul>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php endif; ?>
            
        <?php endforeach; ?>

        </div>

<?php endif;?>