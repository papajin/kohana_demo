<?php defined('SYSPATH') or die('No direct script access.'); ?>

<?= Form::open( '', [ 'id' => 'edit_form', 'class' => 'm-t-1' ] ); ?>
    <div class="row">
        <div class="form-group col-sm-4">
            <?= Form::label( 'title', sprintf( '%s (ID: %d)', __( 'name' ), $data[ 'id' ] ) ); ?>
            <?= Form::input( 'title', $data[ 'title' ], [ 'id' => 'title', 'class' => 'form-control', 'required' => 'required' ] ); ?>
        </div>
        <div class="form-group col-sm-4">
            <?= Form::label('alias', __( 'alias' ), [ 'title' => __( 'Use letters, digits, underscore and hyphens for making url' ) ] ); ?>
            <?= Form::input('alias', $data[ 'alias' ], [ 'id' => 'alias', 'class' => 'form-control' ] ); ?>
        </div>
        <div class="form-group col-sm-4">
            <?= Form::label( 'parent_id', __( 'parent page' ) ); ?>
            <?= Form::select( 'parent_id', $options, ( is_null( $data[ 'parent_id' ] ) ) ? 0 : $data[ 'parent_id' ], [ 'id' => 'parent_id', 'class' => 'form-control' ] ); ?>
        </div>
    </div>
    <div class="row">
        <div class="form-group col-sm-4">
            <?= Form::label( 'menu', __( 'Menu' ) ); ?>
            <?= Form::input( 'menu', $data[ 'menu' ], [ 'id' => 'menu', 'class' => 'form-control', 'title' => __( 'This is how the menu item going to look' ) ] ); ?>

        </div>
        <div class="form-group col-sm-5">
            <?= Form::label( 'keywords', __( 'keywords' ) ); ?>
            <?= Form::input( 'keywords', $data[ 'keywords' ], [ 'id' => 'keywords', 'class' => 'form-control' ] ); ?>
        </div>
        <div class="form-group col-sm-3">
            <?= Form::label( 'status', __( 'status' ) ); ?>
            <div class="checkbox">
                <?= Form::label( 'status',
                        Form::checkbox( 'status', 1, ( bool )$data[ 'status' ], [ 'id' => 'status', 'class' => 'm-r-1' ] )
                        . '<span class="c-indicator"></span>' .  __( 'published' ),
                        [ 'class' => 'c-input c-checkbox' ] ); ?>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="form-group col-sm-12">
            <?= Form::label( 'description', __( 'description' ) . ' <i id="symbols_cnt"></i>', [ 'title' => __( 'meta description' ) ] ); ?>
            <?= Form::input( 'description', $data[ 'description' ], [ 'id' => 'description', 'class' => 'form-control' ] ); ?>
        </div>
    </div>
    <div class="row">
        <div class="form-group col-sm-12">
            <?= Form::textarea( 'content', $data[ 'content' ],
            [ 'rows' => 40, 'class' => 'form-control ckeditor' ] ); ?>
        </div>
    </div>
<?= Form::close(); ?>