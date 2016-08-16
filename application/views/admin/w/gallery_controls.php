<div class="row">
    <div class="col-sm-12">
        <div class="pull-xs-left form-inline">
            <?= Form::label( 'galleries', Form::select( 'the_gallery', $galleries, $id, [ 'id' => 'the_gallery', 'class' => 'form-control' ] ) ); ?>
        </div>
        <div class="btn-group pull-xs-right">
            <?= Form::button( 'gal_new', '<i class="icon-plus-squared-alt"></i> ' . __( 'new gallery' ),  [ 'class' => 'btn btn-secondary' ] ); ?>
            <?= Form::button( 'slide_new', '<i class="icon-leaf"></i> ' . __( 'new slide' ),  [ 'class' => 'btn btn-secondary' ] ); ?>
            <?= Form::button( 'gal_save', '<i class="icon-floppy"></i> ' . __( 'save' ),  [ 'form' => 'edit_form', 'class' => 'btn btn-success' ] ); ?>
            <?= Form::button( 'gal_delete', '<i class="icon-cancel"></i>',  [ 'title' => __( 'delete' ), 'class' => 'btn btn-danger', 'data-toggle' => 'tooltip' ] ); ?>
        </div>
    </div>
</div>
