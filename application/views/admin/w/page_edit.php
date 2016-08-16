<?php defined('SYSPATH') or die('No direct script access.'); ?>
<div class="row p-r-1">
    <div class="btn-group pull-xs-right">
        <?= HTML::anchor( '/admin/page', '<i class="icon-left"></i>', [ 'title' => __( 'Back to list' ), 'class' => 'btn btn-secondary' ] ); ?>
        <?php if ( $instance->loaded() )
                    echo HTML::anchor( Route::url( 'articles', [ 'id' => $instance->alias  ] ),
                        '<i class="icon-search"></i>',
                        [ 'title' => __( 'view' ),
                            'target' => '_blanc',
                            'class' => 'btn btn-secondary' ] ); ?>
        <?= Form::button( 'submit', '<i class = "icon-floppy"></i> ' . __( 'save' ),  [ 'form' => 'edit_form', 'type' => 'submit', 'class' => 'btn btn-success' ] ); ?>
        <?php if ( $instance->loaded() )
                    echo HTML::anchor( Route::url( 'admin', [ 'controller' => 'page', 'action' => 'delete', 'id' => $instance->id ] ),
                            '<i class="icon-cancel"></i>',
                            [ 'class' => 'btn btn-danger', 'title' => __( 'delete' ) ] ); ?>
    </div>
</div>
