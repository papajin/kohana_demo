<div class="row">
    <div class="btn-group pull-xs-right">
        <?= HTML::anchor( '/admin/article', '<i class=" icon-left-open"></i>', [ 'title' => __( 'Back to list' ), 'class' => 'btn btn-secondary' ] ); ?>
        <?php if ( $instance->loaded() ): ?>
        <?= HTML::anchor( Route::url( 'articles', [ 'controller' => 'articles', 'id' => $instance->alias ] ),
                '<i class="icon-search"></i>',
                [   'title' => __( 'view' ),
                    'target'=>'_blanc',
                    'class' => 'btn btn-secondary' ] ); ?>
        <?php endif; ?>
        <?= Form::button( 'submit', '<i class="icon-floppy"></i> ' . __( 'save' ),  [ 'form' => 'edit_form', 'type'=>'submit', 'class' => 'btn btn-success' ] ); ?>
        <?php if ( $instance->loaded() ): ?>
        <?= HTML::anchor( Route::url( 'admin', [ 'controller' => 'article', 'action' => 'delete', 'id' => $instance->id ] ),
                                    '<i class="icon-cancel"></i>',
                                    [ 'class' => 'btn btn-danger', 'title' => __( 'delete' ) ] ); ?>
        <?php endif; ?>
    </div>
</div>
