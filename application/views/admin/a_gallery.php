<div class="alert alert-info small" role="alert"><?= __( 'Fill out form and click Save button' ); ?></div>
<?= HTML::anchor( '#edit_form', '<i class="icon-minus-squared-alt"></i>', [ 'class' => 'pull-xs-right btn', 'data-toggle' => 'collapse' ] ); ?>
<?= Form::open( '', [ 'id' => 'edit_form', 'class' => 'collapse in' ] ); ?>
    <?//= Form::hidden( 'id', $data[ 'id' ] ); ?>
        <div class="form-group row">
            <?= Form::label( 'title', __( 'name' ), [ 'title' => __( 'gallery name' ), 'class' => 'col-sm-2 form-control-label' ] ); ?>
            <div class="col-sm-6">
                <?= Form::input( 'title', $data[ 'title' ], [ 'id' => 'title', 'class' => 'form-control', 'placeholder' => __( 'new gallery' ) ] ); ?>
            </div>
        </div>
        <div class="form-group row">
            <?= Form::label( 'thumb', __( 'thumb' ), [ 'class' => 'col-sm-2 form-control-label' ] ); ?>
            <div class="col-sm-6">
                <?= Form::input( 'thumb', $data[ 'thumb' ], [ 'id' => 'thumb', 'class' => 'form-control', 'placeholder' => __( 'Path to thumb' ) ] ); ?>
            </div>
            <div class="col-sm-4">
                <?= HTML::image( $data[ 'thumb' ], [ 'id' => 'gallery_thumb', 'class' => 'img-fluid' ] ); ?>
            </div>
        </div>
        <div class="form-group row">
            <?= Form::label( 'caption', __( 'description' ), [ 'class'=>'col-sm-2 form-control-label' ] ); ?>
            <div class="col-sm-10">
                <?= Form::textarea( 'caption', $data[ 'caption' ], [ 'id' => 'caption', 'class' => 'form-control', 'placeholder' => __( 'gallery short description' ) ] ); ?>
            </div>
        </div>
    <?=Form::close()?><p class="clearfix"></p>
    <div class="row m-b-3<?php if( ! $data[ 'id' ] ) echo ' invisible'; ?>">
        <div id="slides" class="clearfix">
            
        </div>
        <div class="m-l-1 btn-group">
            <?= Form::button( 'slide_new', '<i class="icon-plus-squared-alt"></i>',  [ 'title' => __( 'add slide' ), 'class' => 'btn btn-secondary', 'data-toggle' => 'tooltip' ] ); ?>
            <?= Form::button( 'save_order', '<i class="icon-floppy"></i> ' . __( 'save order' ),  [ 'class' => 'btn btn-secondary' ] ); ?>
        </div>
    </div>

<div class="hidden-xs-up" id="slide-template">
    <div class="col-sm-4 col-md-3 col-lg-2">
        <div class="card">
          <?= Form::hidden( 'slide_thumb' ); ?>
          <img class="card-img-top center-block" style="height:120px;cursor:move;" src="">
          <div class="card-block">
            <h4>Card title</h4>
            <p class="card-text">
                <i class="icon-info-circled m-l-2" title=""></i>
                <?= Form::label( '', Form::checkbox( 'publish_slide' ), [ 'title' => __( 'published' ) ] ); ?>
          </p>
          <div class="btn-group btn-group-sm">
                <?= HTML::anchor( '#', '<i class="icon-pencil"></i>', [ 'class' => 'btn btn-secondary', 'title' => __( 'edit slide' ), 'role' => 'button' ] ); ?>
                <?= HTML::anchor( '#', '<i class="icon-cancel"></i>', [ 'class' => 'btn btn-danger', 'title' => __( 'delete slide' ), 'role' => 'button' ] ); ?>
            </div>    
          </div>
        </div>
    </div>
</div>
    