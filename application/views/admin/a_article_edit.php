<?php defined('SYSPATH') or die('No direct script access.'); ?>

<?=Form::open( '', [ 'id' => 'edit_form', 'class' => 'm-t-1' ] ); ?>
    <div class="row">
        <div class="form-group col-sm-4">
            <?= Form::label( 'category_id', __( 'Category' ) ); ?>
            <?= Form::select( 'category_id', $categories, $data[ 'category_id' ], [ 'class' => 'form-control' ] ); ?>
        </div>
        <div class="form-group col-sm-4">
            <?= Form::label( 'parent_id', __( 'parent article' ) ); ?>
            <?= Form::select( 'parent_id', $options, intval ( $data[ 'parent_id' ] ), [ 'id' => 'parent_id', 'class' => 'form-control' ] ); ?>
        </div>
        <div class="form-group col-sm-4">
            <div class="col-lg-6">
                <?= Form::label( 'status', __( 'status' ) ); ?>
                <div class="checkbox">
                    <?= Form::label( 'status',
                        Form::checkbox( 'status', 1, ( bool )$data[ 'status' ], [ 'id' => 'status', 'class' => 'm-r-1' ] )
                        . '<span class="c-indicator"></span>' .  __( 'published' ),
                        [ 'class' => 'c-input c-checkbox' ] ); ?>
                </div>
            </div>
            <div class="col-lg-6">
                <?= Form::label( 'widget', __( 'On homepage' ) ); ?>
                <div class="checkbox">
                    <?= Form::label( 'widget',
                        Form::checkbox( 'widget', 1, ( bool )$data[ 'widget' ], [ 'id' => 'widget', 'class' => 'm-r-1' ] )
                        . '<span class="c-indicator"></span>' .  __( 'into widget' ),
                        [ 'class' => 'c-input c-checkbox' ] ); ?>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="form-group col-sm-4">
            <?= Form::label( 'title', sprintf( '%s (ID: <i>%d</i>)', __( 'name' ), $data[ 'id' ] ) ); ?>
            <?= Form::input( 'title', $data['title'], [ 'id' => 'title', 'class' => 'form-control', 'required' => 'required' ] ); ?>
        </div>
        <div class="form-group col-sm-4">
            <?= Form::label( 'alias', __( 'alias' ), [ 'title' => __( 'Use letters, digits, underscore and hyphens for making url' ) ] ); ?>
            <?= Form::input( 'alias', $data[ 'alias' ], [ 'id' => 'alias', 'class' => 'form-control' ] ); ?>
        </div>
        <div class="form-group col-sm-4">
            <?= Form::label( 'menu', __( 'Menu' ) ); ?>
            <?= Form::input( 'menu', $data[ 'menu' ], [ 'id' => 'menu', 'class' => 'form-control', 'title' => __( 'common title for group of articles' ) ] ); ?>
        </div>
    </div>
    <div class="row">
        <div class="form-group col-sm-3">
            <?= Form::label( 'date', __( 'date' ), [ 'title' => __( 'published on' ) ] ); ?>
            <?= Form::input( 'date', $data[ 'date' ], [ 'id' => 'date', 'class' => 'form-control', 'type' => 'date' ] ); ?>
        </div>
        <div class="form-group col-sm-9">
            <?=Form::label( 'keywords', __( 'keywords' ) ); ?>
            <?=Form::input( 'keywords', $data[ 'keywords' ], [ 'id' => 'keywords', 'class' => 'form-control' ] ); ?>
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
            <?= Form::label( 'intro', HTML::anchor( '#intro', __( 'intro' ), [ 'class' => 'dropdown-toggle', 'data-toggle' => 'collapse' ] ) ); ?>
            <?= Form::textarea( 'intro', $data[ 'intro' ], [ 'id' => 'intro', 'rows' => 7, 'class' => 'form-control panel-collapse collapse in' ] ); ?>
        </div>
    </div>
    <?php if ( $tags ): ?>
    <div class="row m-b-1">
        <div class="form-group col-sm-12">
            <?= Form::label( 'cloud', HTML::anchor( '#cloud', __( 'Tags' ), [ 'class' => 'dropdown-toggle', 'data-toggle' => 'collapse' ] ) ); ?>
        </div>
        <div id="cloud" class="panel-collapse collapse in col-sm-12">
            <?= $tags; ?>
        </div>
    </div>
    <?php endif; ?>
    <div class="row">
        <div class="form-group col-sm-12">
            <?= Form::label( 'text', __( 'text' ) ); ?>
            <?= Form::textarea( 'text', $data[ 'text' ], [ 'id' => 'text', 'class' => 'form-control ckeditor' ] ); ?>
        </div>
    </div>
<?=Form::close()?>
    