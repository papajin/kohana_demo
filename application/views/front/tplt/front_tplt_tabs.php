<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Tabs (Bootstrap pills) template.
 */
if ( Valid::not_empty( $tabs ) ):
    $uri_active = '/' . Request::$initial->uri();
?>

    <ul class="nav nav-pills m-y-2" role="tablist">
    <?php foreach( $tabs as $tab ): ?>

        <?php $tab_uri = $tab->make_uri(); ?>

        <?php if ( $tab->alias == 'astro_vocabulary' ):
            $children = $tab->children->where( 'status', '>', 0 )->find_all(); ?>
        <li class="pull-xs-left dropdown<?php if ( $is_vocab ) echo ' active'; ?>">
            <?= HTML::anchor( '#', $tab->menu, [ 'class' => 'dropdown-toggle', 'data-toggle' => 'dropdown', 'role' => 'button', 'aria-haspopup' => TRUE, 'aria-expanded' => FALSE ] ); ?>
            <div class="dropdown-menu">
                <?= HTML::anchor( $tab_uri, $tab->title, [ 'class' => 'dropdown-item' ] ); ?>
                <?php foreach ( $children as $child ): ?>
                    <?= HTML::anchor( $child->make_uri(), $child->title, [ 'class' => 'dropdown-item' ] ); ?>
                <?php endforeach; ?>
            </div>
        </li>
        <?php else: ?>
        <li class="pull-xs-left<?php if ( $uri_active == $tab_uri ) echo ' active'; ?>">
            <?= HTML::anchor( $tab_uri, $tab->menu ); ?>
        </li>
        <?php endif; ?>

    <?php endforeach; ?>
    </ul>

<?php endif; ?>